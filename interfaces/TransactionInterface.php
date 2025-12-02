<?php
interface TransactionInterface
{
    // Para procesar el movimiento (Cajero)
    public function processTransaction();
}