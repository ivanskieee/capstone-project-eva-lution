<?php
// Database connection
include '../database/connection.php'; // Adjust the path to your database connection file

if (isset($_GET['faculty_id'])) {
    $facultyId = intval($_GET['faculty_id']);

    // Fetch the count of students evaluated for the selected faculty
    $stmt = $conn->prepare("
        SELECT COUNT(DISTINCT student_id) 
        FROM evaluation_answers 
        WHERE faculty_id = :faculty_id
    ");
    $stmt->bindParam(':faculty_id', $facultyId, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the total count
    $totalStudentsEvaluated = $stmt->fetchColumn();

    // Return the result
    echo $totalStudentsEvaluated ?: 0; // Default to 0 if no evaluations
}
?>
