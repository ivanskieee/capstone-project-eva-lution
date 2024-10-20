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
$academic_id = isset($_GET['academic_id']) ? $_GET['academic_id'] : null;
$questionToEdit = null;

$stmt = $conn->prepare("
    SELECT a.*, 
           COUNT(q.academic_id) AS total_questions,
           COUNT(ea.evaluation_id) AS total_answers
    FROM academic_list a 
    LEFT JOIN question_list q ON a.academic_id = q.academic_id
    LEFT JOIN evaluation_answers ea ON a.academic_id = ea.evaluation_id 
    GROUP BY a.academic_id
");
$stmt->execute();
$questionnaires = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $conn->prepare('SELECT * FROM criteria_list');
$stmt->execute();
$criteriaList = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare('SELECT * FROM question_list WHERE academic_id = ?');
$stmt->execute([$academic_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($id) {
    $stmt = $conn->prepare('SELECT * FROM question_list WHERE question_id = ?');
    $stmt->execute([$id]);
    $questionToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure academic_id is received in both cases
    $academic_id = $_POST['academic_id']; // Get the academic_id from the form

    if (isset($_POST['delete_id'])) {
        // Deleting a question
        $delete_id = $_POST['delete_id'];
        $stmt = $conn->prepare('DELETE FROM question_list WHERE question_id = :id');
        $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Question deleted successfully.';
        } else {
            error_log('Error deleting question');
            $_SESSION['error'] = 'Error deleting question. Please try again.';
        }

        // Redirect to the same page with academic_id after deletion
        echo "<script>window.location.replace('manage_questionnaire.php?academic_id=" . $academic_id . "');</script>";
        exit();

    } elseif (isset($_POST['question'])) {
        // Updating or inserting a new question
        $criteria_id = $_POST['criteria_id'];
        $question = trim($_POST['question']);
        $id = $_POST['question_id'] ?? null; // Check if it's an update (existing question)

        if (!empty($criteria_id) && !empty($question) && !empty($academic_id)) { // Ensure academic_id is not empty
            if ($id) {
                // Update existing question
                $query = 'UPDATE question_list SET criteria_id = ?, question = ?, academic_id = ? WHERE question_id = ?';
                $stmt = $conn->prepare($query);
                $stmt->execute([$criteria_id, $question, $academic_id, $id]);
                $_SESSION['message'] = 'Question updated successfully.';
            } else {
                // Insert new question
                $query = 'INSERT INTO question_list (criteria_id, question, academic_id) VALUES (?, ?, ?)';
                $stmt = $conn->prepare($query);
                $stmt->execute([$criteria_id, $question, $academic_id]);
                $_SESSION['message'] = 'Question submitted successfully.';
            }

            // Redirect to the same page with academic_id after insert/update
            header('Location: manage_questionnaire.php?academic_id=' . $academic_id);
            exit;
        } else {
            $_SESSION['error'] = 'Please fill out all fields.';
        }
    }
}

// Close the database connection
$conn = null;


?>