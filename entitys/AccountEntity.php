<?php
class AccountEntity
{
    private $pkAccount;
    private $fkUser;
    private $state;
    private $balance;

    // Constructor simple o vacío
    public function __construct(){}

    // Getters y Setters
    public function getPkAccount(){ return $this->pkAccount; }
    public function setPkAccount($pkAccount){ $this->pkAccount = $pkAccount; }

    public function getFkUser(){ return $this->fkUser; }
    public function setFkUser($fkUser){ $this->fkUser = $fkUser; }

    public function getState(){ return $this->state; }
    public function setState($state){ $this->state = $state; }

    public function getBalance(){ return $this->balance; }
    public function setBalance($balance){ $this->balance = $balance; }
}