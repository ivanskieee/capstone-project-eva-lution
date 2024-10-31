<?php
session_start();

include 'includes/header.php';
include 'includes/footer.php';
include 'database/connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        $stmt = $conn->prepare("SELECT * FROM college_faculty_list WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
    }

    if (!$user) {
        $stmt = $conn->prepare("SELECT * FROM student_list WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
    }

    if (!$user) {
        $stmt = $conn->prepare("SELECT * FROM head_faculty_list WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
    }

    if (!$user) {
        $stmt = $conn->prepare("SELECT * FROM secondary_faculty_list WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
    }

    if ($user) {
        $token = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime('+24 hours'));

        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$email, $token, $expiry]);

        $resetLink = "http://localhost/Capstone-Eva-lution/reset_password.php?token=$token";
        $subject = "Password Reset Request";
        $message = "Click on this link to reset your password: <a href='$resetLink'>$resetLink</a>";

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'evaluationspc@gmail.com'; 
            $mail->Password   = 'zjwz wnqx oyew nwst'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('your-email@gmail.com', 'SPC Evaluation'); 
            $mail->addAddress($email); 

            $mail->isHTML(true); 
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
            $_SESSION['flash_messages']['success'][] = "A password reset link has been sent to your email.";
        } catch (Exception $e) {
            $_SESSION['flash_messages']['danger'][] = "Failed to send the email. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $_SESSION['flash_messages']['danger'][] = "No account found with that email.";
    }
}
?>
