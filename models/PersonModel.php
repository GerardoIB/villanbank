<?php
// Carga el archivo de conexión base (Conexiondb.php)
require_once __DIR__ . '/../db/Conexiondb.php';
// Carga la 'caja' de datos (UserEntity) porque contiene los datos de la persona
require_once __DIR__ . '/../entitys/UserEntity.php';

// La clase PersonModel 'hereda' los métodos de Conexiondb (conectardb(), etc.)
class PersonModel extends Conexiondb
{
    /**
     * Guarda los datos de la persona (de la UserEntity) en la tabla tbl_persons.
     * Recibe la 'caja' (Entidad) completa del usuario.
     */
    public function savePerson(UserEntity $user)
    {
        try {
            $this->conectardb();

            // --- CONSULTA SQL MODIFICADA ---
            // (Las columnas no cambian, pero los datos que insertamos sí)
            $sql = "INSERT INTO tbl_persons (pk_phone, person, first_name, last_name, gender, birthday) 
                    VALUES (?, ?, ?, ?, ?, ?)";

            $data = $this->pdo->prepare($sql);

            // --- BINDINGS MODIFICADOS ---
            // (Usamos los getters que ahora tienen los datos correctos)
            $data->bindValue(1, $user->getPkPerson(), PDO::PARAM_STR); // Teléfono
            $data->bindValue(2, $user->getPerson(), PDO::PARAM_STR);   // "JUAN"
            $data->bindValue(3, $user->getFistName(), PDO::PARAM_STR); // "GARCIA"
            $data->bindValue(4, $user->getLastName(), PDO::PARAM_STR);  // "VILLAFUERTE"
            $data->bindValue(5, $user->getGender(), PDO::PARAM_STR);
            $data->bindValue(6, $user->getBirthday(), PDO::PARAM_STR);
            // --- FIN MODIFICACIÓN ---

            $status = $data->execute();

            $this->desconectardb();
            return $status;

        } catch (PDOException $e) {
            error_log("Error en PersonModel::savePerson(): " . $e->getMessage());
            $this->desconectardb();
            throw $e;
        }
    }
}