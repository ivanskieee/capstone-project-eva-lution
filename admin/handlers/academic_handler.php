<?php

include 'header.php';
include 'sidebar.php';
include 'footer.php';
include '../database/connection.php';

$id = isset($_POST['id']) ? $_POST['id'] : null;
$academics = null;

$stmt = $conn->prepare('SELECT * FROM academic_list');
$stmt->execute();
$academics = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($id) {
    $stmt = $conn->prepare("SELECT * FROM academic_list WHERE academic_id = ?");
    $stmt->execute([$academic_id]);
    $academics = $stmt->fetch();
}
// Check if form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $year = $_POST['year'];
    $semester = $_POST['semester'];

    // If ID exists, update the class; otherwise, insert a new class
    if ($id) {
        $query = "UPDATE academic_list SET year = ?, semester = ? WHERE academic_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$year, $semester, $id]);
    } else {
        $query = "INSERT INTO academic_list (year, semester) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$year, $semester]);
    }

    // Commit and close the connection
    $conn = null;

    // Flash message for success
    $_SESSION['flash_message'] = 'Data successfully saved.';
    $_SESSION['flash_type'] = 'success';

    // Redirect to class list
    echo "<script>window.location.replace('academic_list.php');</script>";

    exit;
}

?>