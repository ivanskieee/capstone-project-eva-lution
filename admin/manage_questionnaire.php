<?php
include 'handlers/questionnaire_handler.php';
?>

<div class="content">
    <nav class="main-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 mt-5">
                    <div class="card card-success">
                        <div class="card-header">
                            <b><?php echo isset($questionToEdit) ? 'Edit Question' : 'Add New Question'; ?></b>
                        </div>
                        <div class="card-body">
                            <form id="manage-question"
                                action="manage_questionnaire.php?academic_id=<?php echo $academic_id; ?>&sector=<?php echo $sector; ?>"
                                method="POST">
                                <input type="hidden" name="sector" value="<?= htmlspecialchars($sector) ?>">
                                <input type="hidden" name="question_id"
                                    value="<?php echo isset($questionToEdit['question_id']) ? $questionToEdit['question_id'] : ''; ?>">

                                <input type="hidden" name="academic_id"
                                    value="<?php echo isset($_GET['academic_id']) ? $_GET['academic_id'] : ''; ?>">


                                <div class="form-group">
                                    <label for="sector">Sector</label>
                                    <select name="sector" id="sector" class="form-control" required>
                                        <option value="student_faculty" <?= ($sector === 'student_faculty') ? 'selected' : '' ?>>Student to Faculty</option>
                                        <option value="faculty_faculty" <?= ($sector === 'faculty_faculty') ? 'selected' : '' ?>>Peer to Peer</option>
                                        <option value="faculty_dean" <?= ($sector === 'faculty_dean') ? 'selected' : '' ?>>
                                            Peer to Head</option>
                                        <option value="dean_faculty" <?= ($sector === 'dean_faculty') ? 'selected' : '' ?>>
                                            Head
                                            to Faculty</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="question_type">Question Type</label>
                                    <select name="question_type" id="question_type" class="form-control" required>
                                        <option value="mcq" <?= (isset($questionToEdit['question_type']) && $questionToEdit['question_type'] == 'mcq') ? 'selected' : '' ?>>Ratings
                                        </option>
                                        <option value="text" <?= (isset($questionToEdit['question_type']) && $questionToEdit['question_type'] == 'text') ? 'selected' : '' ?>>Text Answer
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group" id="text-field-container" style="display: none;">
                                    <label for="text_answer">Text Answer</label>
                                    <textarea name="text_answer" id="text_answer" cols="30" rows="4"
                                        class="form-control"
                                        placeholder="Enter the text answer here"><?php echo isset($questionToEdit['text_answer']) ? $questionToEdit['text_answer'] : ''; ?></textarea>
                                </div>

                                <div class="form-group" id="criteria-container">
                                    <label for="criteria_id">Select Criteria:</label>
                                    <select name="criteria_id" id="criteria_id" class="form-control" required>
                                        <option value=""></option>
                                        <?php foreach ($criteriaList as $criteria): ?>
                                            <option value="<?= $criteria['criteria_id'] ?>"
                                                <?= (isset($questionToEdit['criteria_id']) && $questionToEdit['criteria_id'] == $criteria['criteria_id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($criteria['criteria']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group" id="question-container">
                                    <label for="question">Question</label>
                                    <input type="text" name="question" id="question" class="form-control" required
                                        placeholder="Enter the question here"
                                        value="<?php echo isset($questionToEdit['question']) ? htmlspecialchars($questionToEdit['question']) : ''; ?>">
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-end w-100">
                                <button type="submit" class="btn btn-sm btn-success btn-flat bg-gradient-success mx-1"
                                    form="manage-question" name="submit_question">Save</button>
                                <input type="hidden" id="academic_id"
                                    value="<?php echo htmlspecialchars($academic_id); ?>">
                                <button class="btn btn-sm btn-flat btn-secondary bg-gradient-secondary mx-1"
                                    type="button" onclick="cancelAction();">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8 mt-2">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <b>Evaluation Questionnaires</b>
                        </div>
                        <div class="card-body">
                            <fieldset class="border border-success p-2 w-100">
                                <legend class="w-auto">Rating Legend</legend>
                                <p>4 = Strongly Agree, 3 = Agree, 2 = Disagree, 1 = Strongly Disagree</p>
                            </fieldset>
                            <div class="clear-fix mt-2"></div>
                            <?php foreach ($criteriaList as $row): ?>
                                <table class="table table-condensed">
                                    <thead>
                                        <tr class="bg-gradient-secondary">
                                            <th colspan="2" class="p-1">
                                                <b><?php echo htmlspecialchars($row['criteria']); ?></b>
                                            </th>
                                            <th class="text-center">4</th>
                                            <th class="text-center">3</th>
                                            <th class="text-center">2</th>
                                            <th class="text-center">1</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tr-sortable">
                                        <?php
                                        $hasQuestions = false;
                                        if (is_array($questions)) {
                                            foreach ($questions as $qRow) {
                                                if (is_array($qRow) && $qRow['criteria_id'] == $row['criteria_id'] && $qRow['academic_id'] == $academic_id) {
                                                    $hasQuestions = true;
                                                    ?>
                                                    <tr class="bg-white">
                                                        <td class="p-1 text-center" width="5%">
                                                            <span class="btn-group dropright">
                                                                <span type="button" class="btn" data-toggle="dropdown"
                                                                    aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-ellipsis-v"></i>
                                                                </span>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item edit_question"
                                                                        href="manage_questionnaire.php?question_id=<?php echo htmlspecialchars($qRow['question_id']); ?>&academic_id=<?php echo htmlspecialchars($academic_id); ?>&sector=<?php echo htmlspecialchars($sector); ?>">Edit</a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <form method="post" action="manage_questionnaire.php"
                                                                        style="display: inline;" class="delete-form">
                                                                        <input type="hidden" name="delete_id"
                                                                            value="<?= htmlspecialchars($qRow['question_id']) ?>">
                                                                        <input type="hidden" name="sector"
                                                                            value="<?= htmlspecialchars($sector) ?>">
                                                                        <!-- Pass sector here -->
                                                                        <button type="submit" class="dropdown-item">Delete</button>
                                                                    </form>
                                                                </div>
                                                            </span>
                                                        </td>
                                                        <td class="p-1" width="20%">
                                                            <?= htmlspecialchars($qRow['question']) ?>
                                                            <input type="hidden" name="qid[]"
                                                                value="<?= htmlspecialchars($qRow['question_id']) ?>">
                                                        </td>
                                                        <?php if ($qRow['question_type'] == 'mcq'): ?>
                                                            <?php for ($c = 0; $c < 4; $c++): ?>
                                                                <td class="text-center">
                                                                    <div class="icheck-success d-inline">
                                                                        <input type="radio"
                                                                            name="qid[<?= htmlspecialchars($qRow['question_id']) ?>][]"
                                                                            id="qradio<?= htmlspecialchars($qRow['question_id']) . '_' . $c ?>"
                                                                            value="<?= $c + 1 ?>">
                                                                        <label
                                                                            for="qradio<?= htmlspecialchars($qRow['question_id']) . '_' . $c ?>"></label>
                                                                    </div>
                                                                </td>
                                                            <?php endfor; ?>
                                                        <?php elseif ($qRow['question_type'] == 'text'): ?>
                                                            <!-- Add comment textarea in the same row -->
                                                            <td colspan="4" class="text-center">
                                                                <textarea name="comment[<?= htmlspecialchars($qRow['question_id']) ?>]"
                                                                    class="form-control" rows="3"
                                                                    placeholder="Enter your answer"></textarea>
                                                            </td>
                                                        <?php endif; ?>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        }
                                        if (!$hasQuestions): ?>
                                            <tr>
                                                <td colspan="7" class="text-center">No questions available for the selected
                                                    criteria
                                                    and academic year.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>

<!-- JavaScript and jQuery Code -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        // Submit form for adding/editing questions
        $('#manage-question').on('submit', function (e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: 'manage_questionnaire.php',
                data: formData,
                success: function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Question saved successfully.',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        var academic_id = $('#academic_id').val();
                        var sector = $('#sector').val();
                        window.location.href = 'manage_questionnaire.php?academic_id=' + academic_id + '&sector=' + sector;
                    });
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

        // Delete question with confirmation
        $(document).on('submit', '.delete-form', function (e) {
            e.preventDefault();
            var form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: 'This action will permanently delete the question.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: 'manage_questionnaire.php',
                        data: $(form).serialize(),
                        success: function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Question has been deleted.',
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                var academic_id = $('#academic_id').val();
                                var sector = $('#sector').val();
                                window.location.href = 'manage_questionnaire.php?academic_id=' + academic_id + '&sector=' + sector;
                            });
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Failed to delete the question!',
                            });
                        }
                    });
                }
            });
        });

        // Handle sector change
        $('#sector').on('change', function () {
            var sector = $('#sector').val();
            var academic_id = $('#academic_id').val();
            window.location.href = 'manage_questionnaire.php?academic_id=' + academic_id + '&sector=' + sector;
        });
    });

    function cancelAction() {
        var academicId = document.getElementById('academic_id').value;
        window.location.href = 'manage_questionnaire.php?academic_id=' + academicId;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const questionTypeSelect = document.getElementById('question_type');
        const criteriaContainer = document.getElementById('criteria-container');
        const questionContainer = document.getElementById('question-container');
        const questionField = document.getElementById('question');

        function toggleFields() {
            const selectedType = questionTypeSelect.value;

            if (selectedType === 'text') {
                // Change the question field to a textarea for Text Answer
                questionField.setAttribute('type', 'text');
                questionField.setAttribute('placeholder', 'Enter the text answer here');
                questionField.setAttribute('rows', '4');
                questionField.value = '';  // Clear the input field
            } else if (selectedType === 'mcq') {
                // Change the question field to a regular input for MCQ
                questionField.setAttribute('type', 'text');
                questionField.setAttribute('placeholder', 'Enter your question for ratings here');
                questionField.removeAttribute('rows');
            }

            // Always display the criteria container for both types
            criteriaContainer.style.display = 'block';
        }

        // Initial toggle based on the loaded value
        toggleFields();

        // Add event listener to handle changes in question type
        questionTypeSelect.addEventListener('change', toggleFields);
    });
</script>

<style>
    .list-group-item:hover {
        color: black !important;
        font-weight: 700 !important;
    }

    .content .main-header {
        max-height: 90vh;
        overflow-y: auto;
        scroll-behavior: smooth;
    }
</style>
<script>
    // Check if the criteria contains any 'text' type questions
    document.addEventListener('DOMContentLoaded', function() {
        const tables = document.querySelectorAll('table');
        
        tables.forEach(table => {
            const rows = table.querySelectorAll('tbody tr');
            let hasTextQuestion = false;

            // Check if there's a text-type question in the current table
            rows.forEach(row => {
                if (row.querySelector('textarea')) {
                    hasTextQuestion = true;
                }
            });

            if (hasTextQuestion) {
                // Hide the rating columns (1 to 4)
                const headerCells = table.querySelectorAll('thead th');
                headerCells.forEach((cell, index) => {
                    if (index > 0) {
                        cell.style.display = 'none'; // Hide columns 1 to 4
                    }
                });
            }
        });
    });
</script>