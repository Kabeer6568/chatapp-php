<?php

session_start();

require_once "classes/chat.php";

if (!isset($_SESSION['id'])) exit;

$chat = new Chat;

$sender_id = $_SESSION['id'];
$receiver_id = $_GET['receiver_id'] ?? null;
$messages = trim($_POST['message'] ?? null);



if ($receiver_id) {
    $chat->sendMessage($sender_id, $receiver_id, $message);

    echo json_encode(['status' => 'sucess']);
}
else{
    echo json_encode(['status' => 'sucess']);
}