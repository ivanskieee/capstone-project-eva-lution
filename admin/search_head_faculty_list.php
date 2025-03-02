<?php
include '../database/connection.php';

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
    $records_per_page = 5;
    $offset = ($page - 1) * $records_per_page;

    // Count total records based on search criteria
    $query = "SELECT COUNT(*) as total FROM head_faculty_list 
              WHERE school_id LIKE :search 
              OR firstname LIKE :search 
              OR lastname LIKE :search";
    
    $stmt = $conn->prepare($query);
    $searchTerm = "%$search%";
    $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    $total_records = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_pages = ceil($total_records / $records_per_page);
    $stmt->closeCursor();

    // Fetch filtered head faculty records with row numbers
    $query = "
        SELECT head_id, school_id, firstname, lastname, email,
               (SELECT COUNT(*) FROM head_faculty_list AS sub WHERE sub.head_id <= main.head_id) AS row_num
        FROM head_faculty_list AS main
        WHERE school_id LIKE :search 
        OR firstname LIKE :search 
        OR lastname LIKE :search
        OR email LIKE :search
        ORDER BY head_id ASC
        LIMIT :offset, :records_per_page
    ";

    $stmt = $conn->prepare($query);
    $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':records_per_page', $records_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare table rows
    $tableData = "";
    if ($result) {
        foreach ($result as $row) {
            $tableData .= "<tr>
                    <th class='text-center'>{$row['row_num']}</th>
                    <td><b>{$row['school_id']}</b></td>
                    <td><b>" . ucwords($row['firstname'] . ' ' . $row['lastname']) . "</b></td>
                    <td><b>{$row['email']}</b></td>
                    <td class='text-center'>
                        <button type='button' class='btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle' data-toggle='dropdown' aria-expanded='true'>
                            Action
                        </button>
                        <div class='dropdown-menu'>
                            <a class='dropdown-item' href='new_head_faculty.php?head_id={$row['head_id']}'>Edit</a>
                            <form method='post' action='head_faculty_list.php' style='display: inline;' class='delete-form'>
                                <input type='hidden' name='delete_id' value='{$row['head_id']}'>
                                <button type='submit' class='dropdown-item' onclick='confirmDeletion()'>Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>";
        }
    } else {
        $tableData = "<tr><td colspan='5' class='text-center'>No results found.</td></tr>";
    }

    // Pagination with limited page numbers
    $pagination = "<nav aria-label='Page navigation'>
        <ul class='pagination justify-content-center'>";

    $range = 5;
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
