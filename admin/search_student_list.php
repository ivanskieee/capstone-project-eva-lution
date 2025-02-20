<?php
include '../database/connection.php';

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
    $records_per_page = 7;
    $offset = ($page - 1) * $records_per_page;

    // Count total records
    $query = "SELECT COUNT(*) as total FROM student_list 
              WHERE school_id LIKE :search 
              OR firstname LIKE :search 
              OR lastname LIKE :search 
              OR email LIKE :search 
              OR subject LIKE :search 
              OR section LIKE :search";

    $stmt = $conn->prepare($query);
    $searchTerm = "%$search%";
    $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    $total_records = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_pages = ceil($total_records / $records_per_page);
    $stmt->closeCursor();

    // Fetch filtered student records
    $query = "
        SELECT * FROM student_list
        WHERE school_id LIKE :search 
        OR firstname LIKE :search 
        OR lastname LIKE :search 
        OR email LIKE :search 
        OR subject LIKE :search 
        OR section LIKE :search
        ORDER BY student_id ASC
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
    $counter = ($page - 1) * $records_per_page + 1;
    if ($result) {
        foreach ($result as $row) {
            // Format subjects
            $subjects = explode(',', $row['subject']);
            $subjectList = "";
            foreach ($subjects as $subject) {
                $subjectList .= '<span class="subject-item">' . htmlspecialchars(strtoupper(trim($subject))) . '</span> ';
            }

            $tableData .= "<tr>
                    <th class='text-center'>{$counter}</th>
                    <td><b>{$row['school_id']}</b></td>
                    <td><b>" . ucwords($row['firstname'] . ' ' . $row['lastname']) . "</b></td>
                    <td><b>{$row['email']}</b></td>
                    <td>{$subjectList}</td>
                    <td><b>{$row['section']}</b></td>
                </tr>";
            $counter++;
        }
    } else {
        $tableData = "<tr><td colspan='6' class='text-center'>No results found.</td></tr>";
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
