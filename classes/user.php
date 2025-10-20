<?php

require_once "config/db.php";



class User{

    private $pdo;

    public function __construct(){

    $db = new Database;
    $this->pdo = $db->pdo;

    }

    public function userSignup($username , $useremail , $userpass , $profile_pic){

        if ($this->checkUser($useremail)) {
            echo "<script>alert('The Email {$useremail} already exists');</script>";
        }
        else{

        $target_dir = "uploads/";
        $file_name = uniqid() . "_" . basename($profile_pic['name']);
        $target_file = $target_dir . $file_name;

            if (move_uploaded_file($profile_pic['tmp_name'] , $target_file)) {
                $sql = "INSERT INTO users (username , useremail , userpass , profile_pic) VALUES 
            (? , ? , ? , ? )";

            $hashPass = password_hash($userpass , PASSWORD_BCRYPT); 

            $stat = $this->pdo->prepare($sql);

            $res = $stat->execute([$username , $useremail , $userpass , $target_file]);

            return $res;
        }
        else{
            echo "ERROR UPLOADING IMAGE";
        }
    }

    }

    public function checkUser($useremail){

        $sql = "SELECT * FROM users WHERE useremail = ?";
        $stat = $this->pdo->prepare($sql);

        $stat->execute([$useremail]);

        $res = $stat->rowCount() > 0;

        return $res;


    }



}