<?php

include 'header.php';
include 'sidebar.php';
include 'footer.php';
include '../database/connection.php';

// $id = isset($_POST['id']) ? $_POST['id'] : null;
// $questionnaires = null;

$stmt = $conn->prepare('SELECT * FROM academic_list');
$stmt->execute();
$questionnaires = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Check if form is submitted via POST
