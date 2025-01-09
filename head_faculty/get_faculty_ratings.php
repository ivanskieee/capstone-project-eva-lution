<?php
include '../database/connection.php';

$faculty_id = $_GET['faculty_id'] ?? null;
$category = $_GET['category'] ?? null;

// Default response
$response = [
    'status' => 'error',
    'message' => 'Invalid parameters provided.',
    'data' => []
];

if ($faculty_id && $category) {
    $tableMapping = [
        'faculty' => 'question_list',
        'faculty_faculty' => 'question_faculty_faculty',
        'faculty_head' => 'question_faculty_dean',
        'head_faculty' => 'question_dean_faculty'
    ];

    if (array_key_exists($category, $tableMapping)) {
        $tableName = $tableMapping[$category];

        try {
            // Prepare query for ratings
            $query = $conn->prepare("
                SELECT 
                    q.question_id,
                    q.question,
                    q.question_type,
                    COUNT(CASE WHEN e.rate = 1 THEN 1 END) AS rate1,
                    COUNT(CASE WHEN e.rate = 2 THEN 1 END) AS rate2,
                    COUNT(CASE WHEN e.rate = 3 THEN 1 END) AS rate3,
                    COUNT(CASE WHEN e.rate = 4 THEN 1 END) AS rate4,
                    COUNT(e.rate) AS total_responses
                FROM $tableName q
                LEFT JOIN evaluation_answers e 
                    ON e.question_id = q.question_id AND e.faculty_id = :faculty_id
                GROUP BY q.question_id
            ");

            $query->bindParam(':faculty_id', $faculty_id, PDO::PARAM_INT);
            $query->execute();
            $ratings = $query->fetchAll(PDO::FETCH_ASSOC);

            // Fetch comments for the selected category
            $commentQuery = $conn->prepare("
                SELECT 
                    q.question_id,
                    e.comment
                FROM $tableName q
                LEFT JOIN evaluation_answers e 
                    ON e.question_id = q.question_id AND e.faculty_id = :faculty_id
                WHERE e.comment IS NOT NULL
            ");

            $commentQuery->bindParam(':faculty_id', $faculty_id, PDO::PARAM_INT);
            $commentQuery->execute();
            $comments = $commentQuery->fetchAll(PDO::FETCH_ASSOC);

            // Group comments by question_id
            $commentsByQuestion = [];
            foreach ($comments as $comment) {
                $commentsByQuestion[$comment['question_id']][] = $comment['comment'];
            }

            // Compile the response
            $responseData = [];
            foreach ($ratings as $row) {
                $total = $row['total_responses'];
                $responseData[] = [
                    'question_id' => $row['question_id'],
                    'question' => $row['question'],
                    'question_type' => $row['question_type'],
                    'comments' => $commentsByQuestion[$row['question_id']] ?? [],
                    'rate1' => $total ? round(($row['rate1'] / $total) * 100, 2) : 0,
                    'rate2' => $total ? round(($row['rate2'] / $total) * 100, 2) : 0,
                    'rate3' => $total ? round(($row['rate3'] / $total) * 100, 2) : 0,
                    'rate4' => $total ? round(($row['rate4'] / $total) * 100, 2) : 0,
                ];
            }

            $response['status'] = 'success';
            $response['data'] = $responseData;
        } catch (PDOException $e) {
            $response['message'] = 'Database error: ' . $e->getMessage();
        }
    } else {
        $response['message'] = 'Invalid category.';
    }
}

echo json_encode($response);
