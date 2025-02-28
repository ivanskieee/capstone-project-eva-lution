<?php
include '../database/connection.php';

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $records_per_page = 5;

    $query = "SELECT COUNT(*) as total FROM academic_list 
              WHERE year LIKE :search 
              OR semester LIKE :search 
              OR end_date LIKE :search 
              OR status LIKE :search";
    
    $stmt = $conn->prepare($query);
    $searchTerm = "%$search%";
    $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    $total_records = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_pages = ceil($total_records / $records_per_page);
    $stmt->closeCursor();

    $offset = ($page - 1) * $records_per_page;

    $query = "SELECT * FROM (
                  SELECT *, ROW_NUMBER() OVER (ORDER BY academic_id ASC) AS row_num FROM academic_list 
                  WHERE year LIKE :search 
                  OR semester LIKE :search 
                  OR end_date LIKE :search 
                  OR status LIKE :search
              ) AS temp_table
              WHERE row_num BETWEEN :start AND :end";

    $stmt = $conn->prepare($query);
    $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':start', $offset + 1, PDO::PARAM_INT);
    $stmt->bindValue(':end', $offset + $records_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        foreach ($result as $row) {
            echo "<tr>
                    <th class='text-center'>{$row['row_num']}</th>
                    <td><b>{$row['year']}</b></td>
                    <td><b>{$row['semester']}</b></td>
                    <td class='text-center'>" . 
                    ($row['is_default'] == 0 ? 
                        "<button type='button' class='btn btn-secondary bg-gradient-secondary col-sm-4 btn-flat btn-sm px-1 py-0 make_default' data-id='{$row['academic_id']}'>No</button>" 
                        : "<button class='btn btn-success'>Yes</button>") . 
                    "</td>
                    <td class='text-center'>" . 
                        ($row['status'] == 0 ? "<button class='btn btn-secondary update_status' data-id='{$row['academic_id']}' data-status='1'>Start</button>" :
                        ($row['status'] == 1 ? "<span class='badge badge-success'>Active (Ends: {$row['end_date']})</span> <button class='btn btn-success update_status' data-id='{$row['academic_id']}' data-status='2'>Close</button>" :
                        "<span class='badge badge-success'>Closed</span>")) . 
                    "</td>
                    <td class='text-center'>
                        <div class='btn-group'>
                            <a href='manage_academic.php?academic_id={$row['academic_id']}' class='btn btn-success'><i class='fas fa-edit'></i></a>
                            <form method='post' action='academic_list.php' class='delete-form' style='display: inline;'>
                                <input type='hidden' name='delete_id' value='{$row['academic_id']}'>
                                <button type='submit' class='btn btn-secondary delete_academic'><i class='fas fa-trash'></i></button>
                            </form>
                        </div>
                    </td>
                  </tr>";
        }

        echo "<!--pagination--><nav aria-label='Page navigation'>
                <ul class='pagination justify-content-center'>";
        
        $range = 5; 
        $start_page = max(1, $page - floor($range / 2));
        $end_page = min($total_pages, $start_page + $range - 1);

        if ($page > 1) {
            echo "<li class='page-item'>
                    <a class='page-link btn btn-outline-success text-black' href='#' data-page='" . ($page - 1) . "'>&laquo; Previous</a>
                  </li>";
        }

        if ($start_page > 1) {
            echo "<li class='page-item'><a class='page-link btn btn-outline-success text-black' href='#' data-page='1'>1</a></li>";
            if ($start_page > 2) echo "<li class='page-item disabled'><span class='page-link'>...</span></li>";
        }

        for ($page_num = $start_page; $page_num <= $end_page; $page_num++) {
            $active_class = ($page_num == $page) ? 'btn-success' : 'btn-outline-success text-black';
            echo "<li class='page-item'><a class='page-link text-black $active_class' href='#' data-page='$page_num'>$page_num</a></li>";
        }

        if ($end_page < $total_pages) {
            if ($end_page < $total_pages - 1) echo "<li class='page-item disabled'><span class='page-link text-black'>...</span></li>";
            echo "<li class='page-item'><a class='page-link btn btn-outline-success text-black' href='#' data-page='$total_pages'>$total_pages</a></li>";
        }

        if ($page < $total_pages) {
            echo "<li class='page-item'>
                    <a class='page-link btn btn-outline-success text-black' href='#' data-page='" . ($page + 1) . "'>Next &raquo;</a>
                  </li>";
        }

        echo "</ul></nav>";
    } else {
        echo "<tr><td colspan='6' class='text-center'>No academic year found.</td></tr>";
    }

    $stmt->closeCursor();
}
?>
