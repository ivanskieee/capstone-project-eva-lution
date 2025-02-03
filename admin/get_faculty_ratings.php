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
    // Map categories to questions and evaluation tables
    $tableMapping = [
        'faculty' => [
            'questions' => 'question_list',
            'answers' => 'evaluation_answers'
        ],
        'faculty_faculty' => [
            'questions' => 'question_faculty_faculty',
            'answers' => 'evaluation_answers_faculty_faculty'
        ],
        'faculty_head' => [
            'questions' => 'question_faculty_dean',
            'answers' => 'evaluation_answers_faculty_dean'
        ],
        'head_faculty' => [
            'questions' => 'question_dean_faculty',
            'answers' => 'evaluation_answers_dean_faculty'
        ]
    ];

    if (array_key_exists($category, $tableMapping)) {
        $questionTable = $tableMapping[$category]['questions'];
        $answerTable = $tableMapping[$category]['answers'];

        try {
            // Fetch all criteria and group questions under each
            $query = $conn->prepare("
                SELECT 
                    c.criteria_id, 
                    c.criteria, 
                    q.question_id, 
                    q.question, 
                    q.question_type,
                    COUNT(CASE WHEN e.rate = 1 THEN 1 END) AS rate1,
                    COUNT(CASE WHEN e.rate = 2 THEN 1 END) AS rate2,
                    COUNT(CASE WHEN e.rate = 3 THEN 1 END) AS rate3,
                    COUNT(CASE WHEN e.rate = 4 THEN 1 END) AS rate4,
                    COUNT(e.rate) AS total_responses
                FROM criteria_list c
                JOIN $questionTable q ON q.criteria_id = c.criteria_id
                LEFT JOIN $answerTable e 
                    ON e.question_id = q.question_id 
                    AND e.faculty_id = :faculty_id
                GROUP BY c.criteria_id, q.question_id
                ORDER BY c.criteria_id
            ");

            $query->bindParam(':faculty_id', $faculty_id, PDO::PARAM_INT);
            $query->execute();
            $ratings = $query->fetchAll(PDO::FETCH_ASSOC);

            // Fetch comments
            $commentQuery = $conn->prepare("
                SELECT 
                    q.question_id,
                    e.comment
                FROM $questionTable q
                LEFT JOIN $answerTable e 
                    ON e.question_id = q.question_id 
                    AND e.faculty_id = :faculty_id
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

            // Organize data by criteria
            $responseData = [];
            foreach ($ratings as $row) {
                $total = $row['total_responses'];
                $responseData[$row['criteria']][] = [
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
?>
