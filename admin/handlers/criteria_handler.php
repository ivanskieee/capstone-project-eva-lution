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
    // Check if updating or deleting
    if (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];
        $stmt = $conn->prepare('DELETE FROM criteria_list WHERE criteria_id = :id');
        $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Criteria deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting criteria.']);
        }
        
        exit; 
        
    } elseif (isset($_POST['criteria'])) {
        $criteria = $_POST['criteria'];
        $criteria_id = $_POST['criteria_id'] ?? null;

        if ($criteria_id) {
            // Update existing criteria
            $query = "UPDATE criteria_list SET criteria = ? WHERE criteria_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$criteria, $criteria_id]);
            echo json_encode(['success' => true, 'message' => 'Criteria updated successfully.']);
        } else {
            // Insert new criteria
            $query = "INSERT INTO criteria_list (criteria) VALUES (?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$criteria]);
            echo json_encode(['success' => true, 'message' => 'Criteria added successfully.']);
        }
        exit;
    }
}


?>