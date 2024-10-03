<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

include 'header.php';
include 'sidebar.php';
include 'footer.php';
include '../database/connection.php';

$stmt = $conn->prepare('SELECT * FROM users');
$stmt->execute();
$admin = $stmt->fetchAll(PDO::FETCH_ASSOC);

// if ($id) {
//     $stmt = $conn->prepare('SELECT * FROM users WHERE id = :id');
//     $stmt->bindParam(':id', $id, PDO::PARAM_INT);
//     $stmt->execute();
//     $stmt->setFetchMode(PDO::FETCH_ASSOC);
//     $student = $stmt->fetch();
// }

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['delete_id'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpass = $_POST['cpass'];
    $avatar = isset($_FILES['img']['name']) ? $_FILES['img']['name'] : null;

    if (!empty($password) && $password !== $cpass) {
        echo "<script>alert('Passwords do not match');</script>";
        return;
    }

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    }

    if ($avatar) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["img"]["name"]);
        move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
    }

    if ($id) {
        $query = "UPDATE users SET firstname = :firstname, lastname = :lastname , email = :email, password = :password, avatar = :avatar WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':avatar', $avatar);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    } else {
        $query = "INSERT INTO users (firstname, lastname, email, password, avatar) VALUES (:firstname, :lastname, :email, :password, :avatar)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':avatar', $avatar);
    }

    if ($stmt->execute()) {
        sendEmail($email, $password);
        echo "<script>window.location.replace('user_list.php');</script>";
    } else {
        echo "<script>alert('Error saving data.');</script>";
    }

    $conn->close();
}

if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $stmt = $conn->prepare('DELETE FROM users WHERE id = :id');
    $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Admin deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting admin.');</script>";
    }

    echo "<script>window.location.replace('user_list.php');</script>";
}

$conn = null;

function sendEmail($toEmail, $plainPassword) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->SMTPDebug = 0;                                       
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.gmail.com';                       
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'evaluationspc@gmail.com';                 
        $mail->Password   = 'ctet pnsr jirf ohpl';                    
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
        $mail->Port       = 587;                                    

        $mail->setFrom('your_email@gmail.com', 'Your Name');
        $mail->addAddress($toEmail);                                

        $mail->isHTML(true);                                        
        $mail->Subject = 'Account Created';
        $mail->Body    = "Dear Student,<br>Your account has been created successfully.<br><b>Email:</b> $toEmail<br><b>Password:</b> $plainPassword<br><br>Thank you!";
        $mail->AltBody = "Dear Student,\nYour account has been created successfully.\nEmail: $toEmail\nPassword: $plainPassword\n\nThank you!";

        $mail->send();
        echo 'Email has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
