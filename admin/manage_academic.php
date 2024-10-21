<?php
include 'handlers/academic_handler.php';

?>
<nav class="main-header">
    <div class="col-lg-12 mt-5">
        <div class="card">
            <div class="card-body">
                <form action="" id="manage-academic" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="academic_id"
                        value="<?php echo isset($academics['academic_id']) ? $academics['academic_id'] : '' ?>">
                    <div id="msg" class="form-group"></div>
                    <div class="row">
                        <div class="col-md-6 border-right">
                            <div class="form-group">
                                <label for="year" class="control-label">Year</label>
                                <input type="text" class="form-control form-control-sm" name="year" id="year"
                                    value="<?php echo isset($academics['year']) ? $academics['year'] : '' ?>"
                                    placeholder="(2019-2020)" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="semester" class="control-label">Semester</label>
                                <input type="number" class="form-control form-control-sm" name="semester" id="semester"
                                    value="<?php echo isset($academics['semester']) ? $academics['semester'] : '' ?>"
                                    required>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($status)): ?>
                        <div class="form-group">
                            <label for="status" class="control-label">Status</label>
                            <select name="status" id="status" class="form-control form-control-sm">
                                <option value="0" <?php echo $status == 0 ? "selected" : "" ?>>Pending</option>
                                <option value="1" <?php echo $status == 1 ? "selected" : "" ?>>Started</option>
                                <option value="2" <?php echo $status == 2 ? "selected" : "" ?>>Closed</option>
                            </select>
                        </div>
                    <?php endif; ?>
                    <hr>
                    <div class="col-lg-12 text-right justify-content-center d-flex">
                        <button type="submit" class="btn btn-success btn-secondary-blue mr-3">
                            <?php echo isset($id) ? 'Update' : 'Submit'; ?>
                        </button>
                        <button type="button" class="btn btn-secondary"
                            onclick="window.location.href = './academic_list.php';">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</nav>
<script>
    $(document).ready(function () {
        $('#manage-academic').on('submit', function (e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: 'manage_academic.php',  // Adjust the URL as needed
                data: formData,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Academic year saved successfully.',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.href = 'academic_list.php';  // Redirect after saving
                    });

                    $('#manage-academic')[0].reset();  // Reset the form after saving
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
    });
</script>