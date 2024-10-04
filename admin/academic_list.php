<?php
include 'handlers/academic_handler.php';

?>
<nav class="main-header">
    <div class="col-lg-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-tools">
                    <a class="btn btn-block btn-sm btn-default btn-flat border-primary new_academic"
                        href="manage_academic.php"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table tabe-hover table-bordered" id="list">
                    <colgroup>
                        <col width="5%">
                        <col width="25%">
                        <col width="25%">
                        <col width="15%">
                        <col width="15%">
                        <col width="15%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Year</th>
                            <th>Semester</th>
                            <th>System Default</th>
                            <th>Evaluation Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($academics as $row):
                            ?>
                            <tr>
                                <th class="text-center"><?php echo $i++ ?></th>
                                <td><b><?php echo $row['year'] ?></b></td>
                                <td><b><?php echo $row['semester'] ?></b></td>
                                <td class="text-center">
                                    <?php if ($row['is_default'] == 0): ?>
                                        <button type="button"
                                            class="btn btn-secondary bg-gradient-secondary col-sm-4 btn-flat btn-sm px-1 py-0 make_default"
                                            data-id="<?php echo isset($row['id']) ?>">No</button>
                                    <?php else: ?>
                                        <button type="button"
                                            class="btn btn-primary bg-gradient-primary col-sm-4 btn-flat btn-sm px-1 py-0">Yes</button>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($row['status'] == 0): ?>
                                        <span class="badge badge-secondary">Not yet Started</span>
                                    <?php elseif ($row['status'] == 1): ?>
                                        <span class="badge badge-success">Starting</span>
                                    <?php elseif ($row['status'] == 2): ?>
                                        <span class="badge badge-primary">Closed</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="javascript:void(0)" data-id='<?php echo isset($row['id']) ?>'
                                            class="btn btn-success btn-flat manage_academic">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="post" action="academic_list.php" style="display: inline;">
                                            <input type="hidden" name="delete_id"
                                                value="<?php echo isset($row['academic_id']) ? $row['academic_id'] : ''; ?>">
                                            <button type="submit" class="btn btn-secondary btn-flat delete_academic"
                                                onclick="return confirm('Are you sure you want to delete this academic year?');">
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
</nav>