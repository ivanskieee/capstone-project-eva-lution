<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: ../index.php');
    exit;
}

if ($_SESSION['user']['role'] !== 'admin') {
    // Redirect to an unauthorized page or login page if they don't have the correct role
    header('Location: unauthorized.php');
    exit;
}

include 'header.php';
include 'sidebar.php';
include 'footer.php';
include '../database/connection.php';

$id = isset($_POST['id']) ? $_POST['id'] : null;
$classes = null;

$stmt = $conn->prepare('SELECT * FROM class_list');
$stmt->execute();
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($id) {
    $stmt = $conn->prepare("SELECT * FROM class_list WHERE class_id = ?");
    $stmt->execute([$class_id]);
    $classes = $stmt->fetch();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete_id'])) {
    $course = $_POST['course'];
    $level = $_POST['level'];
    $section = $_POST['section'];

    if ($id) {
        $query = "UPDATE class_list SET course = ?, level = ?, section = ? WHERE class_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$course, $level, $section, $id]);
    } else {
        $query = "INSERT INTO class_list (course, level, section) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$course, $level, $section]);
    }

    $conn = null;

    $_SESSION['flash_message'] = 'Data successfully saved.';
    $_SESSION['flash_type'] = 'success';

    echo "<script>window.location.replace('class_list.php');</script>";

    exit;
}

if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $stmt = $conn->prepare('DELETE FROM class_list WHERE class_id = :id');
    $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('class is deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting class.');</script>";
    }

    echo "<script>window.location.replace('class_list.php');</script>";
}

$conn = null;

