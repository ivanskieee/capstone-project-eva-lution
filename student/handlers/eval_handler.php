<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: ../index.php');
    exit;
}

if ($_SESSION['user']['role'] !== 'student') {
    // Redirect to an unauthorized page or login page if they don't have the correct role
    header('Location: unauthorized.php');
    exit;
}

include 'header.php';
include 'sidebar.php';
include '../database/connection.php';


$stmt = $conn->prepare('SELECT * FROM criteria_list');
$stmt->execute();
$criteriaList = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare('SELECT * FROM question_list');
$stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure all required fields are set
    if (isset($_POST['evaluation_id'], $_POST['qid'], $_POST['rate'])) {
        $evaluation_id = $_POST['evaluation_id'];
        $qid = $_POST['qid']; // Array of question IDs
        $rate = $_POST['rate']; // Associative array of rates

        // Prepare your SQL statement
        $stmt = $conn->prepare("INSERT INTO evaluations (evaluation_id, question_id, rate) VALUES (:evaluation_id, :question_id, :rate)");

        // Loop through each question and insert
        foreach ($qid as $question_id) {
            $stmt->execute([
                ':evaluation_id' => $evaluation_id,
                ':question_id' => $question_id,
                ':rate' => $rate[$question_id], // Get the corresponding rate for the question
            ]);
        }

        // Return success response
        echo 1;
        exit;
    } else {
        echo "Required fields are missing.";
        exit;
    }
}



?>