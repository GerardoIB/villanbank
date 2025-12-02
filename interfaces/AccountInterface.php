<?php
interface AccountInterface
{
    // Para leer los datos de las cuentas (Admin y Cajero)
    public function readAccounts();

    // Para cambiar el estado (Bloquear/Activar - Admin)
    public function updateStatus();
}