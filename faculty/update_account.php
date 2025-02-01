<?php
session_start();
include '../database/connection.php';

// Initialize response array
$response = ['success' => false, 'message' => 'Failed to update account'];

// Check if the faculty user is logged in
if (!isset($_SESSION['user']['faculty_id'])) {
    $response['message'] = 'User is not logged in.';
    echo json_encode($response);
    exit;
}

$faculty_id = $_SESSION['user']['faculty_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve posted data; adjust the keys to match your form inputs
    $department = $_POST['department'] ?? null;
    $subject    = $_POST['subject'] ?? null;
    $password   = $_POST['password'] ?? null;
    $cpass      = $_POST['cpass'] ?? null;
    
    try {
        // If a new password is provided, validate it
        if ($password && $password !== $cpass) {
            throw new Exception('Passwords do not match.');
        }
        
        // First, update department and subject details
        $updateQuery = $conn->prepare("
            UPDATE college_faculty_list 
            SET department = :department,
                subject = :subject
            WHERE faculty_id = :faculty_id
        ");
        $updateQuery->bindParam(':department', $department);
        $updateQuery->bindParam(':subject', $subject);
        $updateQuery->bindParam(':faculty_id', $faculty_id);
        $updateQuery->execute();
        
        // Next, update the password if provided
        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updatePassword = $conn->prepare("
                UPDATE college_faculty_list 
                SET password = :password 
                WHERE faculty_id = :faculty_id
            ");
            $updatePassword->bindParam(':password', $hashedPassword);
            $updatePassword->bindParam(':faculty_id', $faculty_id);
            $updatePassword->execute();
        }
        
        $response['success'] = true;
        $response['message'] = 'Account updated successfully';
    } catch (Exception $e) {
        // Catch and report any errors that occur during the update process
        $response['message'] = $e->getMessage();
    }
}

echo json_encode($response);
?>
