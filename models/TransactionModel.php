<?php
require_once __DIR__ . '/../db/Conexiondb.php';
require_once __DIR__ . '/../entitys/TransactionEntity.php';

class TransactionModel extends Conexiondb
{
    /**
     * Realiza un depósito o retiro.
     * 1. Genera el ID compuesto (Cuenta-Consecutivo).
     * 2. Inserta la transacción.
     * 3. Actualiza el saldo de la cuenta.
     */
    public function makeTransaction(TransactionEntity $tx)
    {
        try {
            $this->conectardb();

            $this->pdo->beginTransaction();

            $sqlCount = "SELECT COUNT(*) as total FROM tbl_transactions WHERE fk_account = ?";
            $stmtCount = $this->pdo->prepare($sqlCount);
            $stmtCount->bindValue(1, $tx->getFkAccount());
            $stmtCount->execute();
            $row = $stmtCount->fetch(PDO::FETCH_ASSOC);
            $nextSequence = $row['total'] + 1;


            $customId = $tx->getFkAccount() . '-' . $nextSequence;
            $tx->setPkTransaction($customId);

            if ($tx->getType() == 'retiro') {
                $sqlBalance = "SELECT balance FROM tbl_accounts WHERE pk_account = ?";
                $stmtBal = $this->pdo->prepare($sqlBalance);
                $stmtBal->bindValue(1, $tx->getFkAccount());
                $stmtBal->execute();
                $currentBal = $stmtBal->fetchColumn();

                if ($currentBal < $tx->getAmount()) {
                    $this->pdo->rollBack(); // Cancelamos todo
                    return ["status" => false, "message" => "Saldo insuficiente."];
                }
            }

            // INSERTAR TRANSACCIÓN
            $sqlInsert = "INSERT INTO tbl_transactions (pk_transaction, fk_account, type, amount, description) VALUES (?, ?, ?, ?, ?)";
            $stmtIns = $this->pdo->prepare($sqlInsert);
            $stmtIns->bindValue(1, $tx->getPkTransaction());
            $stmtIns->bindValue(2, $tx->getFkAccount());
            $stmtIns->bindValue(3, $tx->getType());
            $stmtIns->bindValue(4, $tx->getAmount());
            $stmtIns->bindValue(5, $tx->getDescription());
            $stmtIns->execute();

            //ACTUALIZAR SALDO EN TBL_ACCOUNTS
            if ($tx->getType() == 'deposito') {
                $sqlUpdate = "UPDATE tbl_accounts SET balance = balance + ? WHERE pk_account = ?";
            } else {
                $sqlUpdate = "UPDATE tbl_accounts SET balance = balance - ? WHERE pk_account = ?";
            }
            $stmtUpd = $this->pdo->prepare($sqlUpdate);
            $stmtUpd->bindValue(1, $tx->getAmount());
            $stmtUpd->bindValue(2, $tx->getFkAccount());
            $stmtUpd->execute();

            // B. CONFIRMAR TRANSACCIÓN SQL
            $this->pdo->commit();
            $this->desconectardb();

            return ["status" => true, "message" => "Transacción " . $customId . " exitosa."];

        } catch (Exception $e) {
            $this->pdo->rollBack(); // Si algo falla, deshacemos cambios
            $this->desconectardb();
            error_log("Error Transaction: " . $e->getMessage());
            return ["status" => false, "message" => "Error interno: " . $e->getMessage()];
        }
    }

    // Para el Cajero: Historial de Transacciones
    public function readAllHistory()
    {
        try {
            $this->conectardb();
            $sql = "SELECT 
                        t.pk_transaction,
                        t.fk_account,
                        t.type,
                        t.amount,
                        t.create_at,
                        p.person, 
                        p.first_name
                    FROM tbl_transactions t
                    INNER JOIN tbl_accounts a ON t.fk_account = a.pk_account
                    INNER JOIN tbl_users u ON a.fk_user = u.pk_user
                    INNER JOIN tbl_persons p ON u.fk_phone = p.pk_phone
                    ORDER BY t.create_at DESC"; // Lo más reciente primero

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->desconectardb();
            return $result;
        } catch (Exception $e) { return []; }
    }

    /**
     * Historial de una sola cuenta (Para el Cliente)
     */
    // models/TransactionModel.php

    public function getHistoryByAccount($pkAccount)
    {
        try {
            $this->conectardb();
            $sql = "SELECT pk_transaction, type, amount, description, create_at 
                    FROM tbl_transactions 
                    WHERE fk_account = ? 
                    ORDER BY create_at DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $pkAccount);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->desconectardb();
            return $result;
        } catch (Exception $e) {
            return [];
        }
    }
    public function getFacturas($pkPhone){
        try {
            $this->conectardb();
            $sql = " SELECT f.*             FROM facturas f             INNER JOIN tbl_accounts a ON f.fkAccount = a.pk_account             INNER JOIN tbl_users u ON a.fk_user = u.pk_user
INNER JOIN tbl_persons p ON u.fk_phone = p.pk_phone             WHERE p.pk_phone = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $pkPhone);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->desconectardb();
            return $result;
        } catch (Exception $e) {
            return [];
        }
    }
    public function payFactura($facturaId){
        try {
            $this->conectardb();
            $sql = "UPDATE facturas SET status = 'pagado' WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $facturaId);
            $stmt->execute();
            $this->desconectardb();
            return ["status" => true, "message" => "Factura pagada exitosamente."];
        } catch (Exception $e) {
            return ["status" => false, "message" => "Error al pagar la factura: " . $e->getMessage()];
        }
    }

    public function createFacturas($id,$fkAccount,$ammount){
        try {
            //code...
            $this->conectardb();
            $sql = "INSERT INTO facturas (id, fkAccount, ammount) VALUES (?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->bindValue(2, $fkAccount);
            $stmt->bindValue(3, $ammount);
            $result = $stmt->execute();
            $this->desconectardb();
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
            return false;
            
        }
    }
}