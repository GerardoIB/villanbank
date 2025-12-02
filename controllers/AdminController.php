<?php
require_once BASE_PATH . '/utils/View.php';
require_once BASE_PATH . '/models/UserModel.php';
require_once BASE_PATH . '/models/LevelModel.php';
require_once BASE_PATH . '/models/AccountModel.php';

class AdminController
{
    public function index()
    {
        // ✅ CAMBIO AQUÍ
        if (!isset($_SESSION['user_level_id']) || $_SESSION['user_level_id'] != 2) {
            header("Location: " . app_url . "/auth/auth");
            exit;
        }
        $dashboardData = ['name' => $_SESSION['user_person_name'] ?? 'Admin'];
        View::render("Admin/IndexView", ["dashboard" => $dashboardData]);
    }

    public function accounts()
    {
        // ✅ CAMBIO AQUÍ
        if (!isset($_SESSION['user_level_id']) || $_SESSION['user_level_id'] != 2) {
            header("Location: " . app_url . "/auth/auth");
            exit;
        }
        $dashboardData = ['name' => $_SESSION['user_person_name'] ?? 'Admin'];
        View::render("Admin/AccountsView", ["dashboard" => $dashboardData]);
    }

    // ... (Los métodos API read(), updateLevel(), etc. NO necesitan cambios porque devuelven JSON) ...
    // Solo asegúrate de incluir el resto de métodos que ya teníamos.


    // ====================================================================
    // SECCIÓN 2: API JSON (Para DataTables y AJAX)
    // ====================================================================

    /**
     * API: Devuelve usuarios y niveles para la tabla de Usuarios
     * URL: /pagina/admin/read
     */
    public function read()
    {
        $userModel = new UserModel();
        $levelModel = new LevelModel();

        $data = [
            'users'  => $userModel->read(),
            'levels' => $levelModel->read()
        ];

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * API: Devuelve las cuentas bancarias para la tabla de Cuentas
     * URL: /pagina/admin/readAccountsJson
     */
    public function readAccountsJson()
    {
        // Usamos el AccountModel para obtener los datos
        $model = new AccountModel();

        // Llamamos a la función que obtiene cuentas + nombres de dueños
        $data = $model->readForAdmin();

        header('Content-Type: application/json');
        // Enviamos el array bajo la clave "accounts" que espera el JS
        echo json_encode(['accounts' => $data]);
        exit;
    }


    // ====================================================================
    // SECCIÓN 3: ACCIONES DE USUARIOS (CRUD)
    // ====================================================================

    /**
     * Actualiza el Nivel de un usuario
     */
    public function updateLevel()
    {
        header('Content-Type: application/json');

        if (!isset($_POST['infoUpdate'])) {
            echo json_encode(['status' => 0, 'message' => 'No se recibieron datos.']);
            exit;
        }

        try {
            $info = $_POST['infoUpdate'];
            $pkUser = $info[0];
            $pkLevel = $info[1];

            $userModel = new UserModel();
            $success = $userModel->updateLevel($pkUser, $pkLevel);

            if ($success) {
                echo json_encode(['status' => 1, 'message' => 'Nivel actualizado.']);
            } else {
                echo json_encode(['status' => 0, 'message' => 'Error al actualizar BD.']);
            }
        } catch (Exception $e) {
            error_log("Error updateLevel: " . $e->getMessage());
            echo json_encode(['status' => 0, 'message' => 'Error interno.']);
        }
        exit;
    }

    /**
     * Bloquea o Desbloquea un usuario (Acceso al sistema)
     */
    public function toggleLock()
    {
        header('Content-Type: application/json');

        if (!isset($_POST['pkUser'])) {
            echo json_encode(['status' => 0, 'message' => 'Falta ID usuario.']);
            exit;
        }

        try {
            $pkUser = $_POST['pkUser'];
            $userModel = new UserModel();
            $success = $userModel->toggleLockStatus($pkUser);

            if ($success) {
                echo json_encode(['status' => 1, 'message' => 'Estado actualizado.']);
            } else {
                echo json_encode(['status' => 0, 'message' => 'Error en BD.']);
            }
        } catch (Exception $e) {
            error_log("Error toggleLock: " . $e->getMessage());
            echo json_encode(['status' => 0, 'message' => 'Error interno.']);
        }
        exit;
    }

    /**
     * Elimina un usuario y sus datos personales
     */
    public function deleteUser()
    {
        header('Content-Type: application/json');

        if (!isset($_POST['pkUser'])) {
            echo json_encode(['status' => 0, 'message' => 'Falta ID usuario.']);
            exit;
        }

        try {
            $pkUser = $_POST['pkUser'];
            $userModel = new UserModel();
            $success = $userModel->deleteUser($pkUser);

            if ($success) {
                echo json_encode(['status' => 1, 'message' => 'Usuario eliminado.']);
            } else {
                echo json_encode(['status' => 0, 'message' => 'No se pudo eliminar.']);
            }
        } catch (Exception $e) {
            error_log("Error deleteUser: " . $e->getMessage());
            echo json_encode(['status' => 0, 'message' => 'Error interno.']);
        }
        exit;
    }

    /**
     * Actualiza datos personales (y teléfono si cambió)
     */
    public function updateUser()
    {
        header('Content-Type: application/json');

        if (!isset($_POST['original_phone'])) {
            echo json_encode(['status' => 0, 'message' => 'Datos incompletos.']);
            exit;
        }

        try {
            $originalPhone = $_POST['original_phone'];
            $newPhone      = $_POST['new_phone'];
            $person        = $_POST['person'];
            $firstName     = $_POST['first_name'];
            $lastName      = $_POST['last_name'];

            $userModel = new UserModel();
            $success = $userModel->updatePersonData($originalPhone, $newPhone, $person, $firstName, $lastName);

            if ($success) {
                echo json_encode(['status' => 1, 'message' => 'Datos actualizados.']);
            } else {
                echo json_encode(['status' => 0, 'message' => 'Error al guardar.']);
            }
        } catch (Exception $e) {
            error_log("Error updateUser: " . $e->getMessage());
            echo json_encode(['status' => 0, 'message' => 'Error interno.']);
        }
        exit;
    }

    /**
     * API: Elimina una cuenta bancaria (y sus transacciones por cascada)
     * URL: /pagina/admin/deleteAccount
     */
    public function deleteAccount()
    {
        header('Content-Type: application/json');

        if (!isset($_POST['pk_account'])) {
            echo json_encode(['status' => 0, 'message' => 'Falta ID.']);
            exit;
        }

        try {
            $id = $_POST['pk_account'];
            $model = new AccountModel(); // Asegúrate de tener el require_once arriba
            $success = $model->deleteAccount($id);

            if ($success) {
                echo json_encode(['status' => 1, 'message' => 'Cuenta eliminada permanentemente.']);
            } else {
                echo json_encode(['status' => 0, 'message' => 'No se pudo eliminar.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 0, 'message' => 'Error interno.']);
        }
        exit;
    }
}