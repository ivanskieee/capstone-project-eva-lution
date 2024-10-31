<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: ../index.php');
    exit;
}

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: unauthorized.php');
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

include 'header.php';
include 'sidebar.php';
include 'footer.php';
include '../database/connection.php';

$id = isset($_GET['student_id']) ? $_GET['student_id'] : null;

$stmt = $conn->prepare("
    SELECT student_list.*, 
           CONCAT(class_list.course, ' ', class_list.level, ' - ', class_list.section) as class_name
    FROM student_list
    JOIN class_list ON student_list.class_id = class_list.class_id
");
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

$classes = [];
$stmt = $conn->query("SELECT class_id, concat(course, ' ', level, ' - ', section) as class_name FROM class_list");
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);



if ($id) {
    $stmt = $conn->prepare('SELECT * FROM student_list WHERE student_id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['delete_id'])) {
        $school_id = $_POST['school_id'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $class_id = $_POST['class_id'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $cpass = $_POST['cpass'];
        $avatar = isset($_FILES['img']['name']) ? $_FILES['img']['name'] : null;
        $id = $_POST['student_id'] ?? null; 

        if (!empty($password) && $password !== $cpass) {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Passwords do not match.',
                    });
                  </script>";
            return;
        }

        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($avatar) {
            $target_dir = "assets/uploads/";
            $target_file = $target_dir . basename($_FILES["img"]["name"]);
            move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
        }

        if ($id) {
            $query = "UPDATE student_list 
                      SET school_id = :school_id, firstname = :firstname, lastname = :lastname, class_id = :class_id, email = :email";

            if (!empty($password)) {
                $query .= ", password = :password";
            }

            if ($avatar) {
                $query .= ", avatar = :avatar";
            }

            $query .= " WHERE student_id = :student_id";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(':school_id', $school_id);
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':class_id', $class_id);
            $stmt->bindParam(':email', $email);

            if (!empty($password)) {
                $stmt->bindParam(':password', $hashed_password);
            }

            if ($avatar) {
                $stmt->bindParam(':avatar', $avatar);
            }

            $stmt->bindParam(':student_id', $id, PDO::PARAM_INT);
        } else {
            $query = "INSERT INTO student_list (school_id, firstname, lastname, class_id, email, password, avatar) 
                      VALUES (:school_id, :firstname, :lastname, :class_id, :email, :password, :avatar)";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(':school_id', $school_id);
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':class_id', $class_id);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':avatar', $avatar);
        }

        if ($stmt->execute()) {
            sendEmail($email, $password);

            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Student information saved successfully.',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.replace('student_list.php');
                    });
                  </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error saving data. Please try again.',
                    });
                  </script>";
        }

        $conn = null;
    }

    if (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];
    
        $stmt = $conn->prepare('DELETE FROM student_list WHERE student_id = :id');
        $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
    
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Student deleted successfully.';
        } else {
            error_log('Error deleting student');
            $_SESSION['error'] = 'Error deleting student. Please try again.';
        }
    
        echo "<script>window.location.replace('student_list.php');</script>";
    }    
}

function sendEmail($toEmail, $plainPassword) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->SMTPDebug = 0;                                       
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.gmail.com';                       
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'evaluationspc@gmail.com';                 
        $mail->Password   = 'zjwz wnqx oyew nwst';                    
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
        $mail->Port       = 587;                                    

        $mail->setFrom('your_email@gmail.com', 'SPC_EVAL');
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

?>