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

$stmt = $conn->prepare('SELECT * FROM criteria_list');
$stmt->execute();
$criteriaList = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare('SELECT * FROM question_list');
$stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);