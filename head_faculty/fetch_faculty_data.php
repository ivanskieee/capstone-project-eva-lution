<?php
include '../database/connection.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['faculty_id'], $_GET['category'], $_GET['academic_id'])) {
    $facultyId = $_GET['faculty_id'];
    $category = $_GET['category'];
    $academicId = $_GET['academic_id'];

    $query = "";
    if ($category === 'faculty') {
        $query = "SELECT ea.rate, COUNT(*) AS count
                  FROM evaluation_answers ea
                  WHERE ea.faculty_id = :faculty_id AND ea.academic_id = :academic_id AND ea.rate IS NOT NULL
                  GROUP BY ea.rate
                  ORDER BY ea.rate ASC";
    } elseif ($category === 'self-faculty') {
        $query = "SELECT sfe.average_score AS rate, COUNT(*) AS count
                  FROM self_faculty_eval sfe
                  WHERE sfe.faculty_id = :faculty_id AND sfe.academic_id = :academic_id
                  GROUP BY sfe.average_score
                  ORDER BY sfe.average_score ASC";
    } elseif ($category === 'self-head-faculty') {
        $query = "SELECT she.average_score AS rate, COUNT(*) AS count
                  FROM self_head_eval she
                  WHERE she.faculty_id = :faculty_id AND she.academic_id = :academic_id
                  GROUP BY she.average_score
                  ORDER BY she.average_score ASC";
    } elseif ($category === 'faculty-to-faculty') {
        $query = "SELECT eaf.rate, COUNT(*) AS count
                  FROM evaluation_answers_faculty_faculty eaf
                  WHERE eaf.faculty_id = :faculty_id AND eaf.academic_id = :academic_id AND eaf.rate IS NOT NULL
                  GROUP BY eaf.rate
                  ORDER BY eaf.rate ASC";
    } elseif ($category === 'faculty-to-head') {
        $query = "SELECT eah.rate, COUNT(*) AS count
                  FROM evaluation_answers_faculty_dean eah
                  WHERE eah.faculty_id = :faculty_id AND eah.academic_id = :academic_id AND eah.rate IS NOT NULL
                  GROUP BY eah.rate
                  ORDER BY eah.rate ASC";
    } elseif ($category === 'head-to-faculty') {
        $query = "SELECT eahf.rate, COUNT(*) AS count
                  FROM evaluation_answers_dean_faculty eahf
                  WHERE eahf.faculty_id = :faculty_id AND eahf.academic_id = :academic_id AND eahf.rate IS NOT NULL
                  GROUP BY eahf.rate
                  ORDER BY eahf.rate ASC";
    } else {
        echo json_encode(['error' => 'Invalid category']);
        exit;
    }

    $stmt = $conn->prepare($query);
    $stmt->execute(['faculty_id' => $facultyId, 'academic_id' => $academicId]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get total responses to calculate percentage
    $totalResponses = array_sum(array_column($data, 'count'));

    // Prepare labels (ratings) and dataset (percentage for each rating)
    $labels = [];
    $dataset = [];

    foreach ($data as $row) {
        $labels[] = "Rate " . $row['rate'];
        $percentage = ($row['count'] / $totalResponses) * 100;
        $dataset[] = round($percentage, 2);
    }

    // Fetch the average score per academic_id including semester
    $averageQuery = "SELECT CONCAT(al.year, ' - ', al.semester) AS academic_period, AVG(ea.rate) AS average_score
                 FROM evaluation_answers ea
                 JOIN academic_list al ON ea.academic_id = al.academic_id
                 WHERE ea.faculty_id = :faculty_id
                 GROUP BY al.academic_id, al.year, al.semester
                 ORDER BY al.year ASC, al.semester ASC";

    $avgStmt = $conn->prepare($averageQuery);
    $avgStmt->execute(['faculty_id' => $facultyId]);
    $averageData = $avgStmt->fetchAll(PDO::FETCH_ASSOC);

    $lineLabels = [];
    $lineDataset = [];

    foreach ($averageData as $row) {
        $lineLabels[] = $row['academic_period'];
        $lineDataset[] = round($row['average_score'], 2);
    }

    echo json_encode([
        'labels' => $labels,
        'dataset' => $dataset,
        'line_labels' => $lineLabels,
        'line_dataset' => $lineDataset
    ]);
} else {
    echo json_encode(['error' => 'Missing parameters']);
}
