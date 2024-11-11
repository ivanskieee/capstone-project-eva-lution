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

// Generate token and store it in the session
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = bin2hex(random_bytes(16)); // Generate a new token
    $expiry = time() + 3600; // Set the expiry time for the token

    // Store the token and expiry time in the session
    $_SESSION['generated_token'] = $token;

    // Insert token into the database (assuming you want to keep track of it)
    $stmt = $conn->prepare("INSERT INTO password_resets (token, expires_at) VALUES (?, ?)");
    $stmt->execute([$token, $expiry]);

    // Redirect to avoid resubmission on refresh
    header('Location: admin_generate_link.php');
    exit;
}

ob_end_flush();
?>
