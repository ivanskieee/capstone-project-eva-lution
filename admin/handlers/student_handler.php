<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

include 'header.php';
include 'sidebar.php';
include 'footer.php';
include '../database/connection.php';

$id = isset($_POST['id']) ? $_POST['id'] : null;
$student = null;

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
    $stmt = $conn->prepare('SELECT * FROM student_list WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $student = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['delete_id'])) {
    $school_id = $_POST['school_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $class_id = $_POST['class_id'];
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
        $query = "UPDATE student_list SET school_id = :school_id, firstname = :firstname, lastname = :lastname, class_id = :class_id, email = :email, password = :password, avatar = :avatar WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':school_id', $school_id);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':class_id', $class_id);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':avatar', $avatar);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    } else {
        $query = "INSERT INTO student_list (school_id, firstname, lastname, class_id, email, password, avatar) VALUES (:school_id, :firstname, :lastname, :class_id, :email, :password, :avatar)";
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
        echo "<script>window.location.replace('student_list.php');</script>";
    } else {
        echo "<script>alert('Error saving data.');</script>";
    }

    $conn->close();
}

if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $stmt = $conn->prepare('DELETE FROM student_list WHERE student_id = :id');
    $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Student deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting student.');</script>";
    }

    echo "<script>window.location.replace('student_list.php');</script>";
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

?>