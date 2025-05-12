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
include 'audit_log.php';

$id = isset($_GET['faculty_id']) ? $_GET['faculty_id'] : null;

$stmt = $conn->prepare('SELECT * FROM college_faculty_list');
$stmt->execute();
$tertiary_faculties = $stmt->fetchAll(PDO::FETCH_ASSOC);

$faculty = null;
$faculty_department = '';
$selected_subjects = [];

if ($id) {
    $stmt = $conn->prepare('SELECT * FROM college_faculty_list WHERE faculty_id = :faculty_id');
    $stmt->bindParam(':faculty_id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $faculty = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($faculty) {
        $faculty_id = $faculty['faculty_id'];

        try {
            $stmt = $conn->prepare("SELECT department, subject FROM college_faculty_list WHERE faculty_id = ?");
            $stmt->execute([$faculty_id]);
            $faculty_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($faculty_data) {
                $faculty_department = !empty($faculty_data['department']) ? $faculty_data['department'] : '';
                $selected_subjects = !empty($faculty_data['subject']) ? array_map('trim', explode(',', $faculty_data['subject'])) : [];
            }
        } catch (PDOException $e) {
            die("Error fetching faculty data: " . $e->getMessage());
        }
    }
}

// Fetch subjects based on faculty department
$department_subjects = [];
if (!empty($faculty_department)) {
    try {
        $subject_stmt = $conn->prepare("SELECT subject_code, subject_name FROM subjects WHERE department_code = ? ORDER BY subject_name ASC");
        $subject_stmt->execute([$faculty_department]);
        $department_subjects = $subject_stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching subjects: " . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_id = $_SESSION['user']['id'];

    if (!isset($_POST['delete_id'])) {
        $school_id = $_POST['school_id'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $cpass = $_POST['cpass'];
        $subjects = $_POST['subjects'] ?? []; // Multiple subjects array
        $department = $_POST['department']; // New department field
        $id = $_POST['faculty_id'] ?? null;

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

        if (is_array($subjects) && count($subjects) > 1) {
            $subjects_data = implode(", ", $subjects);
        } else {
            $subjects_data = $subjects[0] ?? '';
        }

        if ($id) {
            // Update query
            $query = "UPDATE college_faculty_list 
                          SET school_id = :school_id, firstname = :firstname, lastname = :lastname, 
                              email = :email, subject = :subject, academic_id = :academic_id, department = :department";

            if (!empty($password)) {
                $query .= ", password = :password";
            }

            $query .= " WHERE faculty_id = :faculty_id";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(':school_id', $school_id);
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':subject', $subjects_data);
            $stmt->bindParam(':academic_id', $academic_id);
            $stmt->bindParam(':department', $department);

            if (!empty($password)) {
                $stmt->bindParam(':password', $hashed_password);
            }

            $stmt->bindParam(':faculty_id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                log_action($conn, $admin_id, "Updated Faculty", "Updated: $firstname $lastname ($email)");
            }
        } else {
            // Insert query
            $query = "INSERT INTO college_faculty_list (school_id, firstname, lastname, email, subject, password, academic_id, department) 
                          VALUES (:school_id, :firstname, :lastname, :email, :subject, :password, :academic_id, :department)";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(':school_id', $school_id);
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':subject', $subjects_data);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':academic_id', $academic_id);
            $stmt->bindParam(':department', $department);
        }

        if ($stmt->execute()) {
            log_action($conn, $admin_id, "Added Faculty", "Added: $firstname $lastname ($email)");
            
            sendEmail($email, $password);

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
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error saving data.',
                    });
                  </script>";
        }

        $conn = null;
    }


    if (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];

        try {
            $stmt = $conn->prepare('SELECT firstname, lastname, email FROM college_faculty_list WHERE faculty_id = :id');
            $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
            $stmt->execute();
            $faculty = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($faculty) {
                $stmt = $conn->prepare('DELETE FROM college_faculty_list WHERE faculty_id = :id');
                $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
                $stmt->execute();

                log_action($conn, $admin_id, "Deleted Faculty", "Deleted: {$faculty['firstname']} {$faculty['lastname']} ({$faculty['email']})");
            }

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

        $mail->setFrom('your_email@gmail.com', 'SPC Evaluation System');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = 'Welcome! Your Faculty Evaluation Account is Active';
        $mail->Body = "
            <div style='text-align: justify; font-family: Arial, sans-serif; line-height: 1.5;'>
                <p>
                    Dear Faculty Member,
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
        $mail->AltBody = "Dear Faculty Member,\n\nYour faculty evaluation account has been successfully created.\n\nLogin Details:\nEmail: $toEmail\nPassword: $plainPassword\n\nAccess the system here: http://localhost/Capstone-Eva-lution/\n\nThank you!\nSan Pablo Colleges Admin";


        $mail->send();
        echo 'Email has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

?>