<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: ../index.php');
    exit;
}

if ($_SESSION['user']['role'] !== 'admin') {
    // Redirect to an unauthorized page or login page if they don't have the correct role
    header('Location: unauthorized.php');
    exit;
}

include 'header.php';
include 'sidebar.php';
include 'footer.php';
include '../database/connection.php';

$id = isset($_GET['question_id']) ? $_GET['question_id'] : null;

$stmt = $conn->prepare('SELECT * FROM academic_list');
$stmt->execute();
$questionnaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['submit_question'])) {
    $criteria_id = $_POST['criteria_id'];
    $question = $_POST['question'];

    if (!empty($criteria_id) && !empty($question)) {
        $stmt = $conn->prepare('INSERT INTO question_list (criteria_id, question) VALUES (:criteria_id, :question)');
        $stmt->bindParam(':criteria_id', $criteria_id);
        $stmt->bindParam(':question', $question);

        if ($stmt->execute()) {
            echo "<p>Question successfully submitted.</p>";
        } else {
            echo "<p>Failed to submit question.</p>";
        }
    }
}

$stmt = $conn->prepare('SELECT * FROM criteria_list');
$stmt->execute();
$criteriaList = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare('SELECT * FROM question_list');
$stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// $id = isset($_GET['criteria_id']) ? $_GET['criteria_id'] : null;

// if ($id) {
//     // Fetch questions only if a criteria is selected
//     $stmt = $conn->prepare('SELECT * FROM question_list WHERE criteria_id = :id');
//     $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Ensure it's treated as an integer
//     $stmt->execute();
//     $questionz = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results
// } else {
//     $questionz = []; // Default to empty array if no criteria selected
// }

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM question_list WHERE question_id = ?");
    $stmt->execute([$id]);
    $questions = $stmt->fetch(PDO::FETCH_ASSOC);
}

// if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['delete_id'])) {
//     $criteria_id = $_POST['criteria_id'];
//     $question = $_POST['question'];

//     $stmt = $conn->prepare('INSERT INTO question_list (criteria_id, question) VALUES (:criteria_id, :question)');
//     $stmt->bindParam(':criteria_id', $criteria_id, PDO::PARAM_INT);
//     $stmt->bindParam(':question', $question, PDO::PARAM_STR);
//     $stmt->execute();

//      $conn = null;

//      $_SESSION['flash_message'] = 'Data successfully saved.';
//      $_SESSION['flash_type'] = 'success';
 
//      echo "<script>window.location.replace('manage_questionnaire.php');</script>";
//      exit;
 
// }
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete_id'])) {
    $criteria_id = $_POST['criteria_id'];  // or '$id' if you're using 'id' instead
    $question = $_POST['question'];

    if ($id) {
        // Update the question if the criteria_id already exists
        $query = "UPDATE question_list SET question = ? WHERE criteria_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$question, $criteria_id]);
    } else {
        // Insert new question if there's no criteria_id
        $query = "INSERT INTO question_list (criteria_id, question) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$criteria_id, $question]);
    }

    $conn = null;

    $_SESSION['flash_message'] = 'Data successfully saved.';
    $_SESSION['flash_type'] = 'success';

    echo "<script>window.location.replace('manage_questionnaire.php');</script>";
    exit;
}


if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $stmt = $conn->prepare('DELETE FROM question_list WHERE question_id = :id');
    $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Question is deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting question.');</script>";
    }

    echo "<script>window.location.replace('manage_questionnaire.php');</script>";
}

$conn = null;


?>
