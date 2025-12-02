<?php
// controllers/AuthController.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../utils/View.php';
require_once __DIR__ . '/../entitys/UserEntity.php';
require_once __DIR__ . '/../models/PersonModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/AccountModel.php';

class AuthController
{
    // ... (Métodos create, auth, forget siguen igual, solo renderizan vistas) ...
    public function create() { View::render("Users/CreateView", ["create" => 0]); }
    public function auth() { View::render("Users/AuthView", ["Auth" => 0]); }
    public function forget() { View::render("Users/ForgetView", ["forget" => 0]); }

    public function login()
    {
        if (isset($_POST['infoLogin'])){
            header('Content-Type: application/json');
            try {
                $infoLogin = $_POST['infoLogin'];
                $phone = $infoLogin[0];
                $password = $infoLogin[1];

                $userEntity = new UserEntity();
                $userEntity->__login($phone, $password);

                $userModel = new UserModel();
                $userData = $userModel->login($userEntity);

                if ($userData !== false) {
                    if (session_status() == PHP_SESSION_NONE) session_start();

                    $_SESSION['user_phone'] = $phone;
                    $_SESSION['user_level_id'] = $userData['id'];
                    $_SESSION['user_level_name'] = $userData['levelName'];
                    $_SESSION['user_person_name'] = $userData['personName'];

                    // ✅ CAMBIO AQUÍ: Usamos la constante app_url
                    $redirectUrl = '';
                    $baseURL = app_url; // Dinámico

                    switch ($userData['id']) {
                        case 1: $redirectUrl = $baseURL . '/guest'; break;
                        case 2: $redirectUrl = $baseURL . '/admin'; break;
                        case 3: $redirectUrl = $baseURL . '/cashier'; break;
                        case 4: $redirectUrl = $baseURL . '/teacher'; break;
                        case 5: $redirectUrl = $baseURL . '/student'; break;
                        default: $redirectUrl = $baseURL . '/guest';
                    }

                    $action = [
                        'status' => 1,
                        'message' => 'Login exitoso.',
                        'levelName' => $userData['levelName'],
                        'redirectUrl' => $redirectUrl
                    ];
                } else {
                    $action = ['status' => 0, 'message' => 'Credenciales incorrectas.'];
                }
            } catch (Exception $e) {
                error_log("Error login: " . $e->getMessage());
                $action = ['status' => 2, 'message' => 'Error interno.'];
            }
            echo json_encode($action);
        }
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        session_destroy();

        // ✅ CAMBIO AQUÍ: Redirección dinámica al salir
        header("Location: " . app_url . "/auth/auth");
        exit;
    }

    /**
     * Procesa la solicitud de registro (recibe datos por POST de un JS)
     * URL NUEVA: /auth/register_process
     */
    public function register_process() {

        header('Content-Type: application/json');
        $action = [];

        if (!isset($_POST['infoRegister'])) {
            $action = ['status' => 0, 'message' => 'Error: No se recibieron datos.'];
            echo json_encode($action);
            return;
        }

        try {
            $infoRegister = $_POST['infoRegister'];

            // 1. Validar datos de entrada (Ahora esperamos 7 elementos)
            if (count($infoRegister) < 7) {
                $action = ['status' => 0, 'message' => 'Error: Faltan datos en el formulario.'];
                echo json_encode($action);
                return;
            }

            // Mapeamos los datos del array (NUEVA ESTRUCTURA)
            $name = trim($infoRegister[0]);     // "JUAN"
            $paterno = trim($infoRegister[1]);  // "GARCIA"
            $materno = trim($infoRegister[2]);  // "VILLAFUERTE"
            $phone = trim($infoRegister[3]);    // ej: "7121493064"
            $password = trim($infoRegister[4]);
            $gender = $infoRegister[5];
            $birthday = $infoRegister[6];

            // --- Calculamos el pk_user ---
            $pkUser = substr($phone, -4); // ej: "3064"

            // Validación simple (puedes mejorarla)
            if (empty($name) || empty($paterno) || empty($materno) || empty($phone) || empty($password) || empty($gender) || empty($birthday)) {
                $action = ['status' => 0, 'message' => 'Todos los campos son obligatorios.'];
                echo json_encode($action);
                return;
            }

            // Validación de teléfono
            if (!preg_match('/^[0-9]{10}$/', $phone)) {
                $action = ['status' => 0, 'message' => 'El teléfono debe ser de 10 dígitos.'];
                echo json_encode($action);
                return;
            }

            // 2. Preparar la Entidad
            $userEntity = new UserEntity();
            $defaultLevel = 1; // Nivel Guest por defecto
            $defaultLocked = 0; // Valor 'locked' por defecto

            // --- LLAMADA A __register MODIFICADA (10 parámetros) ---
            $userEntity->__register(
                $pkUser,     // "3064"
                $phone,      // "7121493064" (pkPerson)
                $name,       // "JUAN" ('person')
                $paterno,    // "GARCIA" ('first_name')
                $materno,    // "VILLAFUERTE" ('last_name')
                $gender,
                $birthday,
                $password,
                $defaultLevel,
                $defaultLocked
            );
            // --- FIN MODIFICACIÓN ---

            // 3. Guardar en tbl_persons PRIMERO
            $personModel = new PersonModel();
            $personModel->savePerson($userEntity);

            // 4. Guardar en tbl_users SEGUNDO
            $userModel = new UserModel();
            $userModel->saveUser($userEntity);

            require_once BASE_PATH . '/models/AccountModel.php';
            $accountModel = new AccountModel();

            // Le creamos la cuenta al usuario recién registrado ($pkUser)
            // La BD le asignará el siguiente número disponible (ej: 900031)
            $accountModel->createAccount($pkUser);

            // 5. Éxito
            $action = ['status' => 1, 'message' => '¡Registro exitoso! Ya puedes iniciar sesión.'];

        } catch (PDOException $e) {
            // Manejo de errores
            if ($e->getCode() == 23000) { // Error de clave duplicada
                $action = ['status' => 0, 'message' => 'Error: El número de teléfono ya está registrado.'];
            } else {
                $action = ['status' => 0, 'message' => 'Error interno del servidor. Intente más tarde.'];
                error_log("Error en register_process (PDO): " . $e->getMessage());
            }
        } catch (Exception $e) {
            $action = ['status' => 0, 'message' => 'Ocurrió un error inesperado.'];
            error_log("Error general en register_process: " . $e->getMessage());
        }

        // 6. Enviar respuesta JSON
        echo json_encode($action);
    }

}