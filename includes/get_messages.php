<?php

session_start();

require_once "../classes/chat.php";

if (!isset($_SESSION['uid'])) {
    echo json_encode([]);
    exit;
}

$chat = new Chat;

$sender_id = $_SESSION['uid'];
$receiver_id = $_GET['receiver_id'] ?? null;

if ($receiver_id) {
    $messages = $chat->getMessages($sender_id, $receiver_id);
    header('Content-Type: application/json');
    echo json_encode($messages);
} else {
    header('Content-Type: application/json');
    echo json_encode([]);
}