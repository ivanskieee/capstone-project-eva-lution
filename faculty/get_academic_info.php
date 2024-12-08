<?php
session_start();
include '../database/connection.php';

// Check if the session contains the faculty ID
if (!isset($_SESSION['user']['faculty_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No faculty ID in session.']);
    exit;
}

$facultyId = intval($_SESSION['user']['faculty_id']); // Get faculty_id from the session

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
$stmt->bindParam(':faculty_id', $facultyId, PDO::PARAM_INT);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    // Respond with year and semester
    echo json_encode([
        'status' => 'success',
        'academic_id' => $result['academic_id'],
        'year' => $result['year'],
        'semester' => $result['semester']
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No academic information found for the faculty ID.']);
}
?>
