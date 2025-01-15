<?php
include "handlers/verify_accounts_handler.php";
include "handlers/verify_actions_handler.php";
?>

<div class="content">
    <nav class="main-header">
        <div class="container mt-3">
            <div class="col-12 mb-5">
                <h2 class="text-start"
                    style="font-size: 1.8rem; font-weight: bold; color: #4a4a4a; border-bottom: 2px solid #ccc; padding-bottom: 5px;">
                    Pending Accounts</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header">
                            <h3>Pending Student Registrations</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">

                                <?php
                                if (isset($_GET['status'])) {
                                    $status = $_GET['status'];
                                    if ($status == 'confirmed') {
                                        echo "<div class='alert alert-success fade-alert'>Student has been confirmed and added to the system.</div>";
                                    } elseif ($status == 'rejected') {
                                        echo "<div class='alert alert-danger fade-alert'>Student has been rejected and removed from the system.</div>";
                                    } elseif ($status == 'bulk_confirmed') {
                                        echo "<div class='alert alert-success fade-alert'>All selected students have been confirmed and added to the system.</div>";
                                    } elseif ($status == 'bulk_removed') {
                                        echo "<div class='alert alert-danger fade-alert'>All selected students have been removed from the system.</div>";
                                    } elseif ($status == 'no_selection') {
                                        echo "<div class='alert alert-warning fade-alert'>No students were selected for the action.</div>";
                                    }
                                }
                                ?>

                                <form method="POST" action="verify_accounts.php">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="select-all"></th>
                                                <th>Student ID</th>
                                                <th>Email</th>
                                                <th>Name</th>
                                                <th>Subject</th>
                                                <th>Section</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($result as $row): ?>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="student_ids[]"
                                                            value="<?= htmlspecialchars($row['school_id']) ?>">
                                                    </td>
                                                    <td><?= htmlspecialchars($row['school_id']) ?></td>
                                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                                    <td><?= htmlspecialchars(ucwords($row['firstname'] . ' ' . $row['lastname'])) ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($row['subject']) ?></td>
                                                    <td><?= htmlspecialchars($row['section']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <button type="submit" name="action" value="bulk_confirm"
                                        class="btn btn-success">Confirm
                                        All</button>
                                    <button type="submit" name="action" value="bulk_remove"
                                        class="btn btn-danger">Remove
                                        All</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>

<script>
    window.onload = function () {
        // Add the 'select all' checkbox event listener
        document.getElementById('select-all').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('input[name="student_ids[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        // Fade-out alert after a delay
        var alertElement = document.querySelector('.fade-alert');
        if (alertElement) {
            // Delay before starting the fade-out effect (3 seconds in this case)
            setTimeout(function () {
                alertElement.classList.add('fade-out');

                // After the fade-out effect completes (5 seconds), redirect to verify_accounts.php
                setTimeout(function () {
                    window.location.href = 'verify_accounts.php'; // Redirect to verify_accounts.php
                }, 1000); // 5000 ms (5 seconds) to match the fade duration
            }, 1000); // Initial delay before fade-out (3 seconds)
        }
    };
</script>
<style>
    .content .main-header {
        max-height: 100vh;
        overflow-y: auto;
        scroll-behavior: smooth;
    }

    .fade-alert {
        opacity: 1;
        visibility: visible;
        transition: opacity 1s ease-in-out, visibility 0s linear 1s;
        /* Initially fully visible, and will fade out */
    }

    .fade-alert.fade-out {
        opacity: 0;
        visibility: hidden;
    }
</style>