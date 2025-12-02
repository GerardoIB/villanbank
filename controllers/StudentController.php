<?php
// controllers/StudentController.php

// Carga la utilidad para renderizar vistas (el HTML)
require_once BASE_PATH . '/utils/View.php';

class StudentController
{
    /**
     * Muestra la página principal (dashboard) del estudiante
     */
    public function index()
    {
        // ✅ CAMBIO AQUÍ
        if (!isset($_SESSION['user_level_id']) || $_SESSION['user_level_id'] != 5) {
            header("Location: " . app_url . "/auth/auth");
            exit;
        }

        $dashboardData = ['name' => $_SESSION['user_person_name'] ?? 'Estudiante'];
        View::render("Student/IndexView", ["dashboard" => $dashboardData]);
    }
}
?>