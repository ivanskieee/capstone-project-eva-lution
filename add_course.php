<?php
include 'database/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_code']) && isset($_POST['course_name'])) {
    $course_code = strtolower(trim($_POST['course_code'])); 
    $course_name = ucwords(strtolower(trim($_POST['course_name']))); 
    $course_name = preg_replace('/\bOf\b/', 'of', $course_name); 

    try {
        $stmt = $conn->prepare("SELECT * FROM courses WHERE course_code = :code OR course_name = :name");
        $stmt->bindParam(':code', $course_code);
        $stmt->bindParam(':name', $course_name);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "error", "message" => "Course already exists!"]);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO courses (course_code, course_name) VALUES (:code, :name)");
        $stmt->bindParam(':code', $course_code);
        $stmt->bindParam(':name', $course_name);
        $stmt->execute();

        echo json_encode([
            "status" => "success",
            "course_code" => $course_code,
            "course_name" => $course_name
        ]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    }
}
?>
