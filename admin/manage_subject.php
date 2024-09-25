<?php
include 'handlers/subject_handler.php';

?>
<nav class="main-header">
    <div class="col-lg-12 mt-5">
        <div class="card">
            <div class="card-body">
                <form method="POST" id="manage_subject">
                    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                    <div id="msg" class="form-group"></div>
                    <div class="row">
                        <div class="col-md-6 border-right">
                            <div class="form-group">
                                <label for="code" class="control-label">Subject Code</label>
                                <input type="text" class="form-control form-control-sm" name="code" id="code"
                                    value="<?php echo isset($code) ? $code : '' ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="subject" class="control-label">Subject</label>
                                <input type="text" class="form-control form-control-sm" name="subject" id="subject"
                                    value="<?php echo isset($subject) ? $subject : '' ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="description" class="control-label">Description</label>
                                <textarea name="description" id="description" cols="30" rows="4" class="form-control"
                                    required><?php echo isset($description) ? $description : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-lg-12 text-right justify-content-center d-flex">
                        <button type="submit" class="btn btn-success btn-secondary-blue mr-3">
                            <?php echo isset($id) ? 'Update' : 'Submit'; ?>
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="window.location.href = './subject_list.php';">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</nav>
