<?php
session_start();

function flash($message, $type = 'info')
{
    if (!isset($_SESSION['flash_messages'])) {
        $_SESSION['flash_messages'] = [];
    }
    $_SESSION['flash_messages'][$type][] = $message;
}

include 'includes/header.php';
include('database/connection.php');

if (isset($_SESSION['user'])) {
    // Redirect based on role if already logged in
    switch ($_SESSION['user']['role']) {
        case 'admin':
            header('Location: admin/home.php');
            break;
        case 'student':
            header('Location: student/home.php');
            break;
        case 'faculty':
            header('Location: faculty/home.php');
            break;
        case 'head_faculty':
            header('Location: head_faculty/home.php');
            break;
        case 'secondary_faculty':
            header('Location: secondary_faculty/home.php');
            break;
    }
    exit;
}

if ($_POST) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        flash('Email and password are required.', 'danger');
        header('location: index.php');
        exit;
    }

    // Define roles and corresponding tables
    $roles = [
        'admin' => 'users',
        'faculty' => 'college_faculty_list',
        'secondary_faculty' => 'secondary_faculty_list',
        'head_faculty' => 'head_faculty_list',
        'student' => 'student_list'
    ];

    $user = null;
    $role = null;

    // Check all roles
    foreach ($roles as $roleKey => $table) {
        $query = "SELECT * FROM $table WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $user = $stmt->fetch();

            if (password_verify($password, $user['password'])) {
                $role = $roleKey;
                break; // Stop searching
            } else {
                $user = null; // Reset user if password doesn't match
            }
        }
    }

    if ($user) {
        // Check if student's account is closed (only for 'student' role)
        if ($role === 'student' && isset($user['account_status']) && $user['account_status'] == 0) {
            flash('Your account has been closed. Please contact the administrator.', 'danger');
            header('location: index.php');
            exit;
        }

        $user['role'] = $role;
        $_SESSION['user'] = $user;
        $_SESSION['login_name'] = $user['firstname'] . ' ' . $user['lastname'];

        // Handle student-specific logic
        if ($role === 'student') {
            $student_id = $user['student_id']; // Use student ID from login
            $studentQuery = 'SELECT academic_id FROM student_list WHERE student_id = :student_id';
            $stmtStudent = $conn->prepare($studentQuery);
            $stmtStudent->execute([':student_id' => $student_id]);
            $studentAcademicId = $stmtStudent->fetchColumn();

            if (!$studentAcademicId) {
                flash('Student record not found. Please contact the administrator.', 'danger');
                header('location: index.php');
                exit;
            }

            // Validate active academic period
            $query = 'SELECT * FROM academic_list 
                      WHERE status = 1 
                      AND start_date <= CURDATE() 
                      AND end_date >= CURDATE() 
                      AND academic_id = :academic_id';
            $stmt = $conn->prepare($query);
            $stmt->execute([':academic_id' => $studentAcademicId]);

            if ($stmt->rowCount() === 0) {
                flash('Login is not allowed as the evaluation period is closed or not started for your academic year.', 'danger');
                header('location: index.php');
                exit;
            }
        }

        // Redirect based on role
        switch ($role) {
            case 'admin':
                header('location: admin/home.php');
                break;
            case 'student':
                header('location: student/home.php');
                break;
            case 'faculty':
                header('location: faculty/home.php');
                break;
            case 'head_faculty':
                header('location: head_faculty/home.php');
                break;
            case 'secondary_faculty':
                header('location: secondary_faculty/home.php');
                break;
        }
        exit;
    }

    // Invalid login
    flash('Username or password is incorrect.', 'danger');
    header('location: index.php');
    exit;
}
?>
