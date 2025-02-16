<?php
include '../database/connection.php';

if (isset($_POST['department_code'])) {
    $department_code = $_POST['department_code'];

    $stmt = $conn->prepare("SELECT subject_code, subject_name FROM subjects WHERE department_code = ? ORDER BY subject_name ASC");
    $stmt->execute([$department_code]);
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($subjects);
}
?>
