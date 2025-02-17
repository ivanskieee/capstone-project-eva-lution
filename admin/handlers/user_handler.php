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
include 'audit_log.php'; // Include the audit log functions

$id = isset($_GET['id']) ? $_GET['id'] : null;

$stmt = $conn->prepare('SELECT * FROM users');
$stmt->execute();
$admin = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($id) {
    $stmt = $conn->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = $_SESSION['user']['id'];  // Get the current admin's user ID

    if (!isset($_POST['delete_id'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $cpass = $_POST['cpass'];
        $avatar = isset($_FILES['img']['name']) ? $_FILES['img']['name'] : null;
        $id = $_POST['id'] ?? null;

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

        // Hash password if not empty
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        }

        // Fetch the current active academic_id
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

        $academic_id = $academic['academic_id']; // Active academic ID

        // Update or Insert
        if ($id) {
            $query = "UPDATE users 
                      SET firstname = :firstname, lastname = :lastname, email = :email, academic_id = :academic_id";

            if (!empty($password)) {
                $query .= ", password = :password";
            }

            $query .= " WHERE id = :id";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':academic_id', $academic_id);

            if (!empty($password)) {
                $stmt->bindParam(':password', $hashed_password);
            }

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Log the update action
                log_action($conn, $admin_id, "Updated User", "Updated: $firstname $lastname ($email)");
            }
        } else {
            $query = "INSERT INTO users (firstname, lastname, email, password, academic_id) 
                      VALUES (:firstname, :lastname, :email, :password, :academic_id)";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':academic_id', $academic_id);

            if ($stmt->execute()) {
                // Log the insert action
                log_action($conn, $admin_id, "Added User", "Added: $firstname $lastname ($email)");

                sendEmail($email, $password); // Optional: send email to the user
            }
        }

        // Success or error feedback
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'User information saved successfully.',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.replace('user_list.php');
                });
              </script>";
    }

    if (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];

        $stmt = $conn->prepare('SELECT firstname, lastname, email FROM users WHERE id = :id');
        $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $stmt = $conn->prepare('DELETE FROM users WHERE id = :id');
            $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
            $stmt->execute();

            // Log the delete action
            log_action($conn, $admin_id, "Deleted User", "Deleted: {$user['firstname']} {$user['lastname']} ({$user['email']})");

            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'User deleted successfully.',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.replace('user_list.php');
                    });
                  </script>";
        }
    }
}

$conn = null;


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

        $mail->setFrom('your_email@gmail.com', 'SPC Evaluation System');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = 'Welcome! Your Evaluation Account is Active';
        $mail->Body = "
            <div style='text-align: justify; font-family: Arial, sans-serif; line-height: 1.5;'>
                <p>
                    Dear Admin,
                </p>
                <p>
                    We are pleased to inform you that your account for the faculty evaluation system has been successfully created and activated.
                </p>
                <p>
                    You can now access the system by clicking on the following link: 
                    <a href='http://localhost/Capstone-Eva-lution/'>http://localhost/Capstone-Eva-lution/</a>.
                </p>
                <p>
                    Below are your default login credentials:
                </p>
                <p>
                    <b>Email:</b> $toEmail <br>
                    <b>Password:</b> $plainPassword
                </p>
                <p>
                    We encourage you to log in and familiarize yourself with the evaluation platform. 
                    You can change your password and update your profile through your dashboard.
                </p>
                <p>
                    Thank you,<br><br>
                    San Pablo Colleges Admin
                </p>
            </div>
        ";
        $mail->AltBody = "Dear Admin,\n\nYour faculty evaluation account has been successfully created.\n\nLogin Details:\nEmail: $toEmail\nPassword: $plainPassword\n\nAccess the system here: http://localhost/Capstone-Eva-lution/\n\nThank you!\nSan Pablo Colleges Admin";


        $mail->send();
        echo 'Email has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
