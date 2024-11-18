<?php
include 'database/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $school_id = $_POST['school_id']; // Updated variable name
    $firstname = $_POST['firstname']; // New field
    $lastname = $_POST['lastname'];   // New field
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $section = $_POST['section'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO pending_students (school_id, firstname, lastname, email, subject, section, password) 
              VALUES (:school_id, :firstname, :lastname, :email, :subject, :section, :password)";
    
    $stmt = $conn->prepare($query);
    
    $stmt->bindParam(':school_id', $school_id); // Updated bindParam
    $stmt->bindParam(':firstname', $firstname); // Bind the firstname
    $stmt->bindParam(':lastname', $lastname);   // Bind the lastname
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
?>
