<?php
include 'handlers/admin_link_handler.php';

// Define the number of records per page
$records_per_page = 4;

// Get total number of records
$total_stmt = $conn->query("SELECT COUNT(*) FROM audit_logs");
$total_records = $total_stmt->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);

// Get current page number
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page = max(1, min($page, $total_pages)); // Ensure page is within a valid range

// Calculate offset for query
$offset = ($page - 1) * $records_per_page;

// Fetch records for current page
$stmt = $conn->prepare("
    SELECT audit_logs.*, users.email, users.firstname, users.lastname 
    FROM audit_logs 
    JOIN users ON audit_logs.user_id = users.id 
    ORDER BY timestamp DESC 
    LIMIT :offset, :limit
");
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pagination range
$range = 5;
$start_page = max(1, $page - floor($range / 2));
$end_page = min($total_pages, $start_page + $range - 1);
?>

<div class="content">
    <nav class="main-header">
        <div class="col-lg-12 mt-3">
            <div class="col-12 mb-3">
                <h2 class="text-start"
                    style="font-size: 1.8rem; font-weight: bold; color: #4a4a4a; border-bottom: 2px solid #ccc; padding-bottom: 5px;">
                    Audit Logs</h2>
            </div>
            <div class="card card-outline card-success">
                <div class="row mb-3">
                    <div class="col-8 col-md-4 ms-auto mt-3 mr-3">
                        <input type="text" id="searchAudit" class="form-control form-control-sm"
                            placeholder="Search Logs">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="audit_table">
                            <thead>
                                <tr>
                                    <th>Admin</th>
                                    <th>Action</th>
                                    <th>Details</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data loaded via AJAX -->
                            </tbody>
                        </table>
                        <p id="noAuditMessage" style="display:none; color: black;" class="ml-1">No logs found.</p>
                    </div>
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center" id="audit-pagination">
                        <!-- Pagination links will be generated dynamically -->
                    </ul>
                </nav>
            </div>
        </div>
    </nav>
</div>

<style>
    .content .main-header {
        max-height: 90vh;
        overflow-y: auto;
        scroll-behavior: smooth;
    }

    .pagination {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        padding: 10px;
        gap: 5px;
    }

    .pagination a {
        padding: 8px 12px;
        text-decoration: none;
        border: 1px solid #ddd;
        background: white;
        color: black;
        border-radius: 5px;
    }

    .pagination a.active {
        background: green;
        color: white;
    }

    .pagination a:hover {
        background: #8fd19e;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .text-break {
        word-break: break-word;
        white-space: normal;
    }
    .pagination .page-link {
        color: black; /* Standard text color */
        background-color: white; /* White background */
        border: 1px solid #ddd; /* Light gray border */
        padding: 6px 12px; /* Adjust spacing */
    }

    .pagination .page-item.active .page-link {
        background-color: #f8f9fa; /* Light gray for active page */
        color: black; /* Text remains black */
        border-color: #ddd; /* Match border */
    }

    .pagination .page-link:hover {
        background-color: #f1f1f1; /* Slight hover effect */
        color: black;
    }
</style>

<script>
    $(document).ready(function () {
        function updateAuditTable(page = 1, search = '') {
            $.ajax({
                url: 'search_audit_logs.php',
                method: 'POST',
                data: {
                    search: search,
                    page: page
                },
                dataType: 'json',
                success: function (response) {
                    $('#audit_table tbody').html(response.tableData); // Update logs
                    $('#audit-pagination').html(response.pagination); // Update pagination
                },
                error: function () {
                    console.error('Failed to fetch logs.');
                }
            });
        }

        $('#searchAudit').on('keyup', function () {
            updateAuditTable(1, $(this).val());
        });

        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            updateAuditTable($(this).data('page'), $('#searchAudit').val());
        });

        updateAuditTable(1);
    });
</script>