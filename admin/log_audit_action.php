<?php
session_start();
include '../database/connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user'])) {
    echo "User not logged in";
    exit;
}

// Function to log action
function log_action($conn, $user_id, $action, $details = null) {
    $stmt = $conn->prepare("INSERT INTO audit_logs (user_id, action, details) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $action, $details]);
}

// Capture and log the action
if (isset($_POST['user_id']) && isset($_POST['action'])) {
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];
    
    // Optionally, pass details if needed (e.g., faculty name or category)
    $details = isset($_POST['details']) ? $_POST['details'] : null;
    
    // Log the action
    log_action($conn, $user_id, $action, $details);
}
?>
