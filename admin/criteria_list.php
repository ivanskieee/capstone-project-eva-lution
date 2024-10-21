<?php
include 'handlers/criteria_handler.php';

?>
<nav class="main-header">
    <div class="col-lg-12 mt-3">
        <div class="card card-outline card-success">
            <div class="card-header"></div>
            <div class="card-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card card-outline card-success">
                                <div class="card-header"><b>Criteria Form</b></div>
                                <div class="card-body">
                                    <form action="" id="manage-criteria" method="POST">
                                        <input type="hidden" name="criteria_id"
                                            value="<?php echo isset($criteria_to_edit['criteria_id']) ? $criteria_to_edit['criteria_id'] : ''; ?>">
                                        <div class="form-group">
                                            <label for="criteria">Criteria</label>
                                            <input type="text" name="criteria" class="form-control form-control-sm"
                                                value="<?php echo isset($criteria_to_edit['criteria']) ? $criteria_to_edit['criteria'] : ''; ?>"
                                                required>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-end w-100">
                                        <button class="btn btn-sm btn-success btn-flat bg-gradient-success mx-1"
                                            form="manage-criteria" type="submit">Save</button>
                                        <button class="btn btn-sm btn-flat btn-secondary bg-gradient-secondary mx-1"
                                            form="manage-criteria" type="reset"
                                            onclick="window.location.href = './criteria_list.php';">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="callout callout-success">
                                <div class="d-flex justify-content-between w-100">
                                    <label for=""><b>Criteria List</b></label>
                                    <button class="btn btn-sm btn-success btn-flat bg-gradient-success mx-1"
                                        form="order-criteria">Save Order</button>
                                </div>
                                <hr>
                                <ul class="list-group btn col-md-8" id="ui-sortable-list">
                                    <?php foreach ($criterias as $row): ?>
                                        <li class="list-group-item text-left">
                                            <span class="btn-group dropright float-right">
                                                <span type="button" class="btn" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </span>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="criteria_list.php?criteria_id=<?php echo $row['criteria_id']; ?>">Edit</a>
                                                    <form method="POST" action="criteria_list.php" class="delete-form">
                                                        <input type="hidden" name="delete_id"
                                                            value="<?php echo $row['criteria_id']; ?>">
                                                        <button class="dropdown-item delete-button"
                                                            type="submit">Delete</button>
                                                    </form>
                                                </div>
                                            </span>
                                            <i class="fa fa-bars"></i> <?php echo ucwords($row['criteria']); ?>
                                            <input type="hidden" name="criteria_id" id="criteria_id"
                                                value="<?php echo $row['criteria_id']; ?>">
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<style>
    .dropright a:hover {
        color: black !important;
    }
</style>
<script>
    $(document).ready(function () {
        $('#manage-criteria').on('submit', function (e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: 'criteria_list.php',
                data: formData,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Criteria saved successfully.',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.href = 'criteria_list.php';
                    });

                    $('#manage-criteria')[0].reset();
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                    });
                }
            });
        });

        $(document).on('submit', '.delete-form', function (e) {
            e.preventDefault(); 
            var form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: 'This action will permanently delete the criteria.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var criteria_id = $('#criteria_id').val();
                    $.ajax({
                        type: 'POST',
                        url: 'criteria_list.php', 
                        data: $(form).serialize(),
                        success: function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Criteria has been deleted.',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                
                                window.location.href = 'criteria_list.php' 
                            });
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Failed to delete the criteria!',
                            });
                        }
                    });
                }
            });
        });
    });
</script>