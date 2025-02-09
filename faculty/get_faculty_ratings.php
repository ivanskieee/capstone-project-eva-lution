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

// Fetch criteria-wise ratings
$stmt = $conn->prepare("
    SELECT 
        q.criteria_id, -- Include criteria_id
        c.criteria, -- Fetch criteria name
        q.question_id,
        q.question,
        q.question_type, 
        COUNT(CASE WHEN ea.rate = 1 THEN 1 END) AS rate1,
        COUNT(CASE WHEN ea.rate = 2 THEN 1 END) AS rate2,
        COUNT(CASE WHEN ea.rate = 3 THEN 1 END) AS rate3,
        COUNT(CASE WHEN ea.rate = 4 THEN 1 END) AS rate4,
        COUNT(ea.rate) AS total_responses
    FROM question_list q
    LEFT JOIN evaluation_answers ea 
        ON q.question_id = ea.question_id AND ea.faculty_id = :faculty_id
    LEFT JOIN criteria_list c ON q.criteria_id = c.criteria_id -- Join with criteria table
    WHERE q.question_type = 'mcq' -- Only include MCQ questions
    GROUP BY q.criteria_id, c.criteria, q.question_id, q.question, q.question_type
");
$stmt->bindParam(':faculty_id', $facultyId, PDO::PARAM_INT);
$stmt->execute();
$ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organize data based on criteria
$criteriaRatings = [];
foreach ($ratings as $row) {
    $criteriaId = $row['criteria_id'];
    $criteriaName = $row['criteria'];

    if (!isset($criteriaRatings[$criteriaId])) {
        $criteriaRatings[$criteriaId] = [
            'criteria' => $criteriaName,
            'questions' => []
        ];
    }

    $total = $row['total_responses'];
    $criteriaRatings[$criteriaId]['questions'][] = [
        'question' => $row['question'],
        'question_type' => $row['question_type'],
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
    'criteria_ratings' => $criteriaRatings
]);
?>