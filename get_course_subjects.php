<?php
include 'database/connection.php';

if (isset($_POST['course_code'])) {
    $course_code = $_POST['course_code'];

    $stmt = $conn->prepare("SELECT subject_code, subject_name FROM subjects_course WHERE course_code = ? ORDER BY subject_name ASC");
    $stmt->execute([$course_code]);
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($subjects);
}
?>