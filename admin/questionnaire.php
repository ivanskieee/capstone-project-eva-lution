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
            <div class="row mb-3">
                <div class="col-8 col-md-4 ms-auto mt-3 mr-3">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search">
                </div>
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
                    <p id="noRecordsMessage" style="display:none; color: black;" class="ml-1">No records found.</p>
                </div>
            </div>
        </div>
    </div>
</nav>
<script>
    document.getElementById('searchInput').addEventListener('keyup', function () {
        var searchValue = this.value.toLowerCase();
        var rows = document.querySelectorAll('#list tbody tr');
        var noRecordsMessage = document.getElementById('noRecordsMessage');
        var matchesFound = false;

        rows.forEach(function (row) {
            var cells = row.querySelectorAll('td');
            var matches = false;

            cells.forEach(function (cell) {
                if (cell.textContent.toLowerCase().includes(searchValue)) {
                    matches = true;
                }
            });

            if (matches) {
                row.style.display = '';
                matchesFound = true;
            } else {
                row.style.display = 'none';
            }
        });

        if (matchesFound) {
            noRecordsMessage.style.display = 'none';
        } else {
            noRecordsMessage.style.display = '';
        }
    });
</script>
<style>
    .list-group-item:hover {
        color: black !important;
        font-weight: 700 !important;
    }

    body {
        overflow-y: hidden;
    }

    .main-header {
        max-height: 100vh;
        overflow-y: scroll;
        scrollbar-width: none;
    }

    .main-header::-webkit-scrollbar {
        display: none;
    }
</style>