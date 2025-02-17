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
include 'audit_log.php'; // Include audit log function

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
    $user_id = $_SESSION['user']['id']; // Get logged-in admin ID for logging

    if (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];
        
        // Fetch criteria name before deletion for logging
        $stmt = $conn->prepare("SELECT criteria FROM criteria_list WHERE criteria_id = ?");
        $stmt->execute([$delete_id]);
        $criteria_name = $stmt->fetchColumn();

        $stmt = $conn->prepare('DELETE FROM criteria_list WHERE criteria_id = :id');
        $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            log_action($conn, $user_id, 'Deleted Criteria', "Deleted criteria ID: $delete_id (Criteria: $criteria_name)");
            $_SESSION['message'] = 'Criteria deleted successfully.';
        } else {
            error_log('Error deleting criteria');
            $_SESSION['error'] = 'Error deleting criteria. Please try again.';
        }
        
        echo "<script>window.location.replace('criteria_list.php');</script>";
        exit;

    } elseif (isset($_POST['criteria'])) {
        $criteria = $_POST['criteria'];
        $criteria_id = $_POST['criteria_id'] ?? null;

        if ($criteria_id) {
            // Fetch old criteria value before update for logging
            $stmt = $conn->prepare("SELECT criteria FROM criteria_list WHERE criteria_id = ?");
            $stmt->execute([$criteria_id]);
            $old_criteria = $stmt->fetchColumn();

            $query = "UPDATE criteria_list SET criteria = ? WHERE criteria_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$criteria, $criteria_id]);

            log_action($conn, $user_id, 'Updated Criteria', "Updated criteria ID: $criteria_id from '$old_criteria' to '$criteria'");
            $_SESSION['message'] = 'Criteria updated successfully.';

            header('Location: criteria_list.php'); 
            exit;
        } else {
            $query = "INSERT INTO criteria_list (criteria) VALUES (?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$criteria]);

            $newCriteriaId = $conn->lastInsertId(); // Get new ID for logging
            log_action($conn, $user_id, 'Added Criteria', "Added new criteria ID: $newCriteriaId (Criteria: $criteria)");
            $_SESSION['message'] = 'Criteria added successfully.';

            header('Location: criteria_list.php'); 
            exit;
        }
    }
}
?>
