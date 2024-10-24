<?php
session_start(); 

include 'includes/header.php';
include 'includes/footer.php';
include 'database/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $reset = $stmt->fetch();

    if ($reset) {
        $email = $reset['email'];

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$newPassword, $email]);

        if ($stmt->rowCount() == 0) {
            $stmt = $conn->prepare("UPDATE college_faculty_list SET password = ? WHERE email = ?");
            $stmt->execute([$newPassword, $email]);
        }

        if ($stmt->rowCount() == 0) {
            $stmt = $conn->prepare("UPDATE student_list SET password = ? WHERE email = ?");
            $stmt->execute([$newPassword, $email]);
        }

        if ($stmt->rowCount() > 0) {
            $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
            $stmt->execute([$token]);

            $_SESSION['flash_messages']['success'][] = "Your password has been reset successfully.";
            header("Location: ./"); 
            exit;
        } else {
            $_SESSION['flash_messages']['danger'][] = "Failed to reset the password. Please try again.";
        }
    } else {
        $_SESSION['flash_messages']['danger'][] = "Invalid or expired reset token.";
    }
}
?>