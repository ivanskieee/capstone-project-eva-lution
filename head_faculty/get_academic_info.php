<?php
include '../database/connection.php';

if (isset($_GET['faculty_id']) && isset($_GET['category'])) {
    $faculty_id = intval($_GET['faculty_id']); // Sanitize the input
    $category = htmlspecialchars($_GET['category']); // Sanitize the category input

    try {
        $stmt = null;

        // Determine the query based on category
        switch ($category) {
            // Categories from college_faculty_list
            case 'faculty':
            case 'self':
            case 'faculty_faculty':
            case 'head_faculty':
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
                break;

            // Categories from head_faculty_list
            case 'dean_self':
            case 'faculty_head':
                $stmt = $conn->prepare("
                    SELECT 
                        al.academic_id,
                        al.year,
                        al.semester
                    FROM academic_list al
                    INNER JOIN head_faculty_list hf ON al.academic_id = hf.academic_id
                    WHERE hf.head_id = :head_id
                    LIMIT 1
                ");
                $stmt->execute(['head_id' => $faculty_id]); // Use 'faculty_id' as 'head_id' parameter
                break;

            default:
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid category provided.'
                ]);
                exit;
        }

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
                'message' => 'No academic information found for the provided faculty ID and category.'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'An error occurred while fetching data: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid or missing parameters.'
    ]);
}
?>
