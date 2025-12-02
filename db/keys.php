<?php
// Define el host o servidor de la base de datos (usualmente 'localhost')
define('DB_HOST', 'localhost');

define('DB_PORT', '3306');

// Define el nombre de usuario para la base de datos (usualmente 'root' en local)
define('DB_USER', 'root');

// Define la contraseña para ese usuario de la base de datos
define('DB_PASS', 'Jiqui50800%');

// Define el nombre de la base de datos a la que te quieres conectar
define('DB_NAME', 'villabank');

// --- DETECCIÓN INTELIGENTE DE PROTOCOLO ---

$host = $_SERVER['HTTP_HOST'];

// Si estamos en localhost o IP local, usamos HTTP simple
if ($host === 'localhost' || $host === '127.0.0.1' || strpos($host, '192.168.') === 0) {
    $protocol = 'http';
}
// Si estamos en el servidor real (irvingdevv.dev), forzamos HTTPS
else {
    $protocol = 'https';
}

define('app_url', $protocol . "://" . $host . "/pagina");
?>