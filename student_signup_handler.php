<?php
session_start();
ob_start();

include 'includes/header.php';
include 'includes/footer.php';
include 'database/connection.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $current_time = time();

    $query = "SELECT * FROM password_resets WHERE token = :token AND expires_at > :current_time";
    $stmt = $conn->prepare($query);

    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':current_time', $current_time, PDO::PARAM_INT);

    $stmt->execute();

}
?>