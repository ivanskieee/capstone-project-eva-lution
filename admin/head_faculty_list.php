<?php
include "handlers/head_faculty_handler.php";
include '../database/connection.php';

$query = "
    SELECT 
        head_id, email, firstname, lastname
    FROM head_faculty_list
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

<nav class="main-header">
    <div class="col-lg-12 mt-3">
        <div class="col-12 mb-3">
            <h2 class="text-start"
                style="font-size: 1.8rem; font-weight: bold; color: #4a4a4a; border-bottom: 2px solid #ccc; padding-bottom: 5px;">
                Head Faculty List</h2>
        </div>
        <div class="card card-outline card-success">
            <div class="card-header">
                <div class="card-tools">
                    <a class="btn btn-block btn-sm btn-default btn-flat border-success" href="new_head_faculty.php"><i
                            class="fa fa-plus"></i> Add New Head Faculty</a>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-8 col-md-4 ms-auto mt-3 mr-3">
                    <input type="text" id="searchInput" class="form-control form-control-sm"
                        placeholder="Search Head Faculties">
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="list">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>School ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = $offset + 1; // Adjust numbering based on the current page
                            foreach ($head_faculties as $row): ?>
                                <tr>
                                    <th class="text-center"><?php echo $i++; ?></th>
                                    <td><b><?php echo $row['school_id']; ?></b></td>
                                    <td><b><?php echo ucwords($row['firstname'] . ' ' . $row['lastname']); ?></b></td>
                                    <td><b><?php echo $row['email']; ?></b></td>
                                    <td class="text-center">
                                        <a href="new_head_faculty.php?head_id=<?php echo $row['head_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="#" class="btn btn-sm btn-danger" onclick="confirmDeletion(<?php echo $row['head_id']; ?>)">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p id="noRecordsMessage" style="display:none; color: black;" class="ml-1">No head faculty found.</p>
                </div>
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
</nav>
<script>
    $(document).ready(function() {
        $(document).on('submit', '.delete-form', function(e) {
            e.preventDefault();
            var form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: 'This action will permanently delete the head faculty.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        type: 'POST',
                        url: 'head_faculty_list.php',
                        data: $(form).serialize(),
                        success: function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Head Faculty deleted successfully.',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                window.location.href = 'head_faculty_list.php';
                            });
                        },
                        error: function() {
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
    $(document).ready(function() {
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var searchValue = this.value.toLowerCase();
            var rows = document.querySelectorAll('#list tbody tr');
            var noRecordsMessage = document.getElementById('noRecordsMessage');
            var matchesFound = false;

            rows.forEach(function(row) {
                var cells = row.querySelectorAll('td');
                var matches = false;

                cells.forEach(function(cell) {
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
    });
</script>
<style>
    .list-group-item:hover {
        color: black !important;
        font-weight: 700 !important;
    }

    body {
        overflow-y: hidden;
    }

    html {
        scroll-behavior: smooth;
    }

    .main-header {
        max-height: 100vh;
        overflow-y: scroll;
        scrollbar-width: none;
        scroll-behavior: smooth;
    }

    .main-header::-webkit-scrollbar {
        display: none;
    }
</style>