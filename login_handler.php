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

// $query = 'SELECT * FROM academic_list WHERE is_default = 1 AND status = 1 AND start_date <= CURDATE() AND end_date >= CURDATE()';
// $stmt = $conn->query($query);

// if ($stmt->rowCount() === 0) {
//     flash('Login is not allowed as the evaluation period is closed or not started.', 'danger');
//     header('location: index.php');
//     exit;
// }

if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'admin') {
        header('Location: admin/home.php');
    } elseif ($_SESSION['user']['role'] === 'student') {
        header('Location: student/home.php');
    } elseif ($_SESSION['user']['role'] === 'faculty') {
        header('Location: faculty/home.php');
    } elseif ($_SESSION['user']['role'] === 'head_faculty') {
        header('Location: head_faculty/home.php');
    } elseif ($_SESSION['user']['role'] === 'secondary_faculty') {
        header('Location: secondary_faculty/home.php');
    }
    exit;
}

if ($_POST) {
    include('database/connection.php');

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = 'SELECT * FROM users WHERE email = :email';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetch();

        if (password_verify($password, $user['password'])) {
            $user['role'] = 'admin';
            $_SESSION['user'] = $user;
            $_SESSION['login_name'] = $user['firstname'] . ' ' . $user['lastname'];
            header('location: admin/home.php');
            exit;
        }
    }

    $query = 'SELECT * FROM student_list WHERE email = :email';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetch();

        if (password_verify($password, $user['password'])) {
            $user['role'] = 'student';
            $_SESSION['user'] = $user;
            $_SESSION['login_name'] = $user['firstname'] . ' ' . $user['lastname'];
            header('location: student/home.php');
            exit;
        }
    }

    $query = 'SELECT * FROM college_faculty_list WHERE email = :email';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetch();

        if (password_verify($password, $user['password'])) {
            $user['role'] = 'faculty';
            $_SESSION['user'] = $user;
            $_SESSION['login_name'] = $user['firstname'] . ' ' . $user['lastname'];
            header('location: faculty/home.php');
            exit;
        }
    }

    $query = 'SELECT * FROM secondary_faculty_list WHERE email = :email';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetch();

        if (password_verify($password, $user['password'])) {
            $user['role'] = 'secondary_faculty';
            $_SESSION['user'] = $user;
            $_SESSION['login_name'] = $user['firstname'] . ' ' . $user['lastname'];
            header('location: secondary_faculty/home.php');
            exit;
        }
    }

    $query = 'SELECT * FROM head_faculty_list WHERE email = :email';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetch();

        if (password_verify($password, $user['password'])) {
            $user['role'] = 'head_faculty';
            $_SESSION['user'] = $user;
            $_SESSION['login_name'] = $user['firstname'] . ' ' . $user['lastname'];
            header('location: head_faculty/home.php');
            exit;
        }
    }

    flash('Username or password is incorrect.', 'danger');
    header('location: index.php');
    exit;
}
?>