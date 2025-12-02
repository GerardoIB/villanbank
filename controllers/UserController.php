<?php
// controllers/UserController.php

// Muestra errores (solo para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Carga de archivos (solo los que necesite en el FUTURO)
require_once __DIR__ . '/../utils/View.php';
// Ya no implementa UserInterface

class UserController
{
    // (Esta clase está vacía por ahora)
    //
    // En el futuro, aquí pondrías métodos como:
    //
    // public function profile() {
    //    // Lógica para mostrar el perfil del usuario
    //    // URL: /pagina/user/profile
    // }
    //
    // public function updateProfile() {
    //    // Lógica para actualizar el perfil
    //    // URL: /pagina/user/updateProfile
    // }
}