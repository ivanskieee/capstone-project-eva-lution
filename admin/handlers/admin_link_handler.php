<?php
session_start();
ob_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: unauthorized.php');
    exit;
}

include 'header.php';
include 'sidebar.php';
include 'footer.php';
include '../database/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = bin2hex(random_bytes(16)); 
    $expiry = date("Y-m-d H:i:s", strtotime('+24 hours'));

    $_SESSION['generated_token'] = $token;

    $stmt = $conn->prepare("INSERT INTO password_resets (token, expires_at) VALUES (?, ?)");
    $stmt->execute([$token, $expiry]);

    header('Location: admin_generate_link.php');
    exit;
}

ob_end_flush();
?>
