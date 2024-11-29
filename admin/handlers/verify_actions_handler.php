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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $school_id = $_POST['school_id'];
    $action = $_POST['action'];

    if ($action == 'confirm') {
        // Fetch data from `pending_students`
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
            // Match faculty based on subject
            $faculty_query = "
                SELECT faculty_id 
                FROM college_faculty_list 
                WHERE :subject LIKE CONCAT('%', subject, '%')
            ";
            $faculty_stmt = $conn->prepare($faculty_query);
            $faculty_stmt->execute(['subject' => $student_data['subject']]);
            $faculty_data = $faculty_stmt->fetch(PDO::FETCH_ASSOC);
        
            if ($faculty_data) {
                // Insert the student into `student_list`
                $insert_query = "
                    INSERT INTO student_list (school_id, email, password, firstname, lastname, subject, section) 
                    VALUES (:school_id, :email, :password, :firstname, :lastname, :subject, :section)
                ";
                $stmt = $conn->prepare($insert_query);
                $stmt->execute([
                    'school_id' => $student_data['school_id'],
                    'email' => $student_data['email'],
                    'password' => $student_data['password'],
                    'firstname' => $student_data['firstname'],
                    'lastname' => $student_data['lastname'],
                    'subject' => $student_data['subject'],
                    'section' => $student_data['section']
                ]);
        
                // Remove the student from `pending_students`
                $delete_query = "DELETE FROM pending_students WHERE school_id = :school_id";
                $stmt = $conn->prepare($delete_query);
                $stmt->execute(['school_id' => $school_id]);

                // Send confirmation email
                $emailBody = "
                    Dear {$student_data['firstname']} {$student_data['lastname']},<br><br>
                    Your account has been successfully approved and registered. Please visit our evaluation site and log in with your account.<br><br>
                    Thank you!
                ";
                sendVerificationEmail($student_data['email'], 'Account Approved', $emailBody);

                header('Location: verify_accounts.php?status=confirmed');
                exit();
            } else {
                // No matching faculty found
                header('Location: verify_accounts.php?status=no_faculty_match');
                exit();
            }
        } else {
            // No student found
            header('Location: verify_accounts.php?status=not_found');
            exit();
        }
    } elseif ($action == 'remove') {
        // Fetch student email for rejection notification
        $query = "SELECT email, firstname, lastname FROM pending_students WHERE school_id = :school_id";
        $stmt = $conn->prepare($query);
        $stmt->execute(['school_id' => $school_id]);
        $student_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student_data) {
            // Remove the student from `pending_students`
            $delete_query = "DELETE FROM pending_students WHERE school_id = :school_id";
            $stmt = $conn->prepare($delete_query);
            $stmt->execute(['school_id' => $school_id]);

            // Send rejection email
            $emailBody = "
                Dear {$student_data['firstname']} {$student_data['lastname']},<br><br>
                We regret to inform you that your account registration has been declined.<br><br>
                Thank you!
            ";
            sendEmail($student_data['email'], 'Account Rejected', $emailBody);
        }

        header('Location: verify_accounts.php?status=rejected');
        exit();
    }
}
?>
