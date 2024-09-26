<?php

include 'header.php';
include 'sidebar.php';
include 'footer.php';
include '../database/connection.php';

$stmt = $conn->prepare('SELECT * FROM question_list');
$stmt->execute();
$questionnaires = $stmt->fetchAll(PDO::FETCH_ASSOC);