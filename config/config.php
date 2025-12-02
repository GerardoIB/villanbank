<?php
// config/config.php - Configuración de Producción

// ========================================
// CONFIGURACIÓN DE ENTORNO
// ========================================

// Detectar si estamos en producción o desarrollo
$isProduction = isset($_SERVER['HTTP_HOST']) && 
                (strpos($_SERVER['HTTP_HOST'], 'render.com') !== false || 
                 strpos($_SERVER['HTTP_HOST'], 'villanbank') !== false);

// ========================================
// MODO DE DEPURACIÓN
// ========================================
if ($isProduction) {
    // Producción: Ocultar errores al usuario
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
    
    // Pero registrarlos en un archivo
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../logs/php-errors.log');
} else {
    // Desarrollo: Mostrar todos los errores
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// ========================================
// RUTAS BASE
// ========================================
define('BASE_PATH', __DIR__ . '/..');

// URL Base según entorno
if ($isProduction) {
    define('app_url', 'https://villanbank.onrender.com');
} else {
    define('app_url', 'http://localhost/pagina');
}

// ========================================
// CONFIGURACIÓN DE BASE DE DATOS
// ========================================

// Variables de entorno (Render las proporciona automáticamente)
$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbName = getenv('DB_NAME') ?: 'villabank';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASSWORD') ?: 'Jiqui50800%';
$dbPort = getenv('DB_PORT') ?: '3306';

define('DB_HOST', $dbHost);
define('DB_NAME', $dbName);
define('DB_USER', $dbUser);
define('DB_PASSWORD', $dbPass);
define('DB_PORT', $dbPort);

// ========================================
// FUNCIÓN DE CONEXIÓN A BD
// ========================================
function getConnection()
{
    try {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ];

        $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
        return $pdo;

    } catch (PDOException $e) {
        // En producción, loguear el error pero no mostrarlo
        error_log("Database Connection Error: " . $e->getMessage());
        
        if (!$isProduction) {
            die("Error de conexión: " . $e->getMessage());
        } else {
            die("Error de conexión a la base de datos. Por favor, contacte al administrador.");
        }
    }
}

// ========================================
// CONFIGURACIÓN DE SESIÓN
// ========================================
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 7200, // 2 horas
        'cookie_httponly' => true,
        'cookie_secure' => $isProduction, // Solo HTTPS en producción
        'use_strict_mode' => true
    ]);
}

// ========================================
// ZONA HORARIA
// ========================================
date_default_timezone_set('America/Mexico_City');

// ========================================
// AUTOLOADER (si no usas Composer)
// ========================================
spl_autoload_register(function ($class) {
    $directories = [
        BASE_PATH . '/models/',
        BASE_PATH . '/controllers/',
        BASE_PATH . '/utils/'
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// ========================================
// CONSTANTES ADICIONALES
// ========================================
define('SITE_NAME', 'VillanBank');
define('SITE_VERSION', '1.0.0');
define('MAINTENANCE_MODE', false);

// ========================================
// FUNCIÓN DE DEBUG SEGURA
// ========================================
function debug($data, $die = false)
{
    global $isProduction;
    
    if (!$isProduction) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        
        if ($die) {
            die();
        }
    } else {
        error_log(print_r($data, true));
    }
}

// ========================================
// VERIFICAR CONEXIÓN AL INICIAR
// ========================================
try {
    $testConnection = getConnection();
    if ($testConnection) {
        // Conexión exitosa
        if (!$isProduction) {
            error_log("✓ Conexión a BD establecida correctamente");
        }
    }
} catch (Exception $e) {
    error_log("✗ Error al conectar a BD: " . $e->getMessage());
    
    if (!$isProduction) {
        die("Error crítico: No se pudo conectar a la base de datos");
    }
}