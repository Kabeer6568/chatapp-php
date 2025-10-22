<?php

require_once "config/db.php";



class User{

    private $pdo;
    public $route = "http://localhost/chat_app/" ;

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

            $res = $stat->execute([$username , $useremail , $hashPass , $target_file]);

            header("location:  {$this->route}chatroom.php");
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

    public function userLogin($userInput , $userpass){

        $sql = "SELECT * FROM users WHERE username = ? OR useremail = ?";
        $stat =  $this->pdo->prepare($sql);

        $stat->execute([$userInput , $userInput]);

        $row = $stat->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            
            $storedPass = $row['userpass'];

            if (password_verify($userpass , $storedPass)) {
                
                $_SESSION['uid'] = $row['id'];
                $_SESSION['username'] = $row['username'];

                header("location:  {$this->route}chatroom.php");

            }
            else{
                echo 
                "<script>
                    alert(' Incorrect Password');
                </script>";
            }
        }
        else{
                echo 
                "<script>
                    alert(' Incorrect User Name Or Email');
                </script>";
            }
    }

    public function sessionCheck(){

        if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

        $currentPage = basename($_SERVER['PHP_SELF']);

        if (empty($_SESSION['uid']) ) {


            if ($currentPage != 'index.php' && $currentPage != 'signup.php') {
                
                header("location: {$this->route}");
                exit;
            }
            
        }
        else{
            if ($currentPage == 'index.php' || $currentPage == 'signup.php') {
                
                header("location: {$this->route}chatroom.php");
                exit;
            }
        }
    }


}