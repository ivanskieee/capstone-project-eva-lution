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

$id = isset($_GET['faculty_id']) ? $_GET['faculty_id'] : null;


$stmt = $conn->prepare('SELECT * FROM college_faculty_list');
$stmt->execute();
$tertiary_faculties = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($id) {
    $stmt = $conn->prepare('SELECT * FROM college_faculty_list WHERE faculty_id = :faculty_id');
    $stmt->bindParam(':faculty_id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $faculty = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['delete_id'])) {
        $school_id = $_POST['school_id'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $cpass = $_POST['cpass'];
        $subjects = $_POST['subjects'] ?? []; // Multiple subjects array
        $avatar = isset($_FILES['img']['name']) ? $_FILES['img']['name'] : null;
        $id = $_POST['faculty_id'] ?? null;

        // Check if passwords match
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

        // Handle file upload for avatar
        if ($avatar) {
            $target_dir = "assets/uploads/";
            $target_file = $target_dir . basename($_FILES["img"]["name"]);
            move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
        }

        // Handle subjects
        if (is_array($subjects) && count($subjects) > 1) {
            // Multiple subjects, store as comma-separated string
            $subjects_data = implode(", ", $subjects);
        } else {
            // Single subject, store as string
            $subjects_data = $subjects[0] ?? '';
        }

        try {
            if ($id) {
                // Update query
                $query = "UPDATE college_faculty_list 
                          SET school_id = :school_id, firstname = :firstname, lastname = :lastname, 
                              email = :email, subject = :subject";

                if (!empty($password)) {
                    $query .= ", password = :password";
                }

                if ($avatar) {
                    $query .= ", avatar = :avatar";
                }

                $query .= " WHERE faculty_id = :faculty_id";
                $stmt = $conn->prepare($query);

                $stmt->bindParam(':school_id', $school_id);
                $stmt->bindParam(':firstname', $firstname);
                $stmt->bindParam(':lastname', $lastname);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':subject', $subjects_data);

                if (!empty($password)) {
                    $stmt->bindParam(':password', $hashed_password);
                }

                if ($avatar) {
                    $stmt->bindParam(':avatar', $avatar);
                }

                $stmt->bindParam(':faculty_id', $id, PDO::PARAM_INT);
            } else {
                // Insert query
                $query = "INSERT INTO college_faculty_list (school_id, firstname, lastname, email, subject, password, avatar) 
                          VALUES (:school_id, :firstname, :lastname, :email, :subject, :password, :avatar)";
                $stmt = $conn->prepare($query);

                $stmt->bindParam(':school_id', $school_id);
                $stmt->bindParam(':firstname', $firstname);
                $stmt->bindParam(':lastname', $lastname);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':subject', $subjects_data);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':avatar', $avatar);
            }

            // Execute query
            $stmt->execute();

            // Success message
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Faculty information saved successfully.',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.replace('tertiary_faculty_list.php');
                    });
                  </script>";
        } catch (Exception $e) {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error saving data: {$e->getMessage()}',
                    });
                  </script>";
        }

        $conn = null;
    }

    // Handle delete request
    if (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];

        try {
            $stmt = $conn->prepare('DELETE FROM college_faculty_list WHERE faculty_id = :id');
            $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['message'] = 'Faculty deleted successfully.';
        } catch (Exception $e) {
            error_log("Error deleting faculty: " . $e->getMessage());
            $_SESSION['error'] = 'Error deleting faculty. Please try again.';
        }

        echo "<script>window.location.replace('tertiary_faculty_list.php');</script>";
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