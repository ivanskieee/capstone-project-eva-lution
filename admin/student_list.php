<?php
include "handlers/student_handler.php"; // Handle student-related operations
?>

<nav class="main-header">
    <div class="col-lg-12 mt-3">
        <div class="card card-outline card-success">
            <div class="card-header">
                <div class="card-tools">
                    <a class="btn btn-block btn-sm btn-default btn-flat border-success" href="new_student.php">
                        <i class="fa fa-plus"></i> Add New Student
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="student_list">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>School ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Current Class</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($students as $row): ?>
                                <tr>
                                    <th class="text-center"><?php echo $i++; ?></th>
                                    <td><b><?php echo htmlspecialchars($row['school_id']); ?></b></td>
                                    <td><b><?php echo htmlspecialchars(ucwords($row['firstname'] . ' ' . $row['lastname'])); ?></b>
                                    </td>
                                    <td><b><?php echo htmlspecialchars($row['email']); ?></b></td>
                                    <td><b><?php echo htmlspecialchars($row['class_name']); ?></b></td>
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                            class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle"
                                            data-toggle="dropdown" aria-expanded="true">
                                            Action
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="new_student.php?student_id=<?php echo $row['student_id']; ?>"">Edit</a>
                                            <div class="dropdown-divider"></div>
                                            <form method="post" action="student_list.php" style="display: inline;">
                                                <input type="hidden" name="delete_id"
                                                    value="<?php echo isset($row['student_id']) ? $row['student_id'] : ''; ?>">
                                                <button type="submit" class="dropdown-item"
                                                    onclick="return confirm('Are you sure you want to delete this student member?');">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    $(document).ready(function () {
        // Initialize DataTable
        $('#student_list').DataTable();

        // View student details
        $('.view_student').click(function () {
            var id = $(this).data('id');
            uni_modal("<i class='fa fa-id-card'></i> Student Details", "<?php echo $_SESSION['login_view_folder']; ?>view_student.php?id=" + id);
        });

        // Delete student with confirmation
        $('.delete_student').click(function () {
            var id = $(this).data('id');
            _conf("Are you sure you want to delete this student?", "delete_student", [id]);
        });
    });

    function delete_student(id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_student',
            method: 'POST',
            data: { id: id },
            success: function (resp) {
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success');
                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                } else {
                    alert_toast("An error occurred.", 'danger');
                    end_load();
                }
            }
        });
    }
</script>