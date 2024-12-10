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
    $faculty_id = $_POST['faculty_id'];  // Assuming you have this from the form
    $student_id = $_SESSION['user']['student_id'];  // Fetch student_id from session
    $question_ids = $_POST['question_id']; // This is an array of question ids
    $ratings = $_POST['rate']; // Ratings for each question
    $comments = $_POST['comment']; // Comments for each question

    $stmt = $conn->prepare('SELECT academic_id FROM student_list WHERE student_id = ?');
    $stmt->execute([$student_id]);
    $academic_id = $stmt->fetchColumn();

    if (!$academic_id) {
        echo json_encode(['status' => 'error', 'message' => 'Academic ID not found.']);
        exit;
    }

    // Check if the evaluation has already been submitted for this faculty
    $stmt = $conn->prepare('SELECT COUNT(*) FROM evaluation_answers WHERE faculty_id = ? AND student_id = ?');
    $stmt->execute([$faculty_id, $student_id]);
    $evaluationExists = $stmt->fetchColumn();

    if ($evaluationExists > 0) {
        echo json_encode(['status' => 'error', 'message' => 'You have already submitted your evaluation for this faculty.']);
        exit;  // Stop further execution
    }

    // Prepare the SQL statement for inserting answers
    $stmt = $conn->prepare('INSERT INTO evaluation_answers (evaluation_id, faculty_id, student_id, academic_id, question_id, rate, comment) VALUES (?, ?, ?, ?, ?, ?, ?)');

    $success = true; // Variable to track overall success

    foreach ($question_ids as $question_id) {
        if (isset($ratings[$question_id]) && !empty($ratings[$question_id])) {
            // Insert ratings and comments for MCQ questions
            $rate = $ratings[$question_id];
            $comment = isset($comments[$question_id]) ? $comments[$question_id] : ''; // Get comment (if any)

            if (!$stmt->execute([$evaluation_id, $faculty_id, $student_id, $academic_id, $question_id, $rate, $comment])) {
                $success = false; // Set success to false if insertion fails
            }
        } elseif (isset($comments[$question_id]) && !empty($comments[$question_id])) {
            // Insert only comments for text-based questions (no rate)
            $comment = $comments[$question_id];

            if (!$stmt->execute([$evaluation_id, $faculty_id, $student_id, $academic_id, $question_id, null, $comment])) {
                $success = false; // Set success to false if insertion fails
            }
        }
    }

    if ($success) {
        echo json_encode(['status' => 'success', 'message' => 'Answers and comments inserted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Some answers could not be inserted.']);
    }
}

?>