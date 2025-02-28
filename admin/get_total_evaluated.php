<?php
// Database connection
include '../database/connection.php'; // Adjust the path to your database connection file

if (isset($_GET['faculty_id']) && isset($_GET['category'])) {
    $facultyId = intval($_GET['faculty_id']);
    $category = $_GET['category'];

    $totalCount = 0;

    if ($category === 'self') {
        // Count from self_faculty_eval
        $stmt = $conn->prepare("SELECT COUNT(faculty_id) FROM self_faculty_eval WHERE faculty_id = :faculty_id");
        $stmt->bindParam(':faculty_id', $facultyId, PDO::PARAM_INT);
        $stmt->execute();
        $totalCount = $stmt->fetchColumn();
    } elseif ($category === 'dean_self') {
        // Count from self_head_eval
        $stmt = $conn->prepare("SELECT COUNT(faculty_id) FROM self_head_eval WHERE faculty_id = :faculty_id");
        $stmt->bindParam(':faculty_id', $facultyId, PDO::PARAM_INT);
        $stmt->execute();
        $totalCount = $stmt->fetchColumn();
    } elseif ($category === 'faculty_faculty') {
        $stmt = $conn->prepare("
            SELECT COUNT(DISTINCT CONCAT(faculty_id, '-', academic_id, '-', evaluator_id)) 
            FROM evaluation_answers_faculty_faculty 
            WHERE faculty_id = :faculty_id 
            AND rate IS NOT NULL
        ");

        $stmt->bindParam(':faculty_id', $facultyId, PDO::PARAM_INT);
        $stmt->execute();
        $totalCount = $stmt->fetchColumn();
    } elseif ($category === 'head_faculty') {
        $stmt = $conn->prepare("
            SELECT COUNT(DISTINCT CONCAT(faculty_id, '-', academic_id, '-', evaluator_id)) 
            FROM evaluation_answers_dean_faculty 
            WHERE faculty_id = :faculty_id 
            AND rate IS NOT NULL
        ");
        $stmt->bindParam(':faculty_id', $facultyId, PDO::PARAM_INT);
        $stmt->execute();
        $totalCount = $stmt->fetchColumn();
    } elseif ($category === 'faculty_head') {
        $stmt = $conn->prepare("SELECT COUNT(DISTINCT CONCAT(faculty_id, '-', academic_id, '-', evaluator_id)) FROM evaluation_answers_faculty_dean WHERE faculty_id = :faculty_id AND rate IS NOT NULL");
        $stmt->bindParam(':faculty_id', $facultyId, PDO::PARAM_INT);
        $stmt->execute();
        $totalCount = $stmt->fetchColumn();
    } else {
        $stmt = $conn->prepare("SELECT COUNT(DISTINCT student_id) FROM evaluation_answers WHERE faculty_id = :faculty_id");
        $stmt->bindParam(':faculty_id', $facultyId, PDO::PARAM_INT);
        $stmt->execute();
        $totalCount = $stmt->fetchColumn();
    }

    echo $totalCount ?: 0; 
}
?>