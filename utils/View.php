<?php

// Define la clase 'View' (una utilidad para manejar las vistas HTML)
class View
{
    /**
     * Renderiza (muestra) un archivo de vista y le pasa datos.
     * Es 'static' para poder llamarla como: View::render('vista', $datos)
     *
     * @param string $view El nombre del archivo de vista (ej: "Users/AuthView")
     * @param array $data (Opcional) Un array de datos para la vista
     */
    public static function render($view, $data = [])
    {
        // Convierte las claves del array $data en variables
        // Ejemplo: Si $data = ['dashboard' => $info], esto crea la variable $dashboard
        extract($data);

        // Construye la ruta completa al archivo .php de la vista
        // __DIR__ es el directorio de este archivo (utils)
        // ../ sube un nivel (a la raíz del proyecto)
        // /views/ entra a la carpeta de vistas
        $viewFile = __DIR__ . "/../views/" . $view . ".php";

        // Comprueba si el archivo de la vista que se pidió no existe
        if (!file_exists($viewFile))
        {
            // Si no existe, detiene la aplicación y muestra un error claro
            die("Error: The view <b>$view</b> not found.");
        }

        // Si existe, incluye (carga y ejecuta) el archivo .php de la vista
        // Este archivo (ej: IndexView.php) ahora tendrá acceso a las variables ($dashboard)
        include $viewFile;
    }
}
?>