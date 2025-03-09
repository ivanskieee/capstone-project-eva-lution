<?php
session_start();
include '../database/connection.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user']['faculty_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No faculty ID in session.']);
    exit;
}

$facultyId = intval($_SESSION['user']['faculty_id']);

// Fetch academic-based rating distribution
$stmt = $conn->prepare("
    SELECT ea.rate, COUNT(*) AS count
    FROM evaluation_answers ea
    WHERE ea.faculty_id = :faculty_id AND ea.rate IS NOT NULL
    GROUP BY ea.rate
    ORDER BY ea.rate ASC
");
$stmt->execute(['faculty_id' => $facultyId]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalResponses = array_sum(array_column($data, 'count'));

$academicRatings = [];
foreach ($data as $row) {
    $rateKey = "rate" . $row['rate'];
    $academicRatings[$rateKey] = ($totalResponses > 0) ? round(($row['count'] / $totalResponses) * 100, 2) : 0;
}

// Fetch faculty performance over time
$stmt = $conn->prepare("
    SELECT CONCAT(al.year, ' - ', al.semester) AS academic_period, AVG(ea.rate) AS average_score
    FROM evaluation_answers ea
    JOIN academic_list al ON ea.academic_id = al.academic_id
    WHERE ea.faculty_id = :faculty_id
    GROUP BY al.academic_id, al.year, al.semester
    ORDER BY al.year ASC, al.semester ASC
");
$stmt->execute(['faculty_id' => $facultyId]);
$averageData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$performanceData = [];
foreach ($averageData as $row) {
    $performanceData[$row['academic_period']] = round($row['average_score'], 2);
}

// Fetch criteria-based ratings
$stmt = $conn->prepare("
    SELECT 
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
    GROUP BY c.criteria
");
$stmt->execute(['faculty_id' => $facultyId]);
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
    'academic_ratings' => $academicRatings,
    'performance_data' => $performanceData,
    'criteria_ratings' => $criteriaData
]);
?>
