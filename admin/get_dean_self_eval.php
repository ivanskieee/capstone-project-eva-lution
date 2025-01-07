<?php
include '../database/connection.php';

if (isset($_GET['faculty_id'])) {
    $faculty_id = intval($_GET['faculty_id']); // Sanitize input

    // Fetch self-evaluation data
    $stmt = $conn->prepare("
        SELECT 
            skills, 
            performance, 
            comments 
        FROM self_head_eval 
        WHERE faculty_id = :faculty_id
    ");
    $stmt->execute(['faculty_id' => $faculty_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'skills' => $result['skills'],
            'performance' => $result['performance'],
            'comments' => $result['comments']
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No self-evaluation data found for this faculty.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid faculty ID.']);
}
?>
