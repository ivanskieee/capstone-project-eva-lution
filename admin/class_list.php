<?php
include 'handlers/class_handler.php';
?>

<nav class="main-header">
    <div class="col-lg-12 mt-3">
        <div class="card card-outline card-success">
            <div class="card-header">
                <div class="card-tools">
                    <a class="btn btn-block btn-sm btn-default btn-flat border-success new_class"
                        href="manage_class.php">
                        <i class="fa fa-plus"></i> Add New
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
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
                                            <?php echo htmlspecialchars(ucwords($row['course'] . ' -' . ' ' . $row['level'] . ' -' . ' ' . $row['section'])); ?>
                                        </b>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="manage_class.php?class_id=<?php echo $row['class_id']; ?>"
                                                class="btn btn-success  manage_class">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="post" action="class_list.php" style="display: inline;"
                                                class="delete-form">
                                                <input type="hidden" name="delete_id"
                                                    value="<?php echo isset($row['class_id']) ? $row['class_id'] : ''; ?>">
                                                <button type="submit" class="btn btn-secondary  delete_class">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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
        $(document).on('submit', '.delete-form', function (e) {
            e.preventDefault();
            var form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: 'This action will permanently delete the class.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: 'class_list.php',  
                        data: $(form).serialize(),
                        success: function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Class has been deleted.',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                window.location.href = 'class_list.php'; 
                            });
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Failed to delete the class!',
                            });
                        }
                    });
                }
            });
        });
    });
</script>