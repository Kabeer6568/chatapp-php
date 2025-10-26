<?php

session_start();

require_once "../classes/chat.php";

if (!isset($_SESSION['uid'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$chat = new Chat;

$sender_id = $_SESSION['uid'];
$receiver_id = $_GET['receiver_id'] ?? null;
$message = trim($_POST['message'] ?? '');

if ($receiver_id && !empty($message)) {
    $chat->sendMessage($sender_id, $receiver_id, $message);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
} else {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Missing data']);
}