<?php
include "handlers/student_handler.php";
include '../database/connection.php';
include "handlers/verify_actions_handler.php";

$query = "
    SELECT 
        school_id, email, firstname, lastname, subject, section
    FROM student_list
";
$stmt = $conn->prepare($query);
$stmt->execute();
$student_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Assume $total_records is the total number of records from the database
$total_records = count($student_data); // Replace this with the actual query to count rows
$records_per_page = 5; // Number of records per page
$total_pages = ceil($total_records / $records_per_page);

// Current page
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page = max(1, min($page, $total_pages)); // Ensure the page is within valid range

// Calculate offset for database query
$offset = ($page - 1) * $records_per_page;

// Fetch only the required records for the current page
$student_data = array_slice($student_data, $offset, $records_per_page);

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
                    Student List</h2>
            </div>
            <div class="card card-outline card-success">
                <div class="row mb-3">
                    <div class="col-8 col-md-4 ms-auto mt-3 mr-3">
                        <input type="text" id="searchInput" class="form-control form-control-sm"
                            placeholder="Search Students">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="student_list">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Section</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded dynamically via AJAX -->
                            </tbody>
                        </table>
                        <p id="noRecordsMessage" style="display:none; color: black;" class="ml-1">No student found.</p>
                    </div>
                </div>
                <nav aria-label="Page navigation">
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
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        type: 'POST',
                        url: 'student_list.php',
                        data: $(form).serialize(),
                        success: function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Student deleted successfully.',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                window.location.href = 'student_list.php';
                            });
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Error deleting the item. Please try again.',
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
                url: 'search_student_list.php',
                method: 'POST',
                data: {
                    search: search,
                    page: page
                },
                dataType: 'json',
                success: function(response) {
                    $('#student_list tbody').html(response.tableData); // Update table rows
                    $('#pagination-links').html(response.pagination); // Update pagination
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
        max-height: 100vh;
        overflow-y: auto;
        scroll-behavior: smooth;
    }

    .subject-item {
        display: inline-block;
        margin-right: 8px;
        margin-bottom: 4px;
        font-weight: bold;
        text-transform: uppercase;
        color: #333;
    }
</style>