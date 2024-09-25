<?php
include 'header.php';
include 'sidebar.php';
include 'footer.php';
include '../database/connection.php';

$id = isset($_POST['id']) ? $_POST['id'] : null;
$classes = null;

$stmt = $conn->prepare('SELECT * FROM class_list');
$stmt->execute();
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($id) {
    $stmt = $conn->prepare("SELECT * FROM class_list WHERE class_id = ?");
    $stmt->execute([$class_id]);
    $classes = $stmt->fetch();
}
// Check if form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course = $_POST['course'];
    $level = $_POST['level'];
    $section = $_POST['section'];

    // If ID exists, update the class; otherwise, insert a new class
    if ($id) {
        $query = "UPDATE class_list SET course = ?, level = ?, section = ? WHERE class_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$course, $level, $section, $id]);
    } else {
        $query = "INSERT INTO class_list (course, level, section) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$course, $level, $section]);
    }

    // Commit and close the connection
    $conn = null;

    // Flash message for success
    $_SESSION['flash_message'] = 'Data successfully saved.';
    $_SESSION['flash_type'] = 'success';

    // Redirect to class list
    echo "<script>window.location.replace('class_list.php');</script>";

    exit;
}


