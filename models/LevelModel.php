<?php
require_once __DIR__ . '/../db/Conexiondb.php';
require_once __DIR__ . '/../entitys/LevelEntity.php';

class LevelModel extends Conexiondb
{

    public function save($table, LevelEntity $levelEntity){
        try {
            $this->conectardb();
            $sql = "INSERT INTO {$table} (pk_level, level) VALUES (?,?)";
            $data = $this->pdo->prepare($sql);
            $data->bindValue(1, $levelEntity->getPkLevel(), PDO::PARAM_INT);
            $data->bindValue(2, $levelEntity->getLevel(), PDO::PARAM_STR);
            $status = $data->execute();
            $this->desconectardb();
            return $status;
        } catch (PDOException $e) {
            error_log("Error LevelModel::save: " + $e->getMessage());
            return false;
        }
    }


    public function read()
    {
        try {
            $this->conectardb();

            $sql = "SELECT pk_level, level FROM cat_levels";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->desconectardb();
            return $results;

        } catch (PDOException $e) {
            error_log("Error en LevelModel::read(): " . $e->getMessage());
            $this->desconectardb();
            return [];
        }
    }


    public function updateLevel($pkLevel, $newLevelName)
    {
        try {
            $this->conectardb();
            $sql = "UPDATE cat_levels SET level = ? WHERE pk_level = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $newLevelName, PDO::PARAM_STR);
            $stmt->bindValue(2, $pkLevel, PDO::PARAM_INT);
            $success = $stmt->execute();
            $this->desconectardb();
            return $success;
        } catch (PDOException $e) {
            return false;
        }
    }


    public function deleteLevel($pkLevel)
    {
        try {
            $this->conectardb();
            $sql = "DELETE FROM cat_levels WHERE pk_level = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $pkLevel, PDO::PARAM_INT);
            $success = $stmt->execute();
            $this->desconectardb();
            return $success;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getNextId()
    {
        try {
            $this->conectardb();
            $sql = "SELECT MAX(pk_level) as max_id FROM cat_levels";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->desconectardb();
            return ($row['max_id']) ? $row['max_id'] + 1 : 1;

        } catch (PDOException $e) {
            return 1;
        }
    }
}
?>