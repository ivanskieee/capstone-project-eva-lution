<?php
include 'handlers/class_handler.php';

?>
<nav class="main-header">
    <div class="col-lg-12 mt-5">
        <div class="card">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" id="manage_class">
                    <input type="hidden" name="id" value="<?php echo isset($class_id) ? $class_id : '' ?>">
                    <div id="msg" class="form-group"></div>
                    <div class="row">
                        <div class="col-md-6 border-right">
                            <div class="form-group">
                                <label for="curriculum" class="control-label">Course</label>
                                <input type="text" class="form-control form-control-sm" name="course" id="course"
                                    value="<?php echo isset($course) ? $course : '' ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="level" class="control-label">Year Level</label>
                                <input type="text" class="form-control form-control-sm" name="level" id="level"
                                    value="<?php echo isset($level) ? $level : '' ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="section" class="control-label">Section</label>
                                <input type="text" class="form-control form-control-sm" name="section" id="section"
                                    value="<?php echo isset($section) ? $section : '' ?>" required>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-lg-12 text-right justify-content-center d-flex">
                        <button type="submit" class="btn btn-success btn-secondary-blue mr-3">
                            <?php echo isset($id) ? 'Update' : 'Submit'; ?>
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="window.location.href = './class_list.php';">
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
        $('#manage-class').submit(function (e) {
            e.preventDefault();
            start_load()
            $('#msg').html('')
            $.ajax({
                url: 'ajax.php?action=save_class',
                method: 'POST',
                data: $(this).serialize(),
                success: function (resp) {
                    if (resp == 1) {
                        alert_toast("Data successfully saved.", "success");
                        setTimeout(function () {
                            location.reload()
                        }, 1750)
                    } else if (resp == 2) {
                        $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Class already exist.</div>')
                        end_load()
                    }
                }
            })
        })
    })

</script>