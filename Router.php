<?php

class Router
{
    // --- Propiedades por defecto ---
    protected $controller = 'GuestController'; // Controlador a cargar si la URL está vacía
    protected $method = 'index'; // Método a llamar si la URL no especifica uno
    protected $params = []; // Parámetros para el método (ej: /user/edit/1)

    /**
     * El constructor analiza la URL para definir el controlador, método y parámetros
     */
    public function __construct()
    {
        // Obtiene la URL actual (ej: /pagina/user/login)
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Limpia la URL quitando el directorio base '/pagina'
        $uri = str_replace('/pagina', '', $uri);
        // Quita las barras '/' de inicio y fin (ej: 'user/login')
        $uri = trim($uri, '/');

        // Convierte la URL limpia en un array (ej: ['user', 'login'])
        $segments = explode('/', $uri);

        // --- 1. Definir el Controlador ---
        if (!empty($segments[0])) {
            // Convierte el primer segmento (ej: 'user') a 'UserController'
            $controllerName = ucfirst($segments[0]) . 'Controller';
            // Define la ruta al archivo (ej: .../controllers/UserController.php)
            $controllerFile = BASE_PATH . '/controllers/' . $controllerName . '.php';

            // Comprueba si el archivo del controlador existe
            if (file_exists($controllerFile)) {
                // Si existe, lo usamos como el controlador
                $this->controller = $controllerName;
                // Removemos el controlador del array de segmentos
                unset($segments[0]);
            }
        }

        // Carga el archivo del controlador (ya sea el nuevo o el 'GuestController')
        require_once BASE_PATH . '/controllers/' . $this->controller . '.php';

        // --- 2. Definir el Método ---
        if (isset($segments[1]) && !empty($segments[1])) {
            // Comprueba si el método (ej: 'login') existe dentro de la clase Controlador
            if (method_exists($this->controller, $segments[1])) {
                // Si existe, lo usamos como el método
                $this->method = $segments[1];
                // Removemos el método del array de segmentos
                unset($segments[1]);
            }
        }

        // --- 3. Definir los Parámetros ---
        // Lo que sobre en el array $segments son los parámetros
        $this->params = $segments ? array_values($segments) : [];
    }

    /**
     * Ejecuta el controlador y el método que se definieron en el constructor
     */
    public function dispatch()
    {
        // Crea una instancia del controlador (ej: new UserController)
        $controllerInstance = new $this->controller;

        // Llama al método (ej: $controllerInstance->login()) y le pasa los parámetros
        call_user_func_array([$controllerInstance, $this->method], $this->params);
    }
}