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

$id = isset($_GET['academic_id']) ? $_GET['academic_id'] : null;

$stmt = $conn->prepare('SELECT * FROM academic_list');
$stmt->execute();
$academics = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM academic_list WHERE academic_id = ?");
    $stmt->execute([$id]);
    $academics = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];
        $stmt = $conn->prepare('DELETE FROM academic_list WHERE academic_id = :id');
        $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['flash_message'] = 'Academic year deleted successfully.';
            $_SESSION['flash_type'] = 'success';
        } else {
            error_log('Error deleting academic year');
            $_SESSION['flash_message'] = 'Error deleting academic year. Please try again.';
            $_SESSION['flash_type'] = 'error';
        }

        echo "<script>window.location.replace('academic_list.php');</script>";
    } 
    elseif (isset($_POST['year'], $_POST['semester'])) {
        $year = $_POST['year'];
        $semester = $_POST['semester'];
        $id = $_POST['academic_id'] ?? null;

        if ($id) {
            $query = "UPDATE academic_list SET year = ?, semester = ? WHERE academic_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$year, $semester, $id]);
        } else {
            $query = "INSERT INTO academic_list (year, semester) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$year, $semester]);
        }

        $_SESSION['flash_message'] = 'Data successfully saved.';
        $_SESSION['flash_type'] = 'success';

        echo "<script>window.location.replace('academic_list.php');</script>";
        exit;
    } 
    elseif (isset($_POST['update_status'])) {
        $academic_id = $_POST['academic_id'];
        $status = $_POST['status'];

        if ($status == 1) { // Starting Evaluation
            // Set the start and end dates
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d', strtotime('+1 year')); // Adjust duration as needed

            // Reset other evaluations to "Not Yet Started"
            $conn->query('UPDATE academic_list SET status = 0 WHERE status != 2');

            // Update the selected academic year
            $stmt = $conn->prepare('UPDATE academic_list SET status = ?, start_date = ?, end_date = ? WHERE academic_id = ?');
            $stmt->execute([$status, $start_date, $end_date, $academic_id]);
        } elseif ($status == 2) { // Closing Evaluation
            // Update the selected academic year to closed
            $stmt = $conn->prepare('UPDATE academic_list SET status = ?, start_date = NULL, end_date = NULL WHERE academic_id = ?');
            $stmt->execute([$status, $academic_id]);
        }

        echo json_encode(['success' => true]);
        exit;
    }
}

$conn = null;

?>
