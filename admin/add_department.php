<?php
include '../database/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['department_code']) && isset($_POST['department_name'])) {
    $department_code = strtolower(trim($_POST['department_code'])); // Convert to lowercase
    $department_name = ucwords(strtolower(trim($_POST['department_name']))); // Capitalize first letter of each word
    $department_name = preg_replace('/\bOf\b/', 'of', $department_name); // Ensure "of" stays lowercase

    try {
        $stmt = $conn->prepare("SELECT * FROM departments WHERE department_code = :code OR department_name = :name");
        $stmt->bindParam(':code', $department_code);
        $stmt->bindParam(':name', $department_name);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "error", "message" => "Department already exists!"]);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO departments (department_code, department_name) VALUES (:code, :name)");
        $stmt->bindParam(':code', $department_code);
        $stmt->bindParam(':name', $department_name);
        $stmt->execute();

        echo json_encode([
            "status" => "success",
            "department_code" => $department_code,
            "department_name" => $department_name
        ]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    }
}
?>
