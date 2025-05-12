<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: ../index.php');
    exit;
}

if ($_SESSION['user']['role'] !== 'faculty') {
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

$stmt = $conn->prepare('SELECT * FROM question_faculty_faculty');
$stmt->execute();
$questions_faculties = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare('SELECT * FROM question_faculty_dean');
$stmt->execute();
$questions_deans = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $evaluation_id = $_POST['evaluation_id'];
    $faculty_id = $_POST['faculty_id'] ?? null; 
    $head_id = $_POST['head_id'] ?? null; 
    $evaluator_id = $_SESSION['user']['faculty_id']; 
    $question_ids = $_POST['question_id']; 
    $ratings = $_POST['rate']; 
    $comments = $_POST['comment']; 

    $stmt = $conn->prepare('SELECT academic_id FROM college_faculty_list 
    WHERE faculty_id = ?');
    $stmt->execute([$evaluator_id]);
    $academic_id = $stmt->fetchColumn();

    if (!$academic_id) {
        echo json_encode(['status' => 'error', 'message' => 
        'Academic ID not found.']);
        exit;
    }

    if (!$faculty_id && !$head_id) {
        echo json_encode(['status' => 'error', 'message' => 
        'No evaluation target specified.']);
        exit;
    }

    $target_column = $faculty_id ? 'faculty_id' : 'head_id';
    $target_id = $faculty_id ?? $head_id;

    $stmt = $conn->prepare("SELECT COUNT(*) FROM evaluation_answers_faculty_faculty 
    WHERE $target_column = ? AND evaluator_id = ? AND academic_id = ?");
    $stmt->execute([$target_id, $evaluator_id, $academic_id]);
    $evaluationExists = $stmt->fetchColumn();

    if ($evaluationExists > 0) {
        echo json_encode(['status' => 'error', 'message' => 
        'You have already submitted your evaluation for this target.']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO evaluation_answers_faculty_faculty 
    (evaluation_id, $target_column, evaluator_id, academic_id, 
    question_id, rate, comment) VALUES (?, ?, ?, ?, ?, ?, ?)");

    $success = true;

    foreach ($question_ids as $question_id) {
        if (isset($ratings[$question_id]) && 
            !empty($ratings[$question_id])) {
            $rate = $ratings[$question_id];
            $comment = isset($comments[$question_id]) 
            ? $comments[$question_id] : '';

            if (!$stmt->execute([$evaluation_id, $target_id, 
            $evaluator_id, $academic_id, $question_id, 
            $rate, $comment])) {
                $success = false;
            }
        } elseif (isset($comments[$question_id]) && 
            !empty($comments[$question_id])) {
            $comment = $comments[$question_id];

            if (!$stmt->execute([$evaluation_id, $target_id, 
                $evaluator_id, $academic_id, $question_id, null, $comment])) {
                $success = false;
            }
        }
    }

    if ($success) {
        echo json_encode(['status' => 'success', 
        'message' => 'Evaluation submitted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 
        'message' => 'Some answers could not be saved.']);
    }
}
