<?php

// Define una clase 'abstracta' (sirve como molde base, no se usa directamente)
abstract class PersonEntity
{
    // --- Propiedades (protected = las clases hijas pueden usarlas) ---

    // ID de la persona (Primary Key, ej: el teléfono)
    protected $pkPerson;
    // Nombre completo (ej: 'Irving Davila')
    protected $person;
    // Nombre(s) (Mantengo el typo 'fistName' como en tu original)
    protected $fistName;
    // Apellido(s)
    protected $lastName;
    // Género (ej: 'M', 'F', 'O')
    protected $gender;
    // Fecha de nacimiento (ej: '2000-01-30')
    protected $birthday;

    /**
     * Método para 'llenar' la entidad con los datos de la persona.
     * (OJO: Se llama __personEntity, no __construct, no es un constructor real)
     */
    public function __personEntity($pkPerson, $person, $fistName, $lastName, $gender, $birthday)
    {
        $this->pkPerson = $pkPerson;
        $this->person = $person;     // "JUAN"
        $this->fistName = $fistName; // "GARCIA"
        $this->lastName = $lastName; // "VILLAFUERTE"
        $this->gender = $gender;
        $this->birthday = $birthday;
    }

    // --- Getters (Funciones para LEER las propiedades) ---
    public function getPkPerson() { return $this->pkPerson; }
    public function getPerson() { return $this->person; }
    public function getFistName() { return $this->fistName; }
    public function getLastName() { return $this->lastName; }
    public function getGender() { return $this->gender; }
    public function getBirthday() { return $this->birthday; }

    // --- Setters (FuncFunciones para CAMBIAR las propiedades) ---
    public function setPkPerson($pkPerson) { $this->pkPerson = $pkPerson; }
    public function setPerson($person) { $this->person = $person; }
    public function setFistName($fistName) { $this->fistName = $fistName; }
    public function setLastName($lastName) { $this->lastName = $lastName; }
    public function setGender($gender): void { $this->gender = $gender; }
    public function setBirthday($birthday): void { $this->birthday = $birthday; }
}