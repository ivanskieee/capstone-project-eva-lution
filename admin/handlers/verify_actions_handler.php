<?php

include '../database/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $action = $_POST['action'];

    if ($action == 'confirm') {
        $query = "INSERT INTO student_list (email, password) 
                  SELECT email, password 
                  FROM pending_students WHERE student_id = :student_id";
        $stmt = $conn->prepare($query);
        $stmt->execute(['student_id' => $student_id]);

        $query = "DELETE FROM pending_students WHERE student_id = :student_id";
        $stmt = $conn->prepare($query);
        $stmt->execute(['student_id' => $student_id]);

        header('Location: verify_accounts.php?status=confirmed');
        exit(); 
    } elseif ($action == 'remove') {
        $query = "DELETE FROM pending_students WHERE student_id = :student_id";
        $stmt = $conn->prepare($query);
        $stmt->execute(['student_id' => $student_id]);

        header('Location: verify_accounts.php?status=rejected');
        exit(); 
    }
}
