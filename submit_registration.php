<?php
include 'database/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $school_id = $_POST['school_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $subjects = implode(',', $_POST['subjects']); // Join subjects array into a string
    $section = $_POST['section'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO pending_students (school_id, firstname, lastname, email, subject, section, password) 
              VALUES (:school_id, :firstname, :lastname, :email, :subject, :section, :password)";
    
    $stmt = $conn->prepare($query);

    $stmt->bindParam(':school_id', $school_id);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':subject', $subjects); // Bind the joined subjects string
    $stmt->bindParam(':section', $section);
    $stmt->bindParam(':password', $password);

    if ($stmt->execute()) {
        echo "Your registration is pending approval.";
    } else {
        echo "There was an error with your registration.";
    }
}
?>
