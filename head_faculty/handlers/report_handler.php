<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: ../index.php');
    exit;
}

if ($_SESSION['user']['role'] !== 'head_faculty') {
    header('Location: unauthorized.php');
    exit;
}

include 'header.php';
include 'sidebar.php';
include 'footer.php';
include '../database/connection.php';

$stmt = $conn->prepare('SELECT * FROM criteria_list');
$stmt->execute();
$criteriaList = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare('SELECT * FROM question_list');
$stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare('SELECT * FROM question_dean_faculty');
$stmt->execute();
$questions_faculty = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $evaluation_id = $_POST['evaluation_id'];
    $faculty_id = $_POST['faculty_id']; // Faculty being evaluated
    $head_id = $_SESSION['user']['head_id']; // Head as evaluator (from session)
    $question_ids = $_POST['question_id']; // Array of question IDs
    $ratings = $_POST['rate']; // Ratings for each question
    $comments = $_POST['comment']; // Comments for each question

    // Retrieve academic_id for the evaluator (head)
    $stmt = $conn->prepare('SELECT academic_id FROM head_faculty_list WHERE head_id = ?');
    $stmt->execute([$head_id]);
    $academic_id = $stmt->fetchColumn();

    if (!$academic_id) {
        echo json_encode(['status' => 'error', 'message' => 'Academic ID not found.']);
        exit;
    }

    // Ensure the evaluation target (faculty_id) is specified
    if (!$faculty_id) {
        echo json_encode(['status' => 'error', 'message' => 'No evaluation target (faculty) specified.']);
        exit;
    }

    // Check if the evaluation already exists for the target
    $stmt = $conn->prepare('SELECT COUNT(*) FROM evaluation_answers WHERE faculty_id = ? AND evaluator_id = ? AND academic_id = ?');
    $stmt->execute([$faculty_id, $head_id, $academic_id]);
    $evaluationExists = $stmt->fetchColumn();

    if ($evaluationExists > 0) {
        echo json_encode(['status' => 'error', 'message' => 'You have already submitted your evaluation for this faculty.']);
        exit;
    }

    // Prepare SQL statement for inserting answers
    $stmt = $conn->prepare('INSERT INTO evaluation_answers (evaluation_id, faculty_id, evaluator_id, academic_id, question_id, rate, comment) VALUES (?, ?, ?, ?, ?, ?, ?)');

    $success = true;

    foreach ($question_ids as $question_id) {
        if (isset($ratings[$question_id]) && !empty($ratings[$question_id])) {
            $rate = $ratings[$question_id];
            $comment = isset($comments[$question_id]) ? $comments[$question_id] : '';

            if (!$stmt->execute([$evaluation_id, $faculty_id, $head_id, $academic_id, $question_id, $rate, $comment])) {
                $success = false;
            }
        } elseif (isset($comments[$question_id]) && !empty($comments[$question_id])) {
            $comment = $comments[$question_id];

            if (!$stmt->execute([$evaluation_id, $faculty_id, $head_id, $academic_id, $question_id, null, $comment])) {
                $success = false;
            }
        }
    }

    if ($success) {
        echo json_encode(['status' => 'success', 'message' => 'Evaluation submitted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Some answers could not be saved.']);
    }
}


