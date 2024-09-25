<?php
// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Ensure PHPMailer is autoloaded using Composer

include 'header.php';
include 'sidebar.php';
include 'footer.php';
include '../database/connection.php';

// Retrieve the faculty ID if provided
$id = isset($_POST['id']) ? $_POST['id'] : null;
$faculty = null;

// Fetch all faculty records
$stmt = $conn->prepare('SELECT * FROM college_faculty_list');
$stmt->execute();
$tertiary_faculties = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($id) {
    $stmt = $conn->prepare('SELECT * FROM college_faculty_list WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $faculty = $stmt->fetch();
}

// Handle form submission for adding or updating faculty
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['delete_id'])) {
    $school_id = $_POST['school_id'];
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

    // Hash the password if it's provided
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    }

    // File upload handling for avatar
    if ($avatar) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["img"]["name"]);
        move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
    }

    if ($id) {
        // Update faculty record
        $query = "UPDATE college_faculty_list SET school_id = :school_id, firstname = :firstname, lastname = :lastname, email = :email, password = :password, avatar = :avatar WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':school_id', $school_id);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':avatar', $avatar);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    } else {
        // Insert new faculty record
        $query = "INSERT INTO college_faculty_list (school_id, firstname, lastname, email, password, avatar) VALUES (:school_id, :firstname, :lastname, :email, :password, :avatar)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':school_id', $school_id);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':avatar', $avatar);
    }

    if ($stmt->execute()) {
        // After saving data, send an email
        sendEmail($email, $password);
        echo "<script>window.location.replace('tertiary_faculty_list.php');</script>";
    } else {
        echo "<script>alert('Error saving data.');</script>";
    }

    $conn = null; // Close the connection
}

// Handle faculty deletion
// Handle faculty deletion
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Prepare and execute the delete statement
    $stmt = $conn->prepare('DELETE FROM college_faculty_list WHERE faculty_id = :id');
    $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Faculty deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting faculty.');</script>";
    }

    echo "<script>window.location.replace('tertiary_faculty_list.php');</script>";
}


$conn = null; // Close the connection



// Function to send email using PHPMailer
function sendEmail($toEmail, $plainPassword)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = 0;                                       // Disable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host = 'smtp.gmail.com';                       // Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   // Enable SMTP authentication
        $mail->Username = 'evaluationspc@gmail.com';                 // SMTP username (your Gmail address)
        $mail->Password = 'ctet pnsr jirf ohpl';                    // SMTP password (use App Password if 2FA is enabled)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption
        $mail->Port = 587;                                    // TCP port to connect to

        // Recipients
        $mail->setFrom('your_email@gmail.com', 'Your Name');
        $mail->addAddress($toEmail);                                // Add recipient

        // Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = 'Account Created';
        $mail->Body = "Dear Faculty,<br>Your account has been created successfully.<br><b>Email:</b> $toEmail<br><b>Password:</b> $plainPassword<br><br>Thank you!";
        $mail->AltBody = "Dear Faculty,\nYour account has been created successfully.\nEmail: $toEmail\nPassword: $plainPassword\n\nThank you!";

        $mail->send();
        echo 'Email has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

?>