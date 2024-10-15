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

$id = isset($_GET['subject_id']) ? $_GET['subject_id'] : null;

$stmt = $conn->prepare('SELECT * FROM subject_list');
$stmt->execute();
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($id) {
    $stmt = $conn->prepare("SELECT * FROM subject_list WHERE subject_id = ?");
    $stmt->execute([$id]);
    $subjects = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle delete request
    if (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];
        $stmt = $conn->prepare('DELETE FROM subject_list WHERE subject_id = :id');
        $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Subject deleted successfully.';
        } else {
            error_log('Error deleting subject');
            $_SESSION['error'] = 'Error deleting subject. Please try again.';
        }
        
        echo "<script>window.location.replace('subject_list.php');</script>";
    } 
    // Handle add/update request
    elseif (isset($_POST['code'], $_POST['subject'], $_POST['description'])) {
        $code = $_POST['code'];
        $subject = $_POST['subject'];
        $description = $_POST['description'];
        $id = $_POST['subject_id'] ?? null;

        if ($id) {
            $query = "UPDATE subject_list SET code = ?, subject = ?, description = ? WHERE subject_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$code, $subject, $description, $id]);
            $_SESSION['message'] = 'Subject updated successfully.';
        } else {
            $query = "INSERT INTO subject_list (code, subject, description) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$code, $subject, $description]);
            $_SESSION['message'] = 'Subject added successfully.';
        }

        echo "<script>window.location.replace('subject_list.php');</script>";
        exit;
    }
}

$conn = null;



