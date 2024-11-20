<?php
include "handlers/verify_accounts_handler.php";
include "handlers/verify_actions_handler.php";
?>

<nav class="main-header">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h3>Pending Student Registrations</h3>
                    </div>
                    <div class="card-body">

                        <?php
                        if (isset($_GET['status'])) {
                            $status = $_GET['status'];
                            if ($status == 'confirmed') {
                                echo "<div class='alert alert-success'>Student has been confirmed and added to the system.</div>";
                            } elseif ($status == 'rejected') {
                                echo "<div class='alert alert-danger'>Student has been rejected and removed from the system.</div>";
                            }
                        }
                        ?>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Email</th>
                                    <th>Name</th>
                                    <th>Subject</th>
                                    <th>Section</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($result as $row) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['school_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                    echo "<td>" . htmlspecialchars(ucwords($row['firstname'] . ' ' . $row['lastname'])) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['section']) . "</td>";
                                    echo "<td>
                                        <form method='POST' action='verify_accounts.php'>
                                            <input type='hidden' name='school_id' value='" . htmlspecialchars($row['school_id']) . "'>
                                            <button type='submit' name='action' value='confirm' class='btn btn-success'>Confirm</button>
                                            <button type='submit' name='action' value='remove' class='btn btn-danger'>Reject</button>
                                        </form>
                                      </td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
