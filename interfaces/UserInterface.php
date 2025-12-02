<?php

/**
 * Define una Interfaz (un 'contrato' o 'plantilla') para los controladores de Usuario.
 * Cualquier clase que 'implemente' UserInterface ESTÁ OBLIGADA a tener estos métodos.
 */
interface UserInterface
{
    // Obliga a la clase a tener un método público 'auth' (para mostrar el login)
    public function auth();

    // Obliga a la clase a tener un método público 'login' (para procesar el login)
    public function login();

    // Obliga a la clase a tener un método público 'forget' (para 'olvidé contraseña')
    public function forget();
}