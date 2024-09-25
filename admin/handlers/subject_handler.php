<?php
include 'header.php';
include 'sidebar.php';
include 'footer.php';
include '../database/connection.php';

$id = isset($_POST['id']) ? $_POST['id'] : null;
$subjects = null;

$stmt = $conn->prepare('SELECT * FROM subject_list');
$stmt->execute();
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($id) {
    $stmt = $conn->prepare("SELECT * FROM subject_list WHERE subject_id = ?");
    $stmt->execute([$subject_id]);
    $subjects = $stmt->fetch();
}
// Check if form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'];
    $subject = $_POST['subject'];
    $description = $_POST['description'];

    // If ID exists, update the class; otherwise, insert a new class
    if ($id) {
        $query = "UPDATE subject_list SET code = ?, subject = ?, description = ? WHERE subject_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$code, $subject, $description, $id]);
    } else {
        $query = "INSERT INTO subject_list (code, subject, description) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$code, $subject, $description]);
    }

    // Commit and close the connection
    $conn = null;

    // Flash message for success
    $_SESSION['flash_message'] = 'Data successfully saved.';
    $_SESSION['flash_type'] = 'success';

    // Redirect to class list
    echo "<script>window.location.replace('subject_list.php');</script>";

    exit;
}


