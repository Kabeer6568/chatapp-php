<?php

class Database{

    public $pdo;
    private $host = "localhost";
    private $username = "root";
    private $pass = "";

    public function __construct(){
        try {
        $this->pdo = new PDO("mysql:host = $this->host ", "$this->username" , "$this->pass");

        
            if ($this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION)) {
                $this->createDB();
            }
    } catch (PDOException $e) {
        echo "DB Failed: " . $e->getMessage();
    }

    
}

    public function createDB(){

        $createDB_query = "CREATE DATABASE IF NOT EXISTS chatapp";
        $createDB = $this->pdo->query($createDB_query);

        try {
            if ($createDB == FALSE) {
                throw new Exception("DB Creation Failed: " . $this->pdo->error);
            }
            else{
                $this->createUserTable();
                $this->createMsgTable();
            }
        } catch (Throwable $th) {
            echo "Error: " . $th -> getMessage();
        }

    }

    public function createUserTable(){

        $useDB = "USE chatapp";
        $this->pdo->query($useDB);

        $createTable_query = "CREATE TABLE IF NOT EXISTS users(
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        useremail VARCHAR(255) NOT NULL,
        userpass VARCHAR(255) NOT NULL,
        profile_pic VARCHAR(255) NOT NULL,
        status ENUM('online','offline','busy','away') NOT NULL DEFAULT 'offline',
        last_seen DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        $res = $this->pdo->query($createTable_query);

        if ($res == FALSE) {
            echo "Error Creating Table";
        }

    }
    public function createMsgTable(){

        $useDB = "USE chatapp";
        $this->pdo->query($useDB);

        $createTable_query = "CREATE TABLE IF NOT EXISTS messages(
        id INT AUTO_INCREMENT PRIMARY KEY,
        sender_id INT NOT NULL,
        receiver_id INT NOT NULL,
        message TEXT NOT NULL,
        status ENUM('sent','delivered','seen') DEFAULT 'sent',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
        )";

        $res = $this->pdo->query($createTable_query);

        if ($res == FALSE) {
            echo "Error Creating Table";
        }

    }
}
