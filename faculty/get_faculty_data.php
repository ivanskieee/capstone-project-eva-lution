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

// Fetch academic-based ratings
$stmt = $conn->prepare("
    SELECT 
        a.academic_id,
        a.year,
        a.semester,
        COUNT(CASE WHEN ea.rate = 1 THEN 1 END) AS rate1,
        COUNT(CASE WHEN ea.rate = 2 THEN 1 END) AS rate2,
        COUNT(CASE WHEN ea.rate = 3 THEN 1 END) AS rate3,
        COUNT(CASE WHEN ea.rate = 4 THEN 1 END) AS rate4,
        COUNT(ea.rate) AS total_responses
    FROM evaluation_answers ea
    LEFT JOIN academic_list a ON ea.academic_id = a.academic_id
    WHERE ea.faculty_id = :faculty_id
    GROUP BY a.academic_id, a.year, a.semester
    ORDER BY a.year ASC, a.semester ASC
");
$stmt->bindParam(':faculty_id', $facultyId, PDO::PARAM_INT);
$stmt->execute();
$academicRatings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$academicData = [];
foreach ($academicRatings as $row) {
    $academicPeriod = "Year {$row['year']} - Semester {$row['semester']}";
    $total = $row['total_responses'];

    $academicData[$academicPeriod] = [
        'rate1' => $total ? round(($row['rate1'] / $total) * 100, 2) : 0,
        'rate2' => $total ? round(($row['rate2'] / $total) * 100, 2) : 0,
        'rate3' => $total ? round(($row['rate3'] / $total) * 100, 2) : 0,
        'rate4' => $total ? round(($row['rate4'] / $total) * 100, 2) : 0
    ];
}

// Fetch criteria-based ratings
$stmt = $conn->prepare("
    SELECT 
        c.criteria_id,
        c.criteria,
        COUNT(CASE WHEN ea.rate = 1 THEN 1 END) AS rate1,
        COUNT(CASE WHEN ea.rate = 2 THEN 1 END) AS rate2,
        COUNT(CASE WHEN ea.rate = 3 THEN 1 END) AS rate3,
        COUNT(CASE WHEN ea.rate = 4 THEN 1 END) AS rate4,
        COUNT(ea.rate) AS total_responses
    FROM evaluation_answers ea
    LEFT JOIN question_list q ON ea.question_id = q.question_id
    LEFT JOIN criteria_list c ON q.criteria_id = c.criteria_id
    WHERE ea.faculty_id = :faculty_id
    GROUP BY c.criteria_id, c.criteria
");
$stmt->bindParam(':faculty_id', $facultyId, PDO::PARAM_INT);
$stmt->execute();
$criteriaRatings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$criteriaData = [];
foreach ($criteriaRatings as $row) {
    $total = $row['total_responses'];
    $criteriaData[$row['criteria']] = [
        'rate1' => $total ? round(($row['rate1'] / $total) * 100, 2) : 0,
        'rate2' => $total ? round(($row['rate2'] / $total) * 100, 2) : 0,
        'rate3' => $total ? round(($row['rate3'] / $total) * 100, 2) : 0,
        'rate4' => $total ? round(($row['rate4'] / $total) * 100, 2) : 0
    ];
}

// Send JSON response
header("Content-Type: application/json");
echo json_encode([
    'status' => 'success',
    'faculty_name' => $facultyName,
    'academic_ratings' => $academicData,
    'criteria_ratings' => $criteriaData
]);
?>
