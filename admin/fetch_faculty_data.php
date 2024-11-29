<?php
include '../database/connection.php';

if (isset($_GET['faculty_id'])) {
    $facultyId = $_GET['faculty_id'];

    // Query for ratings data
    $stmt = $conn->prepare("
        SELECT ea.rate AS rating
        FROM evaluation_answers ea
        WHERE ea.faculty_id = :faculty_id
        ORDER BY ea.evaluation_id ASC
    ");
    $stmt->execute(['faculty_id' => $facultyId]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Create labels as sequential numbers (1, 2, 3, ...)
    $labels = range(1, count($data));
    $ratings = array_column($data, 'rating');

    echo json_encode(['labels' => $labels, 'dataset' => $ratings]);
}
?>
