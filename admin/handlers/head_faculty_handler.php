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

$id = isset($_GET['head_id']) ? $_GET['head_id'] : null;


$stmt = $conn->prepare('SELECT * FROM head_faculty_list');
$stmt->execute();
$head_faculties = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($id) {
    $stmt = $conn->prepare('SELECT * FROM head_faculty_list WHERE head_id = :head_id');
    $stmt->bindParam(':head_id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $faculty = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['delete_id'])) {
        // Retrieve form inputs
        $school_id = $_POST['school_id'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $cpass = $_POST['cpass'];
        $department = $_POST['department']; // New department field
        $avatar = isset($_FILES['img']['name']) ? $_FILES['img']['name'] : null;
        $id = $_POST['head_id'] ?? null; 

        // Validate passwords
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

        // Hash the password if provided
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        }

        // Handle avatar upload
        if ($avatar) {
            $target_dir = "assets/uploads/";
            $target_file = $target_dir . basename($_FILES["img"]["name"]);
            move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
        }

        // Fetch active academic year
        $query = 'SELECT academic_id FROM academic_list WHERE status = 1 AND start_date <= CURDATE() AND end_date >= CURDATE()';
        $stmt = $conn->query($query);
        $academic = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$academic) {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'No Active Academic Year!',
                        text: 'Registration is not allowed as there is no active academic year.',
                    });
                  </script>";
            return;
        }

        $academic_id = $academic['academic_id'];

        // Update or Insert logic
        if ($id) {
            // Update head faculty
            $query = "UPDATE head_faculty_list 
                      SET school_id = :school_id, firstname = :firstname, lastname = :lastname, email = :email, 
                          department = :department, academic_id = :academic_id";

            if (!empty($password)) {
                $query .= ", password = :password";
            }

            if ($avatar) {
                $query .= ", avatar = :avatar";
            }

            $query .= " WHERE head_id = :head_id";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(':school_id', $school_id);
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':department', $department); // Bind department
            $stmt->bindParam(':academic_id', $academic_id);

            if (!empty($password)) {
                $stmt->bindParam(':password', $hashed_password);
            }

            if ($avatar) {
                $stmt->bindParam(':avatar', $avatar);
            }

            $stmt->bindParam(':head_id', $id, PDO::PARAM_INT);
        } else {
            // Insert new head faculty
            $query = "INSERT INTO head_faculty_list (school_id, firstname, lastname, email, password, department, academic_id) 
                      VALUES (:school_id, :firstname, :lastname, :email, :password, :department, :academic_id)";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(':school_id', $school_id);
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            // $stmt->bindParam(':avatar', $avatar);
            $stmt->bindParam(':department', $department); // Bind department
            $stmt->bindParam(':academic_id', $academic_id);
        }

        // Execute the query and handle the result
        if ($stmt->execute()) {
            // Optional: Send email with credentials
            sendEmail($email, $password);

            // Success alert
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Head Faculty information saved successfully.',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.replace('head_faculty_list.php');
                    });
                  </script>";
        } else {
            // Error alert
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error saving data. Please try again.',
                    });
                  </script>";
        }

        // Close the connection
        $conn = null;
    }

    // Delete logic
    if (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];

        $stmt = $conn->prepare('DELETE FROM head_faculty_list WHERE head_id = :id');
        $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Head Faculty deleted successfully.';
        } else {
            error_log('Error deleting head faculty');
            $_SESSION['error'] = 'Error deleting head faculty. Please try again.';
        }

        echo "<script>window.location.replace('head_faculty_list.php');</script>";
    }
}


function sendEmail($toEmail, $plainPassword)
{
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'evaluationspc@gmail.com';
        $mail->Password = 'zjwz wnqx oyew nwst';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('your_email@gmail.com', 'SPC_EVAL');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
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