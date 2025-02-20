<?php
include '../database/connection.php';

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
    $records_per_page = 5;
    $offset = ($page - 1) * $records_per_page;

    // Count total users
    $query = "SELECT COUNT(*) as total FROM users 
              WHERE firstname LIKE :search 
              OR lastname LIKE :search 
              OR email LIKE :search";
    
    $stmt = $conn->prepare($query);
    $searchTerm = "%$search%";
    $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    $total_records = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_pages = ceil($total_records / $records_per_page);
    $stmt->closeCursor();

    // Fetch users
    $query = "SELECT * FROM users
              WHERE firstname LIKE :search 
              OR lastname LIKE :search 
              OR email LIKE :search
              ORDER BY id ASC
              LIMIT :limit OFFSET :offset";
    
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate table rows
    $tableData = "";
    $counter = $offset + 1;
    if ($users) {
        foreach ($users as $row) {
            $tableData .= "<tr>
                    <th class='text-center'>{$counter}</th>
                    <td><b>" . ucwords($row['firstname'] . ' ' . $row['lastname']) . "</b></td>
                    <td><b>{$row['email']}</b></td>
                    <td class='text-center'>
                        <button type='button' class='btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle' data-toggle='dropdown'>
                            Action
                        </button>
                        <div class='dropdown-menu'>
                            <a class='dropdown-item' href='new_users.php?id={$row['id']}'>Edit</a>
                            <div class='dropdown-divider'></div>
                            <form method='post' action='user_list.php' class='delete-form' style='display: inline;'>
                                <input type='hidden' name='delete_id' value='{$row['id']}'>
                                <button type='submit' class='dropdown-item'>Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>";
            $counter++;
        }
    } else {
        $tableData = "<tr><td colspan='4' class='text-center'>No results found.</td></tr>";
    }

    // Pagination
    $pagination = "<nav aria-label='Page navigation'>
        <ul class='pagination justify-content-center'>";

    if ($page > 1) {
        $pagination .= "<li class='page-item'><a class='page-link btn btn-outline-success text-black' href='#' data-page='" . ($page - 1) . "'>&laquo; Previous</a></li>";
    }

    $range = 5;
    $start_page = max(1, $page - floor($range / 2));
    $end_page = min($total_pages, $start_page + $range - 1);

    for ($p = $start_page; $p <= $end_page; $p++) {
        $active_class = ($p == $page) ? 'btn-success' : 'btn-outline-success text-black';
        $pagination .= "<li class='page-item'><a class='page-link text-black $active_class' href='#' data-page='$p'>$p</a></li>";
    }

    if ($page < $total_pages) {
        $pagination .= "<li class='page-item'><a class='page-link btn btn-outline-success text-black' href='#' data-page='" . ($page + 1) . "'>Next &raquo;</a></li>";
    }

    $pagination .= "</ul></nav>";

    echo json_encode(['tableData' => $tableData, 'pagination' => $pagination]);
    exit;
}
?>
