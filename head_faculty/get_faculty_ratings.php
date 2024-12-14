<?php
include '../database/connection.php';

if (isset($_GET['faculty_id'])) {
    $faculty_id = intval($_GET['faculty_id']); // Sanitize the input

    // Prepare the SQL query to fetch ratings and percentages
    $stmt = $conn->prepare("
    SELECT 
        q.question_id,
        q.question,
        q.question_type,
        COUNT(CASE WHEN ea.rate = 1 THEN 1 END) AS rate1,
        COUNT(CASE WHEN ea.rate = 2 THEN 1 END) AS rate2,
        COUNT(CASE WHEN ea.rate = 3 THEN 1 END) AS rate3,
        COUNT(CASE WHEN ea.rate = 4 THEN 1 END) AS rate4,
        COUNT(ea.rate) AS total_responses,
        ea.comment -- Fetch the comment if available
    FROM question_list q
    LEFT JOIN evaluation_answers ea 
        ON q.question_id = ea.question_id AND ea.faculty_id = :faculty_id
    GROUP BY q.question_id
");

    $stmt->execute(['faculty_id' => $faculty_id]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate percentages and prepare the response
    $response = [];
    foreach ($results as $row) {
        $total = $row['total_responses'];
        $response[] = [
            'question_id' => $row['question_id'],
            'question' => $row['question'],
            'question_type' => $row['question_type'],
            'comment' => $row['comment'] ?? '', // Include the comment if available
            'rate1' => $total ? round(($row['rate1'] / $total) * 100, 2) : 0,
            'rate2' => $total ? round(($row['rate2'] / $total) * 100, 2) : 0,
            'rate3' => $total ? round(($row['rate3'] / $total) * 100, 2) : 0,
            'rate4' => $total ? round(($row['rate4'] / $total) * 100, 2) : 0,
        ];        
    }

    echo json_encode(['status' => 'success', 'data' => $response]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid faculty ID.']);
}
