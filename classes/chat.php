<?php


require_once __DIR__ . "/../config/db.php";

class Chat extends Database {

    public function fetchUsers(){

        $currentUser = $_SESSION["uid"];

        $sql = "SELECT id , username , status , profile_pic FROM users WHERE id != ?";

        $stat = $this->pdo->prepare($sql);

        $stat->execute([$currentUser]);

        $row = $stat->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }

    public function getMessages($sender_id , $receiver_id){

        $sql = "SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) 
        ORDER BY created_at ASC";

        $stat = $this->pdo->prepare($sql);

        $stat->execute([$sender_id , $receiver_id , $receiver_id , $sender_id]);

        $res = $stat->fetchAll(PDO::FETCH_ASSOC);

        return $res;

    }

    public function sendMessage($sender_id , $receiver_id , $message){

        $sql = "INSERT INTO messages (sender_id , receiver_id , message) VALUES (? , ? , ?)";

        $stat = $this->pdo->prepare($sql);
        
        $res = $stat->execute([$sender_id , $receiver_id , $message]);

        return $res;

    }

}