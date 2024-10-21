<?php
include 'handlers/class_handler.php';

?>
<nav class="main-header">
    <div class="col-lg-12 mt-5">
        <div class="card">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" id="manage-class">
                    <input type="hidden" name="class_id"
                        value="<?php echo isset($classes['class_id']) ? $classes['class_id'] : '' ?>">
                    <div id="msg" class="form-group"></div>
                    <div class="row">
                        <div class="col-md-6 border-right">
                            <div class="form-group">
                                <label for="curriculum" class="control-label">Course</label>
                                <input type="text" class="form-control form-control-sm" name="course" id="course"
                                    value="<?php echo isset($classes['course']) ? $classes['course'] : '' ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="level" class="control-label">Year Level</label>
                                <input type="text" class="form-control form-control-sm" name="level" id="level"
                                    value="<?php echo isset($classes['level']) ? $classes['level'] : '' ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="section" class="control-label">Section</label>
                                <input type="text" class="form-control form-control-sm" name="section" id="section"
                                    value="<?php echo isset($classes['section']) ? $classes['section'] : '' ?>"
                                    required>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-lg-12 text-right justify-content-center d-flex">
                        <button type="submit" class="btn btn-success btn-secondary-blue mr-3">
                            <?php echo isset($id) ? 'Update' : 'Submit'; ?>
                        </button>
                        <button type="button" class="btn btn-secondary"
                            onclick="window.location.href = './class_list.php';">
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
        $('#manage-class').on('submit', function (e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: 'manage_class.php',  // Adjust the URL as needed
                data: formData,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Class saved successfully.',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.href = 'class_list.php';  // Redirect after saving
                    });

                    $('#manage-class')[0].reset();  // Reset the form after saving
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