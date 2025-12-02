<?php
// Carga la utilidad para renderizar vistas (el HTML)
require_once __DIR__ . '/../utils/View.php';

class DashboardController
{
    /**
     * Muestra la página principal (dashboard)
     */
    public function index()
    {
        // Prepara un array con datos de ejemplo para la vista
        $dashboard = array(
            'name' => 'Irving',
            'lastname' => 'Davila',
            'age' => 100,
        );

        // Llama a la utilidad 'View' para mostrar el archivo HTML
        View::render("Users/dashboardView", ["dashboard" => $dashboard]);

        // Este 'echo' se ejecutará DESPUÉS de renderizar la vista
        echo 'Bienvenido a la pagina principal';
    }
}