<?php
include '../database/connection.php';

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
    $records_per_page = 5;
    $offset = ($page - 1) * $records_per_page;

    // Count total records
    $query = "SELECT COUNT(*) as total FROM academic_list 
              WHERE academic_id LIKE :search 
              OR year LIKE :search 
              OR semester LIKE :search 
              OR end_date LIKE :search 
              OR is_default LIKE :search 
              OR status LIKE :search";

    $stmt = $conn->prepare($query);
    $searchTerm = "%$search%";
    $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    $total_records = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_pages = ceil($total_records / $records_per_page);
    $stmt->closeCursor();

    // Fetch academic records with actual row numbers
    $query = "
        SELECT main.*, 
               (SELECT COUNT(*) FROM academic_list AS sub WHERE sub.academic_id <= main.academic_id) AS row_num,
               COALESCE(q.total_questions, 0) AS total_questions, 
               COALESCE(ea.total_answers, 0) AS total_answers      
        FROM academic_list AS main
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
        ) q ON main.academic_id = q.academic_id
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
        ) ea ON main.academic_id = ea.academic_id
        WHERE main.academic_id LIKE :search
        OR main.year LIKE :search 
        OR main.semester LIKE :search 
        OR main.end_date LIKE :search 
        OR main.is_default LIKE :search 
        OR main.status LIKE :search
        ORDER BY main.academic_id ASC
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
                    <th class='text-center'>{$row['row_num']}</th>
                    <td><b>{$row['year']}</b></td>
                    <td><b>{$row['semester']}</b></td>
                    <td class='text-center'><b>{$row['total_questions']}</b></td>
                    <td class='text-center'><b>{$row['total_answers']}</b></td>
                    <td class='text-center'>
                        <button type='button' class='btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle' data-toggle='dropdown' aria-expanded='true'>
                            Action
                        </button>
                        <div class='dropdown-menu'>
                            <a class='dropdown-item manage_questionnaire' href='manage_questionnaire.php?academic_id={$row['academic_id']}'>Manage</a>
                        </div>
                    </td>
                  </tr>";
        }
    } else {
        $tableData = "<tr><td colspan='6' class='text-center'>No results found.</td></tr>";
    }

    // Pagination with limited page numbers
    $pagination = "<!--pagination--><nav aria-label='Page navigation'>
<ul class='pagination justify-content-center'>";

    $range = 5; // Number of visible pages
    $start_page = max(1, $page - floor($range / 2));
    $end_page = min($total_pages, $start_page + $range - 1);

    if ($page > 1) {
        $pagination .= "<li class='page-item'>
        <a class='page-link btn btn-outline-success text-black' href='#' data-page='" . ($page - 1) . "'>&laquo; Previous</a>
    </li>";
    }

    if ($start_page > 1) {
        $pagination .= "<li class='page-item'><a class='page-link btn btn-outline-success text-black' href='#' data-page='1'>1</a></li>";
        if ($start_page > 2)
            $pagination .= "<li class='page-item disabled'><span class='page-link'>...</span></li>";
    }

    for ($page_num = $start_page; $page_num <= $end_page; $page_num++) {
        $active_class = ($page_num == $page) ? 'btn-success' : 'btn-outline-success text-black';
        $pagination .= "<li class='page-item'><a class='page-link text-black $active_class' href='#' data-page='$page_num'>$page_num</a></li>";
    }

    if ($end_page < $total_pages) {
        if ($end_page < $total_pages - 1)
            $pagination .= "<li class='page-item disabled'><span class='page-link text-black'>...</span></li>";
        $pagination .= "<li class='page-item'><a class='page-link btn btn-outline-success text-black' href='#' data-page='$total_pages'>$total_pages</a></li>";
    }

    if ($page < $total_pages) {
        $pagination .= "<li class='page-item'>
        <a class='page-link btn btn-outline-success text-black' href='#' data-page='" . ($page + 1) . "'>Next &raquo;</a>
    </li>";
    }

    $pagination .= "</ul></nav>";

    // Send JSON response
    echo json_encode(['tableData' => $tableData, 'pagination' => $pagination]);
    exit;
}
?>
