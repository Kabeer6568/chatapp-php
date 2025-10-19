<?php

require_once "config/db.php";



class User{

    private $pdo;

    public function __construct(){

    $db = new Database;
    $this->pdo = $db->pdo;

    }

}