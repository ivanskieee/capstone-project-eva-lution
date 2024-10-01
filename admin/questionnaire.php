<?php
include "handlers/questionnaire_handler.php";

?>
<nav class="main-header">
    <div class="col-lg-12">
        <div class="card card-outline card-primary">
            <div class="card-header mt-4">
                <!-- <div class="card-tools">
                    <a class="btn btn-block btn-sm btn-default btn-flat border-primary new_academic"
                        href="manage_questionnaire_academic.php"><i class="fa fa-plus"></i> Add New</a>
                </div> -->
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered" id="list">
                    <colgroup>
                        <col width="5%">
                        <col width="35%">
                        <col width="15%">
                        <col width="15%">
                        <col width="15%">
                        <col width="15%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Academic Year</th>
                            <th>Semester</th>
                            <th>Questions</th>
                            <th>Answered</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($questionnaires as $row):
                            // Check if 'id' exists in $row
                            if (isset($row['id'])) {
                                // Fetch the number of questions
                                $question = $conn->query("SELECT * FROM question_list WHERE academic_id = {$row['id']}")->num_rows;

                                // Fetch the number of answers
                                $answers = $conn->query("SELECT * FROM evaluation_list WHERE academic_id = {$row['id']}")->num_rows;
                            } else {
                                // Handle the case when 'id' is not set
                                $question = 0;
                                $answers = 0;
                            }
                            ?>
                            <tr>
                                <th class="text-center"><?php echo $i++ ?></th>
                                <td><b><?php echo $row['year'] ?></b></td>
                                <td><b><?php echo $row['semester'] ?></b></td>
                                <td class="text-center"><b><?php echo $question; ?></b></td>
                                <td class="text-center"><b><?php echo $answers; ?></b></td>
                                <td class="text-center">
                                    <button type="button"
                                        class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle"
                                        data-toggle="dropdown" aria-expanded="true">
                                        Action
                                    </button>
                                    <div class="dropdown-menu" style="">
                                        <a class="dropdown-item manage_questionnaire"
                                            href="manage_questionnaire.php">Manage</a>
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