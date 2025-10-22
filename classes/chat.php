<?php


require_once "config/db.php";

class Chat extends Database {

    public function fetchUsers(){

        $currentUser = $_SESSION["uid"];

        $sql = "SELECT id , username , status FROM users WHERE id != ?";

        $stat = $this->pdo->prepare($sql);

        $stat->execute([$currentUser]);

        $row = $stat->fetchAll(PDO::FETCH_ASSOC);

        return $row;

    }

}