<?php

include 'header.php';
include 'sidebar.php';
include 'footer.php';
include '../database/connection.php';

$id = isset($_POST['id']) ? $_POST['id'] : null;
$criterias = null;

// Fetch all criterias
$stmt = $conn->prepare('SELECT * FROM criteria_list');
$stmt->execute();
$criterias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If editing, fetch specific criteria
if ($id) {
    $stmt = $conn->prepare("SELECT * FROM criteria_list WHERE criteria_id = ?");
    $stmt->execute([$id]);
    $criterias = $stmt->fetch();
}

// Check if form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete_id'])) {
    $criteria = $_POST['criteria'];

    // If ID exists, update the criteria; otherwise, insert a new one
    if ($id) {
        $query = "UPDATE criteria_list SET criteria = ? WHERE criteria_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$criteria, $id]);
    } else {
        $query = "INSERT INTO criteria_list (criteria) VALUES (?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$criteria]);
    }

    // Commit and close the connection
    $conn = null;

    // Flash message for success
    $_SESSION['flash_message'] = 'Data successfully saved.';
    $_SESSION['flash_type'] = 'success';

    // Redirect to criteria list
    echo "<script>window.location.replace('criteria_list.php');</script>";
    exit;
}

if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Prepare and execute the delete statement
    // Ensure that the column name matches your database schema
    $stmt = $conn->prepare('DELETE FROM criteria_list WHERE criteria_id = :id');
    $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Criteria is deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting criteria.');</script>";
    }

    echo "<script>window.location.replace('criteria_list.php');</script>";
}

$conn = null;

?>
