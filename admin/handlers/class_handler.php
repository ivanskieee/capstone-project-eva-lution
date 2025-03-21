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

include 'header.php';
include 'sidebar.php';
include 'footer.php';
include '../database/connection.php';

$id = isset($_GET['class_id']) ? $_GET['class_id'] : null;

$stmt = $conn->prepare('SELECT * FROM class_list');
$stmt->execute();
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($id) {
    $stmt = $conn->prepare("SELECT * FROM class_list WHERE class_id = ?");
    $stmt->execute([$id]);
    $classes = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];
        $stmt = $conn->prepare('DELETE FROM class_list WHERE class_id = :id');
        $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['flash_message'] = 'Class deleted successfully.';
            $_SESSION['flash_type'] = 'success';
        } else {
            error_log('Error deleting class');
            $_SESSION['flash_message'] = 'Error deleting class. Please try again.';
            $_SESSION['flash_type'] = 'error';
        }

        echo "<script>window.location.replace('class_list.php');</script>";
    }
    
    elseif (isset($_POST['course'], $_POST['level'], $_POST['section'])) {
        $course = $_POST['course'];
        $level = $_POST['level'];
        $section = $_POST['section'];
        $id = $_POST['class_id'] ?? null;

        if ($id) {
            $query = "UPDATE class_list SET course = ?, level = ?, section = ? WHERE class_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$course, $level, $section, $id]);
        } else {
            $query = "INSERT INTO class_list (course, level, section) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$course, $level, $section]);
        }

        $_SESSION['flash_message'] = 'Data successfully saved.';
        $_SESSION['flash_type'] = 'success';

        echo "<script>window.location.replace('class_list.php');</script>";
        exit;
    }
}

$conn = null;

