<?php
// Carga las constantes de la base de datos (DB_HOST, DB_NAME, DB_USER, DB_PASS)
include_once dirname(__FILE__) . '/keys.php';

// Clase abstracta: no se puede instanciar, solo heredar (ej: UserModel extiende Conexiondb)
abstract class Conexiondb
{
    // Almacena el objeto de conexión PDO. Es 'protected' para que los hijos la usen.
    protected $pdo;

    /**
     * Crea y retorna la conexión PDO a la base de datos
     */
    public function conectardb()
    {
        // Intenta establecer la conexión
        try
        {
            $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME;
            
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS);
            // Configura PDO para que lance excepciones (errores) graves
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Asegura que la conexión use la codificación UTF-8 (para 'ñ', 'á', etc.)
            $this->pdo->exec("SET NAMES 'utf8'");
            // Devuelve la conexión establecida
            return $this->pdo;
        }
            // Captura cualquier error que ocurra durante la conexión
        catch(PDOException $e)
        {
            // Registra el error en el log del servidor (no lo muestra al usuario)
            error_log("Error fatal de conexión a BD: " . $e->getMessage());
            // Re-lanza la excepción para que el 'catch' del UserController la maneje
            throw $e;
        }
    }

    /**
     * Cierra la conexión a la base de datos
     */
    public function desconectardb()
    {
        // Asigna 'null' al objeto PDO para cerrar la conexión
        $this->pdo = null;
    }

    /**
     * Método (redundante) para forzar la codificación UTF-8
     * (Ya se llama en conectardb())
     */
    public function setNames()
    {
        // Comprueba si la conexión ($this->pdo) existe
        if ($this->pdo) {
            // Ejecuta la consulta SET NAMES
            return $this->pdo->query("SET NAMES 'utf8'");
        }
    }
}
?>