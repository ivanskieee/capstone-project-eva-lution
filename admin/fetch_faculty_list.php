<?php
include '../database/connection.php';

$category = $_GET['category'] ?? '';

if (in_array($category, ['faculty', 'self', 'faculty_faculty', 'head_faculty'])) {
    $query = "
        SELECT faculty_id, firstname, lastname, 'faculty' AS type 
        FROM college_faculty_list
    ";
} elseif (in_array($category, ['dean_self', 'faculty_head'])) {
    $query = "
        SELECT head_id AS faculty_id, firstname, lastname, 'dean' AS type 
        FROM head_faculty_list
    ";
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid category.']);
    exit;
}

$stmt = $conn->query($query);
$data = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data[] = [
        'faculty_id' => $row['faculty_id'],
        'fullname' => $row['firstname'] . ' ' . $row['lastname'],
        'type' => $row['type']
    ];
}
echo json_encode(['status' => 'success', 'data' => $data]);