<?php

include 'handlers/subject_handler.php';

?>
<nav class="main-header">
    <div class="col-lg-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-tools">
                    <a class="btn btn-block btn-sm btn-default btn-flat border-primary new_subject"
                        href="manage_subject.php"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table tabe-hover table-bordered" id="list">
                    <colgroup>
                        <col width="5%">
                        <col width="15%">
                        <col width="30%">
                        <col width="40%">
                        <col width="15%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Code</th>
                            <th>Subject</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($subjects as $row): ?>
                            <tr>
                                <th class="text-center"><?php echo $i++ ?></th>
                                <td><b><?php echo $row['code'] ?></b></td>
                                <td><b><?php echo $row['subject'] ?></b></td>
                                <td><b><?php echo $row['description'] ?></b></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="javascript:void(0)" data-id='<?php echo isset($row['id']) ?>'
                                            class="btn btn-success btn-flat manage_subject">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-secondary btn-flat delete_subject"
                                            data-id="<?php echo isset($row['id']) ?>">
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