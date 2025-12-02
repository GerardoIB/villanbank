<?php
// entitys/UserEntity.php

require_once __DIR__ . '/PersonEntity.php';

// UserEntity 'hereda' todas las propiedades de PersonEntity
class UserEntity extends PersonEntity
{
    // Propiedades específicas de UserEntity
    private $pkUser;      // El ID de usuario (ej: "3064")
    private $password;
    private $fkLevelUser;
    private $fkPerson;    // El teléfono completo
    private $locked;      // El campo 'locked' (0 o 1)

    /**
     * Método para 'llenar' la entidad con datos durante el REGISTRO
     * (Ahora con 10 parámetros)
     */
    public function __register($pkUser, $pkPerson, $person, $fistName, $lastName, $gender, $birthday, $password, $levelUser, $locked)
    {
        // 1. Llama al método de la clase padre (PersonEntity)
        parent::__personEntity($pkPerson, $person, $fistName, $lastName, $gender, $birthday);

        // 2. Guarda los datos específicos del usuario
        $this->pkUser = $pkUser;
        $this->password = $password;
        $this->fkLevelUser = $levelUser;
        $this->locked = $locked;
        // Nota: fkPerson se guarda en __login, pero pkPerson (teléfono) está en el padre
    }

    /**
     * Método para 'llenar' la entidad con datos durante el LOGIN
     */
    public function __login( $pkPerson, $password ){
        $this->password = $password;
        $this->fkPerson = $pkPerson; // (fkPerson aquí es el teléfono)
    }

    // --- Getters y Setters ---

    // pkUser
    public function getPkUser() { return $this->pkUser; }
    public function setPkUser($pkUser): void { $this->pkUser = $pkUser; }

    // fkPerson (Teléfono para el login)
    public function getFkPerson() { return $this->fkPerson; }
    public function setFkPerson($fkPerson) { $this->fkPerson = $fkPerson; }

    // password
    public function getPassword() { return $this->password; }
    public function setPassword($password) { $this->password = $password; }

    // fkLevelUser (ID del rol)
    public function getFkLevelUser() { return $this->fkLevelUser; }
    public function setFkLevelUser($fkLevelUser) { $this->fkLevelUser = $fkLevelUser; }

    // locked
    public function getLocked() { return $this->locked; }
    public function setLocked($locked): void { $this->locked = $locked; }
}