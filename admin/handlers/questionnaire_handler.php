<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: ../index.php');
    exit;
}

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: unauthorized.php');
    exit;
}

include 'header.php';
include 'sidebar.php';
include 'footer.php';
include '../database/connection.php';

$id = isset($_GET['question_id']) ? $_GET['question_id'] : null;
$questionToEdit = null;

$stmt = $conn->prepare('SELECT * FROM academic_list');
$stmt->execute();
$questionnaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare('SELECT * FROM criteria_list');
$stmt->execute();
$criteriaList = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare('SELECT * FROM question_list');
$stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($id) {
    $stmt = $conn->prepare('SELECT * FROM question_list WHERE question_id = ?');
    $stmt->execute([$id]);
    $questionToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];
        $stmt = $conn->prepare('DELETE FROM question_list WHERE question_id = :id');
        $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Question deleted successfully.';
        } else {
            error_log('Error deleting question');
            $_SESSION['error'] = 'Error deleting question. Please try again.';
        }

        echo "<script>window.location.replace('manage_questionnaire.php');</script>";

    } elseif (isset($_POST['question'])) {
        $criteria_id = $_POST['criteria_id'];
        $question = trim($_POST['question']);
        $id = $_POST['question_id'] ?? null;

        if (!empty($criteria_id) && !empty($question)) {
            if ($id) {
                // Update existing question
                $query = 'UPDATE question_list SET criteria_id = ?, question = ? WHERE question_id = ?';
                $stmt = $conn->prepare($query);
                $stmt->execute([$criteria_id, $question, $id]);
                $_SESSION['message'] = 'Question updated successfully.';
            } else {
                // Insert new question
                $query = 'INSERT INTO question_list (criteria_id, question) VALUES (?, ?)';
                $stmt = $conn->prepare($query);
                $stmt->execute([$criteria_id, $question]);
                $_SESSION['message'] = 'Question submitted successfully.';
            }
            header('Location: manage_questionnaire.php');
            exit;
        } else {
            $_SESSION['error'] = 'Please fill out all fields.';
        }
    }
}

$conn = null;


?>