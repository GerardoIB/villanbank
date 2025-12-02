<?php
class TransactionEntity
{
    private $pkTransaction; // Será String
    private $fkAccount;
    private $type;
    private $amount;
    private $description;

    public function __construct(){}

    public function getPkTransaction(){ return $this->pkTransaction; }
    public function setPkTransaction($pkTransaction){ $this->pkTransaction = $pkTransaction; }

    public function getFkAccount(){ return $this->fkAccount; }
    public function setFkAccount($fkAccount){ $this->fkAccount = $fkAccount; }

    public function getType(){ return $this->type; }
    public function setType($type){ $this->type = $type; }

    public function getAmount(){ return $this->amount; }
    public function setAmount($amount){ $this->amount = $amount; }

    public function getDescription(){ return $this->description; }
    public function setDescription($description){ $this->description = $description; }
}