<?php
include 'database/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $section = $_POST['section'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO pending_students (student_id, email, subject, section, password) 
              VALUES (:student_id, :email, :subject, :section, :password)";
    
    $stmt = $conn->prepare($query);
    
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':subject', $subject);
    $stmt->bindParam(':section', $section);
    $stmt->bindParam(':password', $password);

    if ($stmt->execute()) {
        echo "Your registration is pending approval.";
    } else {
        echo "There was an error with your registration.";
    }
}
