<?php
include "handlers/faculty_handler.php";

include '../database/connection.php';

$query = "
    SELECT 
        faculty_id, school_id, email, firstname, lastname
    FROM college_faculty_list
";
$stmt = $conn->prepare($query);
$stmt->execute();
$users_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Assume $total_records is the total number of records from the database
$total_records = count($users_data); // Replace this with the actual query to count rows
$records_per_page = 5; // Number of records per page
$total_pages = ceil($total_records / $records_per_page);

// Current page
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page = max(1, min($page, $total_pages)); // Ensure the page is within valid range

// Calculate offset for database query
$offset = ($page - 1) * $records_per_page;

// Fetch only the required records for the current page
$users_data = array_slice($users_data, $offset, $records_per_page);

// Pagination segment settings
$segment_size = 5; // Number of page links per segment
$current_segment = ceil($page / $segment_size);
$start_page = ($current_segment - 1) * $segment_size + 1;
$end_page = min($current_segment * $segment_size, $total_pages);

?>

<div class="content">
    <nav class="main-header">
        <div class="col-lg-12 mt-3">
            <div class="col-12 mb-3">
                <h2 class="text-start"
                    style="font-size: 1.8rem; font-weight: bold; color: #4a4a4a; border-bottom: 2px solid #ccc; padding-bottom: 5px;">
                    Faculty List</h2>
            </div>
            <div class="card card-outline card-success">
                <div class="card-header">
                    <div class="card-tools">
                        <a class="btn btn-block btn-sm btn-default btn-flat border-success" href="new_faculty.php"><i
                                class="fa fa-plus"></i> Add New Faculty</a>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-8 col-md-4 ms-auto mt-3 mr-3">
                        <input type="text" id="searchInput" class="form-control form-control-sm"
                            placeholder="Search Tertiary Faculties">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="list">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = $offset + 1;
                                foreach ($users_data as $row): ?>
                                    <tr>
                                        <th class="text-center"><?php echo $i++; ?></th>
                                        <td><b><?php echo htmlspecialchars($row['school_id']); ?></b></td>
                                        <td><b><?php echo htmlspecialchars(ucwords($row['firstname'] . ' ' . $row['lastname'])); ?></b>
                                        </td>
                                        <td><b><?php echo htmlspecialchars($row['email']); ?></b></td>
                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle"
                                                data-toggle="dropdown" aria-expanded="true">
                                                Action
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                    href="new_faculty.php?faculty_id=<?php echo $row['faculty_id']; ?>">Edit</a>
                                                <div class="dropdown-divider"></div>
                                                <form method="post" action="tertiary_faculty_list.php"
                                                    style="display: inline;" class="delete-form">
                                                    <input type="hidden" name="delete_id"
                                                        value="<?php echo isset($row['faculty_id']) ? $row['faculty_id'] : ''; ?>">
                                                    <button type="submit" class="dropdown-item"
                                                        onclick="confirmDeletion()">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <p id="noRecordsMessage" style="display:none; color: black;" class="ml-1">No tertiary faculty
                            found.
                        </p>
                    </div>

                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <?php if ($current_segment > 1): ?>
                                <li class="page-item">
                                    <a class="page-link btn btn-success" href="?page=<?php echo ($start_page - 1); ?>"
                                        aria-label="Previous">
                                        <span aria-hidden="true">&laquo; Previous Segment</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($p = $start_page; $p <= $end_page; $p++): ?>
                                <li class="page-item <?php echo ($p == $page) ? 'active' : ''; ?>">
                                    <a class="page-link btn btn-success <?php echo ($p == $page) ? 'active' : ''; ?>"
                                        href="?page=<?php echo $p; ?>">
                                        <?php echo $p; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($end_page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link btn btn-success" href="?page=<?php echo $end_page + 1; ?>"
                                        aria-label="Next">
                                        <span aria-hidden="true">Next Segment &raquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </nav>
</div>

<script>
    $(document).ready(function () {
        $(document).on('submit', '.delete-form', function (e) {
            e.preventDefault();
            var form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: 'This action will permanently delete the faculty.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        type: 'POST',
                        url: 'tertiary_faculty_list.php',
                        data: $(form).serialize(),
                        success: function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Faculty deleted successfully.',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                window.location.href = 'tertiary_faculty_list.php';
                            });
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Error deleting faculty. Please try again.',
                            });
                        }
                    });
                }
            });
        });
    });
</script>
<script>
    document.getElementById('searchInput').addEventListener('keyup', function () {
        var searchValue = this.value.toLowerCase();
        var rows = document.querySelectorAll('#list tbody tr');
        var noRecordsMessage = document.getElementById('noRecordsMessage');
        var matchesFound = false;

        rows.forEach(function (row) {
            var cells = row.querySelectorAll('td');
            var matches = false;

            cells.forEach(function (cell) {
                if (cell.textContent.toLowerCase().includes(searchValue)) {
                    matches = true;
                }
            });

            if (matches) {
                row.style.display = '';
                matchesFound = true;
            } else {
                row.style.display = 'none';
            }
        });

        if (matchesFound) {
            noRecordsMessage.style.display = 'none';
        } else {
            noRecordsMessage.style.display = '';
        }
    });
</script>
<style>
    .list-group-item:hover {
        color: black !important;
        font-weight: 700 !important;
    }

    .content .main-header {
        max-height: 100vh;
        overflow-y: auto;
        scroll-behavior: smooth;
    }
</style>