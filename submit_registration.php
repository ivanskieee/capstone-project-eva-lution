<?php
include 'database/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $school_id = $_POST['school_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $course = $_POST['course'];
    $subjects = isset($_POST['subjects']) ? implode(", ", $_POST['subjects']) : '';
    $section = $_POST['section'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO pending_students (school_id, firstname, lastname, email, course, subject, section, password) 
              VALUES (:school_id, :firstname, :lastname, :email, :course, :subject, :section, :password)";
    
    $stmt = $conn->prepare($query);

    $stmt->bindParam(':school_id', $school_id);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':course', $course);
    $stmt->bindParam(':subject', $subjects); 
    $stmt->bindParam(':section', $section);
    $stmt->bindParam(':password', $password);

    if ($stmt->execute()) {
        header('Location: pending_approval.php'); 
        exit();
    } else {
        header('Location: error.php'); 
        exit();
    }
}

?>
