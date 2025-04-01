<?php
include 'database/connection.php';

if (isset($_POST['subject_code'], $_POST['subject_name'], $_POST['course_code'])) {
    $subject_code = trim($_POST['subject_code']);
    $subject_name = trim($_POST['subject_name']);
    $course_code = trim($_POST['course_code']);

    // $check = $conn->prepare("SELECT * FROM subjects WHERE subject_code = ?");
    // $check->execute([$subject_code]);

    // if ($check->rowCount() > 0) {
    //     echo json_encode(["status" => "exists"]);
    //     exit;
    // }

    $stmt = $conn->prepare("INSERT INTO subjects_course (subject_code, subject_name, course_code) VALUES (?, ?, ?)");
    if ($stmt->execute([$subject_code, $subject_name, $course_code])) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }
}
?>
