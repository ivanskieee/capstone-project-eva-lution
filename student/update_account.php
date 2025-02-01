<?php
session_start();
include '../database/connection.php';

$response = ['success' => false, 'message' => 'Failed to update account'];

if (!isset($_SESSION['user']['student_id'])) {
    $response['message'] = 'User is not logged in.';
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['user']['student_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $firstname = $_POST['firstname'] ?? null;
    $lastname = $_POST['lastname'] ?? null;
    $password = $_POST['password'] ?? null;
    $cpass = $_POST['cpass'] ?? null;

    try {
        // Validate password
        if ($password && $password !== $cpass) {
            throw new Exception('Passwords do not match.');
        }

        // Update firstname, lastname, and email
        $updateQuery = $conn->prepare("
            UPDATE student_list 
            SET email = :email, 
                firstname = :firstname, 
                lastname = :lastname 
            WHERE student_id = :student_id
        ");
        $updateQuery->bindParam(':email', $email);
        $updateQuery->bindParam(':firstname', $firstname);
        $updateQuery->bindParam(':lastname', $lastname);
        $updateQuery->bindParam(':student_id', $user_id);
        $updateQuery->execute();

        // Update password (if provided)
        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updatePassword = $conn->prepare("
                UPDATE student_list 
                SET password = :password 
                WHERE student_id = :student_id
            ");
            $updatePassword->bindParam(':password', $hashedPassword);
            $updatePassword->bindParam(':student_id', $user_id);
            $updatePassword->execute();
        }

        $response['success'] = true;
        $response['message'] = 'Account updated successfully';
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
}

echo json_encode($response);
?>