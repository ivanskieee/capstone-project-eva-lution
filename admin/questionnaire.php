<?php
include "handlers/questionnaire_handler.php";

?>
<nav class="main-header">
    <div class="col-lg-12 mt-3">
        <div class="card card-outline card-success">
            <div class="card-header mt-4">
                <!-- <div class="card-tools">
                    <a class="btn btn-block btn-sm btn-default btn-flat border-primary new_academic"
                        href="manage_questionnaire_academic.php"><i class="fa fa-plus"></i> Add New</a>
                </div> -->
            </div>
            <div class="card-body">
                <div class="table-responsive">
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
                                $question = $row['total_questions'];
                                $answers = $row['total_answers'];
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
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item manage_questionnaire"
                                                href="manage_questionnaire.php?academic_id=<?php echo $row['academic_id']; ?>">Manage</a>
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