<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Adjust path based on your directory structure
include '../database/connection.php';

function sendVerificationEmail($recipientEmail, $subject, $body)
{
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'evaluationspc@gmail.com'; // Replace with your email
        $mail->Password = 'zjwz wnqx oyew nwst'; // Replace with your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and recipient settings
        $mail->setFrom('your_email@gmail.com', 'SPC_EVAL'); // Replace with your email and name
        $mail->addAddress($recipientEmail);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
    } catch (Exception $e) {
        error_log("Error sending email: {$mail->ErrorInfo}");
    }
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $school_id = $_POST['school_id'] ?? null; // Single action ID
    $student_ids = $_POST['student_ids'] ?? []; // Bulk action IDs
    $action = $_POST['action'];

    if ($action == 'confirm') {
        // Single confirm logic
        $query = "
            SELECT 
                school_id,
                email,
                password,
                firstname,
                lastname,
                subject,
                section
            FROM pending_students
            WHERE school_id = :school_id
        ";
        $stmt = $conn->prepare($query);
        $stmt->execute(['school_id' => $school_id]);
        $student_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student_data) {
            $faculty_query = "
                SELECT faculty_id 
                FROM college_faculty_list 
                WHERE :subject LIKE CONCAT('%', subject, '%')
            ";
            $faculty_stmt = $conn->prepare($faculty_query);
            $faculty_stmt->execute(['subject' => $student_data['subject']]);
            $faculty_data = $faculty_stmt->fetch(PDO::FETCH_ASSOC);

            if ($faculty_data) {
                $insert_query = "
                    INSERT INTO student_list (school_id, email, password, firstname, lastname, subject, section, academic_id) 
                    VALUES (:school_id, :email, :password, :firstname, :lastname, :subject, :section, :academic_id)
                ";
                $stmt = $conn->prepare($insert_query);
                $stmt->execute([
                    'school_id' => $student_data['school_id'],
                    'email' => $student_data['email'],
                    'password' => $student_data['password'],
                    'firstname' => $student_data['firstname'],
                    'lastname' => $student_data['lastname'],
                    'subject' => $student_data['subject'],
                    'section' => $student_data['section'],
                    'academic_id' => $academic_id
                ]);

                $delete_query = "DELETE FROM pending_students WHERE school_id = :school_id";
                $stmt = $conn->prepare($delete_query);
                $stmt->execute(['school_id' => $school_id]);

                $emailBody = "
                    Dear {$student_data['firstname']} {$student_data['lastname']},<br><br>
                    Your account has been successfully approved and registered. Please visit our evaluation site and log in with your account.<br><br>
                    Thank you!
                ";
                sendVerificationEmail($student_data['email'], 'Account Approved', $emailBody);

                header('Location: verify_accounts.php?status=confirmed');
                exit();
            } else {
                header('Location: verify_accounts.php?status=no_faculty_match');
                exit();
            }
        } else {
            header('Location: verify_accounts.php?status=not_found');
            exit();
        }
    } elseif ($action == 'remove') {
        // Single remove logic
        $query = "SELECT email, firstname, lastname FROM pending_students WHERE school_id = :school_id";
        $stmt = $conn->prepare($query);
        $stmt->execute(['school_id' => $school_id]);
        $student_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student_data) {
            $delete_query = "DELETE FROM pending_students WHERE school_id = :school_id";
            $stmt = $conn->prepare($delete_query);
            $stmt->execute(['school_id' => $school_id]);

            $emailBody = "
                Dear {$student_data['firstname']} {$student_data['lastname']},<br><br>
                We regret to inform you that your account registration has been declined.<br><br>
                Thank you!
            ";
            sendVerificationEmail($student_data['email'], 'Account Rejected', $emailBody);
        }

        header('Location: verify_accounts.php?status=rejected');
        exit();
    } elseif ($action == 'bulk_confirm') {
        // Bulk confirm logic
        if (empty($student_ids)) {
            header('Location: verify_accounts.php?status=no_selection');
            exit();
        }

        $placeholders = implode(',', array_fill(0, count($student_ids), '?'));
        $query = "SELECT * FROM pending_students WHERE school_id IN ($placeholders)";
        $stmt = $conn->prepare($query);
        $stmt->execute($student_ids);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($students as $student_data) {
            $faculty_query = "
                SELECT faculty_id 
                FROM college_faculty_list 
                WHERE ? LIKE CONCAT('%', subject, '%')
            ";
            $faculty_stmt = $conn->prepare($faculty_query);
            $faculty_stmt->execute([$student_data['subject']]);
            $faculty_data = $faculty_stmt->fetch(PDO::FETCH_ASSOC);

            if ($faculty_data) {
                $insert_query = "
                    INSERT INTO student_list (school_id, email, password, firstname, lastname, subject, section, academic_id) 
                    VALUES (:school_id, :email, :password, :firstname, :lastname, :subject, :section, :academic_id)
                ";
                $stmt = $conn->prepare($insert_query);
                $stmt->execute([
                    'school_id' => $student_data['school_id'],
                    'email' => $student_data['email'],
                    'password' => $student_data['password'],
                    'firstname' => $student_data['firstname'],
                    'lastname' => $student_data['lastname'],
                    'subject' => $student_data['subject'],
                    'section' => $student_data['section'],
                    'academic_id' => $academic_id
                ]);

                $emailBody = "
                    Dear {$student_data['firstname']} {$student_data['lastname']},<br><br>
                    Your account has been successfully approved and registered.<br><br>
                    Thank you!
                ";
                sendVerificationEmail($student_data['email'], 'Account Approved', $emailBody);
            }
        }

        $delete_query = "DELETE FROM pending_students WHERE school_id IN ($placeholders)";
        $stmt = $conn->prepare($delete_query);
        $stmt->execute($student_ids);

        header('Location: verify_accounts.php?status=bulk_confirmed');
        exit();
    } elseif ($action == 'bulk_remove') {
        // Bulk remove logic
        if (empty($student_ids)) {
            header('Location: verify_accounts.php?status=no_selection');
            exit();
        }
    
        // Fetch student data for email notifications before deletion
        $placeholders = implode(',', array_fill(0, count($student_ids), '?'));
        $fetch_query = "SELECT email, firstname, lastname FROM pending_students WHERE school_id IN ($placeholders)";
        $stmt = $conn->prepare($fetch_query);
        $stmt->execute($student_ids);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if ($students) {
            foreach ($students as $student_data) {
                $emailBody = "
                    Dear {$student_data['firstname']} {$student_data['lastname']},<br><br>
                    We regret to inform you that your account registration has been declined.<br><br>
                    Thank you!
                ";
                sendVerificationEmail($student_data['email'], 'Account Rejected', $emailBody);
            }
    
            // Delete students from the pending list
            $delete_query = "DELETE FROM pending_students WHERE school_id IN ($placeholders)";
            $stmt = $conn->prepare($delete_query);
            $stmt->execute($student_ids);
    
            header('Location: verify_accounts.php?status=bulk_removed');
            exit();
        }
    }
}
?>
