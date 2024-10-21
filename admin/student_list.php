<?php
include "handlers/student_handler.php";
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
                                            <form method="post" action="student_list.php" style="display: inline;" class="delete-form">
                                                <input type="hidden" name="delete_id"
                                                    value="<?php echo isset($row['student_id']) ? $row['student_id'] : ''; ?>">
                                                <button type="submit" class="dropdown-item" onclick="confirmDeletion()">Delete</button>
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
