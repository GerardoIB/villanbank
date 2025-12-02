<?php
// index.php - Versión con Debug para Producción

// ========================================
// 1. INICIALIZACIÓN Y CONFIGURACIÓN
// ========================================
try {
    // Cargar configuración
    require_once __DIR__ . '/config/config.php';
    
} catch (Exception $e) {
    error_log("Error en config.php: " . $e->getMessage());
    die("Error de configuración del sistema");
}

// ========================================
// 2. VERIFICAR INSTALACIÓN
// ========================================
$requiredFiles = [
    __DIR__ . '/config/config.php',
    __DIR__ . '/utils/View.php',
    __DIR__ . '/.htaccess'
];

foreach ($requiredFiles as $file) {
    if (!file_exists($file)) {
        error_log("Archivo requerido no encontrado: $file");
        die("Error: Sistema no configurado correctamente");
    }
}

// ========================================
// 3. MODO MANTENIMIENTO
// ========================================
if (defined('MAINTENANCE_MODE') && MAINTENANCE_MODE === true) {
    http_response_code(503);
    die('Sistema en mantenimiento. Por favor, vuelve más tarde.');
}

// ========================================
// 4. ROUTING BÁSICO
// ========================================
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';

// Limpiar la URI
$basePath = str_replace('/index.php', '', $scriptName);
$route = str_replace($basePath, '', $requestUri);
$route = strtok($route, '?'); // Remover query string
$route = trim($route, '/');

// Parsear la ruta
$parts = explode('/', $route);
$module = $parts[0] ?? 'home';
$action = $parts[1] ?? 'index';

// Log de debug (solo en desarrollo)
if (!isset($isProduction) || !$isProduction) {
    error_log("REQUEST_URI: $requestUri");
    error_log("SCRIPT_NAME: $scriptName");
    error_log("BASE_PATH: $basePath");
    error_log("ROUTE: $route");
    error_log("MODULE: $module | ACTION: $action");
}

// ========================================
// 5. ROUTER
// ========================================
try {
    switch ($module) {
        
        // ============ HOME ============
        case '':
        case 'home':
            require_once BASE_PATH . '/views/HomeView.php';
            break;

        // ============ AUTH ============
        case 'auth':
            require_once BASE_PATH . '/controllers/AuthController.php';
            $controller = new AuthController();
            
            switch ($action) {
                case 'auth':
                case 'login':
                    $controller->login();
                    break;
                case 'authenticate':
                    $controller->authenticate();
                    break;
                case 'logout':
                    $controller->logout();
                    break;
                default:
                    require_once BASE_PATH . '/views/404View.php';
            }
            break;

        // ============ GUEST ============
        case 'guest':
            require_once BASE_PATH . '/controllers/GuestController.php';
            $controller = new GuestController();
            
            switch ($action) {
                case '':
                case 'index':
                    $controller->index();
                    break;
                case 'readMyHistory':
                    $controller->readMyHistory();
                    break;
                case 'readMyFacturas':
                    $controller->readMyFacturas();
                    break;
                case 'facturas':
                    $controller->facturas();
                    break;
                case 'pagarFactura':
                    $controller->pagarFactura();
                    break;
                default:
                    require_once BASE_PATH . '/views/404View.php';
            }
            break;

        // ============ CASHIER ============
        case 'cashier':
            require_once BASE_PATH . '/controllers/CashierController.php';
            $controller = new CashierController();
            
            switch ($action) {
                case '':
                case 'index':
                    $controller->index();
                    break;
                case 'createFacturaView':
                    $controller->createFacturaView();
                    break;
                case 'buscarCliente':
                    $controller->buscarCliente();
                    break;
                case 'createFactura':
                    $controller->createFactura();
                    break;
                default:
                    require_once BASE_PATH . '/views/404View.php';
            }
            break;

        // ============ ADMIN ============
        case 'admin':
            require_once BASE_PATH . '/controllers/AdminController.php';
            $controller = new AdminController();
            
            switch ($action) {
                case '':
                case 'index':
                    $controller->index();
                    break;
                default:
                    require_once BASE_PATH . '/views/404View.php';
            }
            break;

        // ============ 404 ============
        default:
            http_response_code(404);
            require_once BASE_PATH . '/views/404View.php';
            break;
    }

} catch (Exception $e) {
    // Log del error
    error_log("Error en router: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    // En producción, mostrar página genérica
    http_response_code(500);
    
    if (isset($isProduction) && $isProduction) {
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Error - VillanBank</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                h1 { color: #dc3545; }
            </style>
        </head>
        <body>
            <h1>Error del Servidor</h1>
            <p>Lo sentimos, ha ocurrido un error interno.</p>
            <p><a href="/">Volver al inicio</a></p>
        </body>
        </html>';
    } else {
        // En desarrollo, mostrar el error completo
        echo '<h1>Error</h1>';
        echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    }
}