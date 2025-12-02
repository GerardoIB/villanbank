<?php

// --- PUNTO DE ENTRADA (FRONT CONTROLLER) ---

// Muestra todos los errores de PHP (solo para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define la ruta raíz absoluta del proyecto (ej: /var/www/pagina)
define('BASE_PATH', __DIR__);

// Comprueba si no hay una sesión activa
if (session_status() === PHP_SESSION_NONE) {
    // Inicia la sesión si no existe
    session_start();
}

// Carga el archivo principal del enrutador
require_once BASE_PATH . '/Router.php';

// Crea una nueva instancia del enrutador
$router = new Router();
// Ejecuta el método que maneja la URL actual
$router->dispatch();