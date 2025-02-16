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
?>

<div class="content">
    <nav class="main-header">
        <div class="col-lg-12 mt-3">
            <div class="container mt-3">
                <div class="col-12 mb-5">
                    <h2 class="text-start"
                        style="font-size: 1.8rem; font-weight: bold; color: #4a4a4a; border-bottom: 2px solid #ccc; padding-bottom: 5px;">
                        Audit Logs</h2>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center">System Activity Log</h2>
                        <p class="text-center">Below is the list of actions performed by admins:</p>

                        <div class="table-responsive mt-4">
                            <table class="table table-bordered table-striped">
                                <thead class="table-success">
                                    <tr>
                                        <th>Admin</th>
                                        <th>Action</th>
                                        <th>Details</th>
                                        <th>Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($logs as $log): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($log['firstname'] . ' ' . $log['lastname']) ?>
                                                (<?= htmlspecialchars($log['email']) ?>)</td>
                                            <td><?= htmlspecialchars($log['action']) ?></td>
                                            <td><?= htmlspecialchars($log['details']) ?></td>
                                            <td><?= htmlspecialchars($log['timestamp']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                <!-- Previous Button -->
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link btn btn-success" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo; Previous</span>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <!-- Page Numbers -->
                                <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                                    <li class="page-item <?= ($p == $page) ? 'active' : ''; ?>">
                                        <a class="page-link btn btn-success <?= ($p == $page) ? 'active' : ''; ?>"
                                           href="?page=<?= $p; ?>">
                                            <?= $p; ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Next Button -->
                                <?php if ($page < $total_pages): ?>
                                    <li class="page-item">
                                        <a class="page-link btn btn-success" href="?page=<?= $page + 1 ?>" aria-label="Next">
                                            <span aria-hidden="true">Next &raquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>

                    </div>
                </div>
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
</style>
