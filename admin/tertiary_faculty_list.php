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
                        <a class="btn btn-block btn-sm btn-default btn-flat border-success"
                            href="new_faculty.php"><i class="fa fa-plus"></i> Add New Faculty</a>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-8 col-md-4 ms-auto mt-3 mr-3">
                        <input type="text" id="searchInput" class="form-control form-control-sm"
                            placeholder="Search Faculty">
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
                                <!-- Faculty Data will be loaded here dynamically -->
                            </tbody>
                        </table>
                        <p id="noRecordsMessage" style="display:none; color: black;" class="ml-1">No faculty found.</p>
                    </div>
                </div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center" id="pagination-links">
                        <!-- Pagination links will be generated dynamically -->
                    </ul>
                </nav>
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
    $(document).ready(function() {
        function updateTable(page = 1, search = '') {
            $.ajax({
                url: 'search_faculty_list.php',
                method: 'POST',
                data: {
                    search: search,
                    page: page
                },
                dataType: 'json',
                success: function(response) {
                    $('#list tbody').html(response.tableData);
                    $('#pagination-links').html(response.pagination); 
                },
                error: function() {
                    console.error('Failed to fetch search results.');
                }
            });
        }

        $('#searchInput').on('keyup', function() {
            updateTable(1, $(this).val());
        });

        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            updateTable($(this).data('page'), $('#searchInput').val());
        });

        updateTable(1);
    });
</script>
<style>
    .list-group-item:hover {
        color: black !important;
        font-weight: 700 !important;
    }

    .content .main-header {
        max-height: 90vh;
        overflow-y: auto;
        scroll-behavior: smooth;
    }
</style>