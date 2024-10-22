<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: ../index.php');
    exit;
}

if ($_SESSION['user']['role'] !== 'student') {
    header('Location: unauthorized.php');
    exit;
}

include 'header.php';
include 'sidebar.php';
include '../database/connection.php';

// Fetch criteria list
$stmt = $conn->prepare('SELECT * FROM criteria_list');
$stmt->execute();
$criteriaList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch questions list
$stmt = $conn->prepare('SELECT * FROM question_list');
$stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $evaluation_id = $_POST['evaluation_id'];
    $question_ids = $_POST['question_id']; // This is now an array
    $ratings = $_POST['rate']; // Renamed for clarity

    // Prepare the SQL statement
    $stmt = $conn->prepare('INSERT INTO evaluation_answers (evaluation_id, question_id, rate) VALUES (?, ?, ?)');

    $success = true; // Variable to track overall success

    foreach ($question_ids as $question_id) {
        // Make sure to get the corresponding rating for the current question_id
        if (isset($ratings[$question_id])) {
            $rate = $ratings[$question_id];

            // Execute the prepared statement
            if (!$stmt->execute([$evaluation_id, $question_id, $rate])) {
                $success = false; // Set success to false if an insertion fails
                // Optionally log the error message or handle it
            }
        }
    }

    if ($success) {
        echo json_encode(['status' => 'success', 'message' => 'Answers inserted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Some answers could not be inserted.']);
    }
}

?>