<?php
session_start();
include '../database/connection.php';

if (!isset($_SESSION['user'])) {
    echo "User not logged in";
    exit;
}

function log_action($conn, $user_id, $action, $details) {
    $stmt = $conn->prepare("INSERT INTO audit_logs (user_id, action, details) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $action, $details]);
}

if (isset($_POST['user_id']) && isset($_POST['action']) && isset($_POST['details'])) {
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];
    $details = $_POST['details']; 

    log_action($conn, $user_id, $action, $details);
}
?>
