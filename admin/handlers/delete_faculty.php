<?php
include '../database/connection.php';

// Check if delete_id is set in the POST request
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Prepare and execute the delete statement
    // Ensure that the column name matches your database schema
    $stmt = $conn->prepare('DELETE FROM college_faculty_list WHERE faculty_id = :faculty_id');
    $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Faculty deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting faculty.');</script>";
    }

    echo "<script>window.location.replace('tertiary_faculty_list.php');</script>";
}

$conn = null; // Close the connection
?>
