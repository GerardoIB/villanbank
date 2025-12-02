<?php
// Define la clase 'LevelEntity' (un contenedor de datos para un Nivel de usuario)
class LevelEntity
{
    // Propiedad para guardar el ID (Primary Key) del nivel
    private $pkLevel;
    // Propiedad para guardar el nombre del nivel (ej: 'Admin', 'Guest')
    private $level;

    // Constructor: Se ejecuta al crear una nueva instancia (ej: new LevelEntity(1, 'Admin'))
    public function __construct($pkLevel, $level){
        // Asigna el ID recibido a la propiedad interna '$pkLevel'
        $this->pkLevel = $pkLevel;
        // Asigna el nombre recibido a la propiedad interna '$level'
        $this->level = $level;
    }

    // Getter: Devuelve el ID del nivel
    public function getPkLevel()
    {
        return $this->pkLevel;
    }

    // Setter: Establece (cambia) el ID del nivel
    public function setPkLevel($pkLevel): void
    {
        $this->pkLevel = $pkLevel;
    }

    // Getter: Devuelve el nombre del nivel
    public function getLevel()
    {
        return $this->level;
    }

    // Setter: Establece (cambia) el nombre del nivel
    public function setLevel($level): void
    {
        $this->level = $level;
    }
}