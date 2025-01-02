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
        COUNT(ea.rate) AS total_responses
    FROM question_list q
    LEFT JOIN evaluation_answers ea 
        ON q.question_id = ea.question_id AND ea.faculty_id = :faculty_id
    GROUP BY q.question_id
    ");

    $stmt->execute(['faculty_id' => $faculty_id]);
    $ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all comments grouped by question
    $commentStmt = $conn->prepare("
    SELECT 
        q.question_id,
        ea.comment
    FROM question_list q
    LEFT JOIN evaluation_answers ea 
        ON q.question_id = ea.question_id AND ea.faculty_id = :faculty_id
    WHERE ea.comment IS NOT NULL
    ");

    $commentStmt->execute(['faculty_id' => $faculty_id]);
    $comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);

    // Group comments by question_id
    $commentsByQuestion = [];
    foreach ($comments as $comment) {
        $commentsByQuestion[$comment['question_id']][] = $comment['comment'];
    }

    // Prepare the response
    $response = [];
    foreach ($ratings as $row) {
        $total = $row['total_responses'];
        $response[] = [
            'question_id' => $row['question_id'],
            'question' => $row['question'],
            'question_type' => $row['question_type'],
            'comments' => $commentsByQuestion[$row['question_id']] ?? [], // Include all comments
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
