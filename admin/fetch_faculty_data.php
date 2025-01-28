<?php
include '../database/connection.php';

if (isset($_GET['faculty_id'], $_GET['category'], $_GET['academic_id'])) {
    $facultyId = $_GET['faculty_id'];
    $category = $_GET['category'];
    $academicId = $_GET['academic_id'];

    if ($category === 'faculty') {
        $stmt = $conn->prepare("
            SELECT ea.rate AS rating
            FROM evaluation_answers ea
            WHERE ea.faculty_id = :faculty_id AND ea.academic_id = :academic_id AND ea.rate IS NOT NULL
            ORDER BY ea.evaluation_id ASC
        ");
        $stmt->execute(['faculty_id' => $facultyId, 'academic_id' => $academicId]);
    } elseif ($category === 'self-faculty') {
        // Fetch average_score for self-faculty evaluations
        $stmt = $conn->prepare("
            SELECT sfe.average_score AS average_score
            FROM self_faculty_eval sfe
            WHERE sfe.faculty_id = :faculty_id AND sfe.academic_id = :academic_id
            ORDER BY sfe.faculty_id ASC
        ");
        $stmt->execute(['faculty_id' => $facultyId, 'academic_id' => $academicId]);
    } elseif ($category === 'self-head-faculty') {
        // Fetch average_score for self-head-faculty evaluations
        $stmt = $conn->prepare("
            SELECT she.average_score AS average_score
            FROM self_head_eval she
            WHERE she.faculty_id = :faculty_id AND she.academic_id = :academic_id
            ORDER BY she.faculty_id ASC
        ");
        $stmt->execute(['faculty_id' => $facultyId, 'academic_id' => $academicId]);
    } elseif ($category === 'faculty-to-faculty') {
        $stmt = $conn->prepare("
            SELECT eaf.rate AS rating
            FROM evaluation_answers_faculty_faculty eaf
            WHERE eaf.faculty_id = :faculty_id AND eaf.academic_id = :academic_id AND eaf.rate IS NOT NULL
            ORDER BY eaf.evaluation_id ASC
        ");
        $stmt->execute(['faculty_id' => $facultyId, 'academic_id' => $academicId]);
    } elseif ($category === 'faculty-to-head') {
        $stmt = $conn->prepare("
            SELECT eah.rate AS rating
            FROM evaluation_answers_faculty_dean eah
            WHERE eah.faculty_id = :faculty_id AND eah.academic_id = :academic_id AND eah.rate IS NOT NULL
            ORDER BY eah.evaluation_id ASC
        ");
        $stmt->execute(['faculty_id' => $facultyId, 'academic_id' => $academicId]);
    } elseif ($category === 'head-to-faculty') {
        $stmt = $conn->prepare("
            SELECT eahf.rate AS rating
            FROM evaluation_answers_dean_faculty eahf
            WHERE eahf.faculty_id = :faculty_id AND eahf.academic_id = :academic_id AND eahf.rate IS NOT NULL
            ORDER BY eahf.evaluation_id ASC
        ");
        $stmt->execute(['faculty_id' => $facultyId, 'academic_id' => $academicId]);
    } else {
        echo json_encode(['error' => 'Invalid category']);
        exit;
    }

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $labels = range(1, count($data));

    // Determine if the category involves average_score or rating
    if ($category === 'self-faculty' || $category === 'self-head-faculty') {
        $dataset = array_column($data, 'average_score');
    } else {
        $dataset = array_column($data, 'rating');
    }

    echo json_encode(['labels' => $labels, 'dataset' => $dataset]);
} else {
    echo json_encode(['error' => 'Missing parameters']);
}
?>
