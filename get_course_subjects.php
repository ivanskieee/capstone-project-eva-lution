<?php
include 'database/connection.php';

if (isset($_POST['course_code'])) {
    $course_code = $_POST['course_code'];

    // Step 1: Get department codes from subjects linked to the selected course
    $stmt = $conn->prepare("
        SELECT DISTINCT s.department_code 
        FROM subjects_course sc
        JOIN subjects s ON sc.subject_code = s.subject_code
        WHERE sc.course_code = ?
    ");
    $stmt->execute([$course_code]);
    $departments = $stmt->fetchAll(PDO::FETCH_COLUMN); // Get department codes

    if (empty($departments)) {
        echo json_encode([]);
        exit;
    }

    // Step 2: Fetch all subjects from the retrieved department(s)
    $placeholders = implode(',', array_fill(0, count($departments), '?'));
    $stmt2 = $conn->prepare("
        SELECT subject_code, subject_name 
        FROM subjects 
        WHERE department_code IN ($placeholders) 
        ORDER BY subject_name ASC
    ");
    $stmt2->execute($departments);
    $subjects = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($subjects);
}
?>
