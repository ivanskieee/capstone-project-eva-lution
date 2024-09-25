<?php
include 'handlers/class_handler.php';
?>

<nav class="main-header">
    <div class="col-lg-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-tools">
                    <a class="btn btn-block btn-sm btn-default btn-flat border-primary new_class"
                        href="manage_class.php">
                        <i class="fa fa-plus"></i> Add New
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered" id="list">
                    <colgroup>
                        <col width="5%">
                        <col width="60%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Class</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($classes as $row): ?>
                            <tr>
                                <th class="text-center"><?php echo $i++ ?></th>
                                <td>
                                    <b>
                                        <?php echo htmlspecialchars(ucwords($row['course'] . ' -' . ' ' . $row['level']. ' -' . ' ' . $row['section'])); ?>
                                    </b>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="javascript:void(0)"
                                            data-id='<?php echo isset($row['id']) ? $row['id'] : 0; ?>'
                                            class="btn btn-success  manage_class">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button type="button" class="btn btn-secondary  delete_class"
                                            data-id="<?php echo isset($row['id']) ? $row['id'] : 0; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</nav>

<script>
    $(document).ready(function () {
        // Initialize DataTable
        $('#list').dataTable();

        // Event for adding a new class
        $('.new_class').on('click', function () {
            uni_modal("New Class", "<?php echo $_SESSION['login_view_folder'] ?>manage_class.php");
        });

        // Event for managing a class (edit)
        $('.manage_class').on('click', function () {
            const classId = $(this).data('id');
            uni_modal("Manage Class", "<?php echo $_SESSION['login_view_folder'] ?>manage_class.php?id=" + classId);
        });

        // Event for deleting a class
        $('.delete_class').on('click', function () {
            const classId = $(this).data('id');
            _conf("Are you sure you want to delete this class?", "delete_class", [classId]);
        });
    });

    // Function to delete a class using AJAX
    function delete_class(id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_class',
            method: 'POST',
            data: { id: id },
            success: function (response) {
                if (response == 1) {
                    alert_toast("Data successfully deleted", 'success');
                    setTimeout(function () {
                        location.reload(); // Reload the page after deletion
                    }, 1500);
                } else {
                    alert_toast("Failed to delete data", 'error');
                }
            },
            error: function (xhr, status, error) {
                alert_toast("An error occurred: " + error, 'error');
            }
        });
    }
</script>