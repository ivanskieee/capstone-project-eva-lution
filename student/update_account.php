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
    $password = $_POST['password'] ?? null;
    $cpass = $_POST['cpass'] ?? null;
    $avatar = $_FILES['avatar'] ?? null;

    try {
        if ($avatar && $avatar['size'] > 0) {
            $avatarPath = 'uploads/' . basename($avatar['name']);
            if (move_uploaded_file($avatar['tmp_name'], $avatarPath)) {
                $updateAvatar = $conn->prepare("UPDATE student_list SET avatar = :avatar WHERE student_id = :student_id");
                $updateAvatar->bindParam(':avatar', $avatarPath);
                $updateAvatar->bindParam(':student_id', $user_id);
                $updateAvatar->execute();
            } else {
                throw new Exception('Failed to upload avatar.');
            }
        }

        $updateEmail = $conn->prepare("UPDATE student_list SET email = :email WHERE student_id = :student_id");
        $updateEmail->bindParam(':email', $email);
        $updateEmail->bindParam(':student_id', $user_id);
        $updateEmail->execute();

        if ($password && $password === $cpass) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updatePassword = $conn->prepare("UPDATE student_list SET password = :password WHERE student_id = :student_id");
            $updatePassword->bindParam(':password', $hashedPassword);
            $updatePassword->bindParam(':student_id', $user_id);
            $updatePassword->execute();
        } elseif ($password && $password !== $cpass) {
            throw new Exception('Passwords do not match.');
        }

        $response['success'] = true;
        $response['message'] = 'Account updated successfully';
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
}

echo json_encode($response);
?>
