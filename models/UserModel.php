<?php
require_once __DIR__ . '/../db/Conexiondb.php';
require_once __DIR__ . '/../entitys/UserEntity.php';

class UserModel extends Conexiondb
{
    /**
     * Guarda un nuevo usuario (Registro)
     */
    public function saveUser(UserEntity $user)
    {
        try {
            $this->conectardb();
            $plainPassword = $user->getPassword();

            $sql = "INSERT INTO tbl_users (pk_user, fk_phone, fk_level, password, locked) 
                    VALUES (?, ?, ?, ?, ?)";
            $data = $this->pdo->prepare($sql);

            $data->bindValue(1, $user->getPkUser(), PDO::PARAM_STR);
            $data->bindValue(2, $user->getPkPerson(), PDO::PARAM_STR);
            $data->bindValue(3, $user->getFkLevelUser(), PDO::PARAM_INT);
            $data->bindValue(4, $plainPassword, PDO::PARAM_STR);
            $data->bindValue(5, $user->getLocked(), PDO::PARAM_INT);

            $status = $data->execute();
            $this->desconectardb();
            return $status;
        } catch (PDOException $e) {
            error_log("Error saveUser: " . $e->getMessage());
            $this->desconectardb();
            throw $e;
        }
    }

    /**
     * Valida credenciales (Login)
     */
    public function login(UserEntity $userEntity)
    {
        try {
            $this->conectardb();
            $sql = "SELECT u.password, u.fk_level, l.level, p.person 
                    FROM tbl_users u
                    LEFT JOIN cat_levels l ON u.fk_level = l.pk_level
                    LEFT JOIN tbl_persons p ON u.fk_phone = p.pk_phone 
                    WHERE u.fk_phone = ?";
            $data = $this->pdo->prepare($sql);
            $data->bindValue(1, $userEntity->getFkPerson());
            $data->execute();
            $result = $data->fetch(PDO::FETCH_ASSOC);
            $this->desconectardb();

            if ($result) {
                // Comparación simple (sin hash, como pediste)
                if ($userEntity->getPassword() == $result['password']) {
                    return [
                        'id' => $result['fk_level'],
                        'levelName' => $result['level'] ?? 'Usuario',
                        'personName' => $result['person'] ?? 'Usuario'
                    ];
                } else { return false; }
            } else { return false; }
        } catch (PDOException $e) {
            $this->desconectardb();
            return false;
        }
    }

    /**
     * Obtiene todos los usuarios para la DataTable
     */
    public function read()
    {
        try {
            $this->conectardb();
            $sql = "SELECT 
                        tbl_users.pk_user, 
                        tbl_persons.pk_phone, 
                        tbl_persons.person, 
                        tbl_persons.first_name, 
                        tbl_persons.last_name, 
                        cat_levels.level 
                    FROM tbl_users 
                    INNER JOIN tbl_persons ON tbl_users.fk_phone = tbl_persons.pk_phone 
                    INNER JOIN cat_levels ON tbl_users.fk_level = cat_levels.pk_level";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->desconectardb();
            return $results;
        } catch (PDOException $e) {
            $this->desconectardb();
            return [];
        }
    }

    /**
     * Actualiza el nivel de usuario
     */
    public function updateLevel($pkUser, $pkLevel)
    {
        try {
            $this->conectardb();
            $sql = "UPDATE tbl_users SET fk_level = ? WHERE pk_user = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $pkLevel, PDO::PARAM_INT);
            $stmt->bindValue(2, $pkUser, PDO::PARAM_STR);
            $success = $stmt->execute();
            $this->desconectardb();
            return $success;
        } catch (PDOException $e) {
            $this->desconectardb();
            return false;
        }
    }

    /**
     * Invierte el estado 'locked' (0 <-> 1)
     */
    public function toggleLockStatus($pkUser)
    {
        try {
            $this->conectardb();
            $sql = "UPDATE tbl_users SET locked = (1 - locked) WHERE pk_user = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $pkUser, PDO::PARAM_STR);
            $success = $stmt->execute();
            $this->desconectardb();
            return $success;
        } catch (PDOException $e) {
            $this->desconectardb();
            return false;
        }
    }

    /**
     * Elimina usuario y persona asociada
     */
    public function deleteUser($pkUser)
    {
        try {
            $this->conectardb();
            // Obtenemos el teléfono antes de borrar
            $sqlGet = "SELECT fk_phone FROM tbl_users WHERE pk_user = ?";
            $stmt = $this->pdo->prepare($sqlGet);
            $stmt->bindValue(1, $pkUser);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) { $this->desconectardb(); return false; }
            $fkPhone = $result['fk_phone'];

            // Borramos Usuario
            $sqlUser = "DELETE FROM tbl_users WHERE pk_user = ?";
            $stmtUser = $this->pdo->prepare($sqlUser);
            $stmtUser->bindValue(1, $pkUser);
            $stmtUser->execute();

            // Borramos Persona
            $sqlPerson = "DELETE FROM tbl_persons WHERE pk_phone = ?";
            $stmtPerson = $this->pdo->prepare($sqlPerson);
            $stmtPerson->bindValue(1, $fkPhone);
            $success = $stmtPerson->execute();

            $this->desconectardb();
            return $success;
        } catch (PDOException $e) {
            $this->desconectardb();
            return false;
        }
    }

    /**
     * Actualiza datos personales (y maneja cambio de teléfono/pk_user)
     */
    public function updatePersonData($originalPhone, $newPhone, $person, $firstName, $lastName)
    {
        try {
            $this->conectardb();

            // 1. Actualizamos la tabla persona (y el PK si cambió)
            // Al cambiar PK aquí, 'tbl_users' se actualiza solo por el ON UPDATE CASCADE en SQL
            $sql = "UPDATE tbl_persons SET pk_phone = ?, person = ?, first_name = ?, last_name = ? WHERE pk_phone = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $newPhone, PDO::PARAM_STR);
            $stmt->bindValue(2, $person, PDO::PARAM_STR);
            $stmt->bindValue(3, $firstName, PDO::PARAM_STR);
            $stmt->bindValue(4, $lastName, PDO::PARAM_STR);
            $stmt->bindValue(5, $originalPhone, PDO::PARAM_STR);
            $success = $stmt->execute();

            // 2. Si el teléfono cambió, debemos recalcular el 'pk_user' (últimos 4 dígitos)
            if ($success && $originalPhone !== $newPhone) {
                $newPkUser = substr($newPhone, -4);

                $sqlUser = "UPDATE tbl_users SET pk_user = ? WHERE fk_phone = ?";
                $stmtUser = $this->pdo->prepare($sqlUser);
                $stmtUser->bindValue(1, $newPkUser, PDO::PARAM_STR);
                $stmtUser->bindValue(2, $newPhone, PDO::PARAM_STR);
                $stmtUser->execute();
            }

            $this->desconectardb();
            return $success;
        } catch (PDOException $e) {
            error_log("Error updatePersonData: " . $e->getMessage());
            $this->desconectardb();
            return false;
        }
    }
}