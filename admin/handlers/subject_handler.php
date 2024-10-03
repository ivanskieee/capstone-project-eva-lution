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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'];
    $subject = $_POST['subject'];
    $description = $_POST['description'];

    if ($id) {
        $query = "UPDATE subject_list SET code = ?, subject = ?, description = ? WHERE subject_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$code, $subject, $description, $id]);
    } else {
        $query = "INSERT INTO subject_list (code, subject, description) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$code, $subject, $description]);
    }

    $conn = null;

    $_SESSION['flash_message'] = 'Data successfully saved.';
    $_SESSION['flash_type'] = 'success';

    echo "<script>window.location.replace('subject_list.php');</script>";

    exit;
}


