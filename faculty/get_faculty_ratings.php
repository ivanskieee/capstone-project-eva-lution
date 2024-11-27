<?php
session_start();
include '../database/connection.php';

if (!isset($_SESSION['user']['faculty_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No faculty ID in session.']);
    exit;
}

$facultyId = intval($_SESSION['user']['faculty_id']);

// Fetch faculty name
$stmt = $conn->prepare("
    SELECT firstname, lastname 
    FROM college_faculty_list 
    WHERE faculty_id = :faculty_id
");
$stmt->bindParam(':faculty_id', $facultyId, PDO::PARAM_INT);
$stmt->execute();
$facultyData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$facultyData) {
    echo json_encode(['status' => 'error', 'message' => 'Faculty not found.']);
    exit;
}

$facultyName = $facultyData['firstname'] . ' ' . $facultyData['lastname'];

// Fetch ratings
$stmt = $conn->prepare("
    SELECT 
        q.question_id,
        q.question,
        COUNT(CASE WHEN ea.rate = 1 THEN 1 END) AS rate1,
        COUNT(CASE WHEN ea.rate = 2 THEN 1 END) AS rate2,
        COUNT(CASE WHEN ea.rate = 3 THEN 1 END) AS rate3,
        COUNT(CASE WHEN ea.rate = 4 THEN 1 END) AS rate4,
        COUNT(ea.rate) AS total_responses
    FROM question_list q
    LEFT JOIN evaluation_answers ea 
        ON q.question_id = ea.question_id AND ea.faculty_id = :faculty_id
    GROUP BY q.question_id, q.question
");
$stmt->bindParam(':faculty_id', $facultyId, PDO::PARAM_INT);
$stmt->execute();
$ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$response = [];
foreach ($ratings as $row) {
    $total = $row['total_responses'];
    $response[] = [
        'question' => $row['question'],
        'rate1' => $total ? round(($row['rate1'] / $total) * 100, 2) : 0,
        'rate2' => $total ? round(($row['rate2'] / $total) * 100, 2) : 0,
        'rate3' => $total ? round(($row['rate3'] / $total) * 100, 2) : 0,
        'rate4' => $total ? round(($row['rate4'] / $total) * 100, 2) : 0,
    ];
}

// Response
echo json_encode([
    'status' => 'success',
    'faculty_name' => $facultyName,
    'ratings' => $response
]);
?>
