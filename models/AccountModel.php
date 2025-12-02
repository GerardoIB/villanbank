<?php
require_once __DIR__ . '/../db/Conexiondb.php';

class AccountModel extends Conexiondb
{
    /**
     * Crea una cuenta inicial (Usado en el Registro)
     */
    public function createAccount($fkUser)
    {
        try {
            $this->conectardb();
            $sql = "INSERT INTO tbl_accounts (fk_user, state, balance) VALUES (?, 'active', 0.00)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $fkUser, PDO::PARAM_STR);
            $stmt->execute();
            $this->desconectardb();
            return true;
        } catch (PDOException $e) {
            error_log("Error creando cuenta: " . $e->getMessage());
            $this->desconectardb();
            return false;
        }
    }

    /**
     * ✅ ESTA ES LA FUNCIÓN QUE TE FALTA O ESTÁ FALLANDO
     * Obtiene todas las cuentas con los nombres de los dueños.
     */
    public function readForAdmin()
    {
        try {
            $this->conectardb();

            $sql = "SELECT 
                        a.pk_account, 
                        a.state, 
                        a.balance,
                        p.person,       -- Nombre
                        p.first_name,   -- Paterno
                        p.last_name     -- Materno
                    FROM tbl_accounts a
                    INNER JOIN tbl_users u ON a.fk_user = u.pk_user
                    INNER JOIN tbl_persons p ON u.fk_phone = p.pk_phone";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->desconectardb();
            return $result;

        } catch (Exception $e) {
            // Si hay error, devolvemos array vacío para no romper el JSON
            error_log("Error en readForAdmin: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Elimina una cuenta bancaria.
     * (Las transacciones se borran solas por la FK en Cascada)
     */
    public function deleteAccount($pkAccount)
    {
        try {
            $this->conectardb();
            $sql = "DELETE FROM tbl_accounts WHERE pk_account = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $pkAccount, PDO::PARAM_INT);
            $success = $stmt->execute();
            $this->desconectardb();
            return $success;
        } catch (PDOException $e) {
            error_log("Error deleteAccount: " . $e->getMessage());
            $this->desconectardb();
            return false;
        }
    }

    /**
     * Busca una cuenta específica y devuelve el nombre del titular.
     * Usado por el Cajero para validar antes de operar.
     */
    public function getAccountOwner($pkAccount)
    {
        try {
            $this->conectardb();

            $sql = "SELECT 
                        a.pk_account, 
                        a.state,
                        p.person, 
                        p.first_name, 
                        p.last_name
                    FROM tbl_accounts a
                    INNER JOIN tbl_users u ON a.fk_user = u.pk_user
                    INNER JOIN tbl_persons p ON u.fk_phone = p.pk_phone
                    WHERE a.pk_account = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $pkAccount, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->desconectardb();

            return $result; // Devuelve el array con datos o false si no existe

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Obtiene la cuenta y saldo de un usuario específico por su teléfono
     */
    // models/AccountModel.php

    /**
     * Obtiene la cuenta y saldo de un usuario específico por su teléfono
     */
    public function getAccountByPhone($phone)
    {
        try {
            $this->conectardb();
            // Buscamos la cuenta uniendo con la tabla de usuarios
            $sql = "SELECT a.pk_account, a.balance, a.state 
                    FROM tbl_accounts a
                    INNER JOIN tbl_users u ON a.fk_user = u.pk_user
                    WHERE u.fk_phone = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $phone);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->desconectardb();
            return $result;
        } catch (Exception $e) {
            $this->desconectardb();
            return false;
        }
    }
}