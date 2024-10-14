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

$id = isset($_GET['criteria_id']) ? $_GET['criteria_id'] : null;

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM criteria_list WHERE criteria_id = ?");
    $stmt->execute([$id]);
    $criteria_to_edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

$stmt = $conn->prepare('SELECT * FROM criteria_list');
$stmt->execute();
$criterias = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];
        $stmt = $conn->prepare('DELETE FROM criteria_list WHERE criteria_id = :id');
        $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Criteria deleted successfully.';
        } else {
            error_log('Error deleting criteria');
            $_SESSION['error'] = 'Error deleting criteria. Please try again.';
        }
        
        echo "<script>window.location.replace('criteria_list.php');</script>";

    } elseif (isset($_POST['criteria'])) {
        $criteria = $_POST['criteria'];
        $criteria_id = $_POST['criteria_id'] ?? null;

        if ($criteria_id) {
            
            $query = "UPDATE criteria_list SET criteria = ? WHERE criteria_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$criteria, $criteria_id]);
            header('Location: criteria_list.php'); 
            exit;
        } else {
            
            $query = "INSERT INTO criteria_list (criteria) VALUES (?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$criteria]);
            header('Location: criteria_list.php'); 
            exit;
        }
    }
}


?>