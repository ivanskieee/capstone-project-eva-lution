<?php
include '../database/connection.php';

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
    $records_per_page = 5;
    $offset = ($page - 1) * $records_per_page;

    // Count total records
    $query = "SELECT COUNT(*) as total FROM audit_logs 
              JOIN users ON audit_logs.user_id = users.id 
              WHERE users.firstname LIKE :search 
              OR users.lastname LIKE :search 
              OR users.email LIKE :search 
              OR audit_logs.action LIKE :search 
              OR audit_logs.details LIKE :search";

    $stmt = $conn->prepare($query);
    $searchTerm = "%$search%";
    $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    $total_records = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_pages = ceil($total_records / $records_per_page);
    $stmt->closeCursor();

    // Fetch filtered logs
    $query = "
        SELECT audit_logs.*, users.email, users.firstname, users.lastname 
        FROM audit_logs 
        JOIN users ON audit_logs.user_id = users.id 
        WHERE users.firstname LIKE :search 
        OR users.lastname LIKE :search 
        OR users.email LIKE :search 
        OR audit_logs.action LIKE :search 
        OR audit_logs.details LIKE :search
        ORDER BY timestamp DESC
        LIMIT :limit OFFSET :offset
    ";

    $stmt = $conn->prepare($query);
    $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare table rows
    $tableData = "";
    if ($result) {
        foreach ($result as $row) {
            $tableData .= "<tr>
                    <td><b>{$row['firstname']} {$row['lastname']} ({$row['email']})</b></td>
                    <td><b>{$row['action']}</b></td>
                    <td class='text-break'>{$row['details']}</td>
                    <td><b>{$row['timestamp']}</b></td>
                </tr>";
        }
    } else {
        $tableData = "<tr><td colspan='4' class='text-center'>No logs found.</td></tr>";
    }

    // Pagination
    $pagination = "<nav aria-label='Page navigation'>
        <ul class='pagination justify-content-center'>";

    $range = 5;
    $start_page = max(1, $page - floor($range / 2));
    $end_page = min($total_pages, $start_page + $range - 1);

    if ($page > 1) {
        $pagination .= "<li class='page-item'><a class='page-link btn btn-outline-success text-black' href='#' data-page='" . ($page - 1) . "'>&laquo; Previous</a></li>";
    }

    if ($start_page > 1) {
        $pagination .= "<li class='page-item'><a class='page-link btn btn-outline-success text-black' href='#' data-page='1'>1</a></li>";
        if ($start_page > 2) $pagination .= "<li class='page-item disabled'><span class='page-link'>...</span></li>";
    }

    for ($page_num = $start_page; $page_num <= $end_page; $page_num++) {
        $active_class = ($page_num == $page) ? 'btn-success' : 'btn-outline-success text-black';
        $pagination .= "<li class='page-item'><a class='page-link text-black $active_class' href='#' data-page='$page_num'>$page_num</a></li>";
    }

    if ($page < $total_pages) {
        $pagination .= "<li class='page-item'><a class='page-link btn btn-outline-success text-black' href='#' data-page='" . ($page + 1) . "'>Next &raquo;</a></li>";
    }

    $pagination .= "</ul></nav>";

    echo json_encode(['tableData' => $tableData, 'pagination' => $pagination]);
    exit;
}
?>
