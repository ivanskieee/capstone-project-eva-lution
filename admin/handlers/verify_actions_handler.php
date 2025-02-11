<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../database/connection.php';

function sendVerificationEmail($recipientEmail, $subject, $body)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'evaluationspc@gmail.com';
        $mail->Password = 'zjwz wnqx oyew nwst';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('evaluationspc@gmail.com', 'SPC Evaluation System');
        $mail->addAddress($recipientEmail);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
    } catch (Exception $e) {
        error_log("Email failed to send: " . $mail->ErrorInfo);
    }
}

// Fetch active academic ID
$query = 'SELECT academic_id FROM academic_list WHERE status = 1 AND start_date <= CURDATE() AND end_date >= CURDATE()';
$stmt = $conn->query($query);
$academic = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$academic) {
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'No Active Academic Year!',
                text: 'Registration is not allowed as there is no active academic year.',
            }).then(() => {
                window.location.reload();
            });
          </script>";
    exit();
}

$academic_id = $academic['academic_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_ids = $_POST['student_ids'] ?? [];
    $action = $_POST['action'] ?? '';

    if (empty($student_ids)) {
        header('Location: verify_accounts.php?status=no_selection');
        exit();
    }

    // Convert array to string placeholders for safe query execution
    $placeholders = implode(',', array_fill(0, count($student_ids), '?'));

    if ($action === 'bulk_confirm') {
        try {
            $conn->beginTransaction(); // Start transaction

            // Fetch students to be approved
            $query = "SELECT * FROM pending_students WHERE school_id IN ($placeholders)";
            $stmt = $conn->prepare($query);
            $stmt->execute($student_ids);
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$students) {
                throw new Exception("No students found for confirmation.");
            }

            // Check if the subject exists in college_faculty_list
            foreach ($students as $student) {
                $subjects = explode(" ", $student['subject']); // Split the student's subject by space

                // Check for each subject from the student's list
                $subject_valid = false;
                foreach ($subjects as $subject) {
                    // Query to check if the subject exists in college_faculty_list with partial matching
                    $subject_query = "SELECT * FROM college_faculty_list WHERE subject LIKE :subject";
                    $stmt = $conn->prepare($subject_query);
                    $stmt->execute(['subject' => '%' . $subject . '%']);
                    $subject_check = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($subject_check) {
                        $subject_valid = true; // Mark as valid if any subject matches
                        break; // Exit the loop once a match is found
                    }
                }

                if (!$subject_valid) {
                    // If no subject matches, reject the student
                    echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'No Subject Enrolled!',
                                text: 'The subjects for {$student['firstname']} {$student['lastname']} are not valid.',
                            }).then(() => {
                                window.history.back();
                            });
                          </script>";
                    $conn->rollBack(); // Rollback any changes so far
                    exit(); // Exit the script to prevent further processing
                }
            }

            // If all subjects are valid, proceed with the student insertion
            foreach ($students as $student) {
                // Insert into `student_list`
                $insert_query = "
                    INSERT INTO student_list (school_id, email, password, firstname, lastname, subject, section, academic_id) 
                    VALUES (:school_id, :email, :password, :firstname, :lastname, :subject, :section, :academic_id)
                ";
                $stmt = $conn->prepare($insert_query);
                $stmt->execute([
                    'school_id' => $student['school_id'],
                    'email' => $student['email'],
                    'password' => $student['password'],
                    'firstname' => $student['firstname'],
                    'lastname' => $student['lastname'],
                    'subject' => $student['subject'],
                    'section' => $student['section'],
                    'academic_id' => $academic_id
                ]);

                // Send approval email
                $emailBody = "
                    <div style='text-align: justify; font-family: Arial, sans-serif; line-height: 1.5;'>
                        <p>
                            Dear {$student['firstname']} {$student['lastname']},
                        </p>
                        <p>
                            We are pleased to inform you that your account for the student evaluation has been successfully approved and activated.
                        </p>
                        <p>
                            You can now access the system by clicking on the following link: 
                            <a href='http://localhost/Capstone-Eva-lution/'>http://localhost/Capstone-Eva-lution/</a>.
                        </p>
                        <p>
                            We encourage you to log in and familiarize yourself with the evaluation platform. 
                            You can change your password and other credentials through your dashboard.
                        </p>
                        <p>
                            Thank you,<br><br>
                            San Pablo Colleges Admin
                        </p>
                    </div>
                ";

                sendVerificationEmail($student['email'], 'Welcome! Your Student Evaluation Account is Active', $emailBody);
            }

            // Remove approved students from pending list
            $delete_query = "DELETE FROM pending_students WHERE school_id IN ($placeholders)";
            $stmt = $conn->prepare($delete_query);
            $stmt->execute($student_ids);

            $conn->commit(); // Commit the transaction

            header('Location: verify_accounts.php?status=bulk_confirmed');
            exit();
        } catch (Exception $e) {
            $conn->rollBack(); // Rollback transaction if something fails
            error_log("Error processing bulk confirmation: " . $e->getMessage());
            header('Location: verify_accounts.php?status=error');
            exit();
        }
    } elseif ($action === 'bulk_remove') {
        try {
            $conn->beginTransaction();

            // Fetch students to be rejected
            $fetch_query = "SELECT email, firstname, lastname FROM pending_students WHERE school_id IN ($placeholders)";
            $stmt = $conn->prepare($fetch_query);
            $stmt->execute($student_ids);
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$students) {
                throw new Exception("No students found for rejection.");
            }

            foreach ($students as $student) {
                // Send rejection email
                $emailBody = "
                    <div style='text-align: justify; font-family: Arial, sans-serif; line-height: 1.5;'>
                        <p>
                            Dear {$student['firstname']} {$student['lastname']},
                        </p>
                        <p>
                            We regret to inform you that your account registration has been declined. The subjects you selected during the registration process do not match the subjects in which you are currently enrolled.
                        </p>
                        <p>
                            Please contact your professor or department secretary for clarification regarding your enrolled subjects.
                        </p>
                        <p>
                            Thank you for your understanding.
                        </p>
                        <p>
                            San Pablo Colleges Admin
                        </p>
                    </div>
                ";
                sendVerificationEmail($student['email'], 'Account Registration Declined', $emailBody);

            }

            // Delete students from pending list
            $delete_query = "DELETE FROM pending_students WHERE school_id IN ($placeholders)";
            $stmt = $conn->prepare($delete_query);
            $stmt->execute($student_ids);

            $conn->commit();

            header('Location: verify_accounts.php?status=bulk_removed');
            exit();
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Error processing bulk removal: " . $e->getMessage());
            header('Location: verify_accounts.php?status=error');
            exit();
        }
    }
}
?>