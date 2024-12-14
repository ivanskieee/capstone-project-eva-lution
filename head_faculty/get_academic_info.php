<?php
include '../database/connection.php';

if (isset($_GET['faculty_id'])) {
    $faculty_id = intval($_GET['faculty_id']); // Sanitize the input

    // Prepare the SQL query to fetch academic year and semester
    $stmt = $conn->prepare("
        SELECT 
            al.academic_id,
            al.year,
            al.semester
        FROM academic_list al
        INNER JOIN college_faculty_list cf ON al.academic_id = cf.academic_id
        WHERE cf.faculty_id = :faculty_id
        LIMIT 1
    ");
    $stmt->execute(['faculty_id' => $faculty_id]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'academic_id' => $result['academic_id'],
            'year' => $result['year'],
            'semester' => $result['semester']
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'No academic information found for the provided faculty ID.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid or missing faculty ID.'
    ]);
}
?>
