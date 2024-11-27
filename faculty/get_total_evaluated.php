<?php
session_start();
include '../database/connection.php';

if (!isset($_SESSION['user']['faculty_id'])) {
    echo 'No faculty ID in session.';
    exit;
}

$facultyId = intval($_SESSION['user']['faculty_id']);

$stmt = $conn->prepare("
    SELECT COUNT(DISTINCT student_id) 
    FROM evaluation_answers 
    WHERE faculty_id = :faculty_id
");
$stmt->bindParam(':faculty_id', $facultyId, PDO::PARAM_INT);
$stmt->execute();

$totalStudentsEvaluated = $stmt->fetchColumn();
echo $totalStudentsEvaluated ?: 0;
?>
