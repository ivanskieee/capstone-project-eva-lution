<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: ../index.php');
    exit;
}

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: unauthorized.php');
    exit;
}

include 'header.php';
include 'sidebar.php';
include 'footer.php';
include '../database/connection.php';
include 'audit_log.php'; // Include audit log function

$id = isset($_GET['question_id']) ? $_GET['question_id'] : null;
$academic_id = isset($_GET['academic_id']) ? $_GET['academic_id'] : null;
$questionToEdit = null;

$query = "SELECT COUNT(*) as total FROM academic_list";
$stmt = $conn->prepare($query);
$stmt->execute();
$total_records = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$records_per_page = 5; // Adjust as needed
$total_pages = ceil($total_records / $records_per_page);

// Get current page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, min($page, $total_pages));

$offset = ($page - 1) * $records_per_page;

// Fetch academic records with pagination
$query = "
    SELECT a.*, 
           COALESCE(q.total_questions, 0) AS total_questions, 
           COALESCE(ea.total_answers, 0) AS total_answers      
    FROM academic_list a
    LEFT JOIN (
        SELECT academic_id, COUNT(*) AS total_questions
        FROM (
            SELECT academic_id, question_id FROM question_list
            UNION ALL
            SELECT academic_id, question_id FROM question_faculty_faculty
            UNION ALL
            SELECT academic_id, question_id FROM question_faculty_dean
            UNION ALL
            SELECT academic_id, question_id FROM question_dean_faculty
        ) AS combined_questions
        GROUP BY academic_id
    ) q ON a.academic_id = q.academic_id
    LEFT JOIN (
        SELECT academic_id, COUNT(DISTINCT faculty_id) AS total_answers
        FROM (
            SELECT academic_id, faculty_id FROM evaluation_answers
            UNION ALL
            SELECT academic_id, faculty_id FROM evaluation_answers_faculty_dean
            UNION ALL
            SELECT academic_id, faculty_id FROM evaluation_answers_faculty_faculty
            UNION ALL
            SELECT academic_id, faculty_id FROM evaluation_answers_dean_faculty
            UNION ALL
            SELECT NULL AS academic_id, faculty_id FROM self_faculty_eval
            UNION ALL
            SELECT NULL AS academic_id, faculty_id FROM self_head_eval
        ) AS combined_evaluation_answers
        GROUP BY academic_id
    ) ea ON a.academic_id = ea.academic_id
    ORDER BY a.academic_id ASC
    LIMIT :limit OFFSET :offset
";
$stmt = $conn->prepare($query);
$stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$questionnaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pagination segment settings
$segment_size = 5;
$current_segment = ceil($page / $segment_size);
$start_page = ($current_segment - 1) * $segment_size + 1;
$end_page = min($current_segment * $segment_size, $total_pages);

// Fetch criteria list
$stmt = $conn->prepare('SELECT * FROM criteria_list');
$stmt->execute();
$criteriaList = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sector = isset($_GET['sector']) ? $_GET['sector'] : 'student_faculty';

// Fetch questions based on selected sector
if ($sector === 'student_faculty') {
    $stmt = $conn->prepare('SELECT * FROM question_list WHERE academic_id = ?');
} elseif ($sector === 'faculty_faculty') {
    $stmt = $conn->prepare('SELECT * FROM question_faculty_faculty WHERE academic_id = ?');
} elseif ($sector === 'faculty_dean') {
    $stmt = $conn->prepare('SELECT * FROM question_faculty_dean WHERE academic_id = ?');
} elseif ($sector === 'dean_faculty') {
    $stmt = $conn->prepare('SELECT * FROM question_dean_faculty WHERE academic_id = ?');
}
$stmt->execute([$academic_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch question data for editing
if ($id) {
    $stmt = $conn->prepare('SELECT * FROM question_list WHERE question_id = ?');
    $stmt->execute([$id]);
    $questionToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user']['id']; // Get logged-in user ID for logging
    $academic_id = $_POST['academic_id'];
    $sector = $_POST['sector'] ?? 'student_faculty'; // Default sector

    // Determine the target table based on the sector
    if ($sector === 'faculty_faculty') {
        $table = 'question_faculty_faculty';
    } elseif ($sector === 'faculty_dean') {
        $table = 'question_faculty_dean';
    } elseif ($sector === 'dean_faculty') {
        $table = 'question_dean_faculty';
    } else {
        $table = 'question_list';  // Default table for student_faculty
    }

    if (isset($_POST['delete_id'])) {
        // DELETE Operation
        $delete_id = $_POST['delete_id'];
        $stmt = $conn->prepare("DELETE FROM $table WHERE question_id = :id");
        $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            log_action($conn, $user_id, 'Deleted Question', "Deleted question ID: $delete_id from $table");
            $_SESSION['message'] = 'Question deleted successfully.';
        } else {
            $_SESSION['error'] = 'Error deleting question. Please try again.';
        }

        echo "<script>window.location.replace('manage_questionnaire.php?academic_id=" . $academic_id . "&sector=" . $sector . "');</script>";
        exit;

    } elseif (isset($_POST['question'])) {
        // EDIT or INSERT Operation
        $criteria_id = $_POST['criteria_id'];
        $question = trim($_POST['question']);
        $question_type = $_POST['question_type'];
        $id = $_POST['question_id'] ?? null;

        if (!empty($criteria_id) && !empty($question) && !empty($academic_id) && !empty($question_type)) {
            if ($id) {
                // Update existing question
                $query = "UPDATE $table SET criteria_id = ?, question = ?, question_type = ?, academic_id = ? WHERE question_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->execute([$criteria_id, $question, $question_type, $academic_id, $id]);

                log_action($conn, $user_id, 'Updated Question', "Updated question ID: $id in $table");
                $_SESSION['message'] = 'Question updated successfully.';
            } else {
                // Insert new question
                $query = "INSERT INTO $table (criteria_id, question, question_type, academic_id) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->execute([$criteria_id, $question, $question_type, $academic_id]);

                $newQuestionId = $conn->lastInsertId(); // Get the last inserted ID for logging
                log_action($conn, $user_id, 'Added Question', "Added new question ID: $newQuestionId in $table");
                $_SESSION['message'] = 'Question added successfully.';
            }

            header('Location: manage_questionnaire.php?academic_id=' . $academic_id . '&sector=' . $sector);
            exit;
        } else {
            $_SESSION['error'] = 'Please fill out all fields.';
        }
    }
}

// Fetch question data for editing
if (isset($_GET['question_id'])) {
    $question_id = $_GET['question_id'];
    $sector = $_GET['sector'] ?? 'student_faculty';

    // Determine the target table based on the sector
    $table = ($sector === 'faculty_faculty') ? 'question_faculty_faculty' : 'question_list';

    $stmt = $conn->prepare("SELECT * FROM $table WHERE question_id = :id");
    $stmt->bindParam(':id', $question_id, PDO::PARAM_INT);
    $stmt->execute();
    $questionToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

$conn = null;
?>
