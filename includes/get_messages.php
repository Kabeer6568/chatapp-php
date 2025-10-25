<?php

session_start();

require_once "classes/chat.php";

if (!isset($_SESSION['id'])) exit;

$chat = new Chat;

$sender_id = $_SESSION['id'];
$receiver_id = $_GET['receiver_id'] ?? null;

if ($receiver_id) {
    $messages = $chat->getMessages($sender_id , $receiver_id);
    echo json_encode($messages);
}