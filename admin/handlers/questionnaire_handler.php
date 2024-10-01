<?php

include 'header.php';
include 'sidebar.php';
include 'footer.php';
include '../database/connection.php';

$id = isset($_POST['id']) ? $_POST['id'] : null;
$questionz = null;

$stmt = $conn->prepare('SELECT * FROM academic_list');
$stmt->execute();
$questionnaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare('SELECT * FROM criteria_list');
$stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare('SELECT * FROM question_list');
$stmt->execute();
$questionz = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($id) {
    $stmt = $conn->prepare("SELECT * FROM question_list WHERE question_id = ?");
    $stmt->execute([$id]);
    $questionz = $stmt->fetch();
}

// Check if form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = $_POST['question'];

    // If ID exists, update the criteria; otherwise, insert a new one
    if ($id) {
        $query = "UPDATE question_list SET question = ? WHERE question_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$question, $id]);
    } else {
        $query = "INSERT INTO question_list (question) VALUES (?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$question]);
    }

    // Commit and close the connection
    $conn = null;

    // Flash message for success
    $_SESSION['flash_message'] = 'Data successfully saved.';
    $_SESSION['flash_type'] = 'success';

    // Redirect to criteria list
    echo "<script>window.location.replace('manage_questionnaire.php');</script>";
    exit;
}


?>
