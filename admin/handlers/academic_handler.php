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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete_id'])) {
    $year = $_POST['year'];
    $semester = $_POST['semester'];

    
    if ($id) {
        $query = "UPDATE academic_list SET year = ?, semester = ? WHERE academic_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$year, $semester, $id]);
    } else {
        $query = "INSERT INTO academic_list (year, semester) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$year, $semester]);
    }

    $conn = null;
   
    $_SESSION['flash_message'] = 'Data successfully saved.';
    $_SESSION['flash_type'] = 'success';

    echo "<script>window.location.replace('academic_list.php');</script>";

    exit;
}

if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $stmt = $conn->prepare('DELETE FROM academic_list WHERE academic_id = :id');
    $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Academic year is deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting academic year.');</script>";
    }

    echo "<script>window.location.replace('academic_list.php');</script>";
}

$conn = null;

?>