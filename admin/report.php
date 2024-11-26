<?php

include "handlers/report_handler.php";

?>
<nav class="main-header">
    <div class="col-lg-12 mt-3">
        <div class="callout callout-success">
            <div class="d-flex w-100 justify-content-center align-items-center">
                <label for="faculty">Select Faculty</label>
                <div class=" mx-2 col-md-4">
                    <select name="" id="faculty_id" class="form-control form-control-sm select2">
                        <option value="">Select Faculty</option>
                        <?php
                        $stmt = $conn->query("SELECT faculty_id, firstname, lastname FROM college_faculty_list");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $fullName = htmlspecialchars($row['firstname'] . ' ' . $row['lastname']);
                            echo '<option value="' . $row['faculty_id'] . '" data-name="' . $fullName . '">' . $fullName . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="d-flex justify-content-end w-100">
                    <button class="btn btn-sm btn-success bg-gradient-success mr-3" id="print-btn"><i
                            class="fa fa-print"></i> Print</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="callout callout-success">
                    <div class="list-group" id="class-list">

                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="callout callout-success" id="printable">
                    <div>
                        <h3 class="text-center">Evaluation Report</h3>
                        <hr>
                        <table width="100%">
                            <tr>
                                <td width="50%">
                                    <p><b>Faculty: <span id="fname"></span></b></p>
                                </td>
                                <td width="50%">
                                    <p><b>Academic Year: <span id="ay">
                                                Semester</span></b></p>
                                </td>
                            </tr>
                            <tr>
                            </tr>
                        </table>
                        <p class=""><b>Total Student Evaluated: <span id="tse">
                                </span></b></p>
                    </div>
                    <fieldset class="border border-success p-2 w-100">
                        <legend class="w-auto">Rating Legend</legend>
                        <p>4 = Strongly Agree, 3 = Agree, 2 = Disagree, 1 = Strongly Disagree</p>
                    </fieldset>
                    <div class="table-responsive">
                        <?php foreach ($criteriaList as $row): ?>
                            <table class="table table-condensed wborder">
                                <thead>
                                    <tr class="bg-gradient-secondary">
                                        <th class=" p-1"><b><?php echo $row['criteria'] ?></b></th>
                                        <th width="5%" class="text-center">1</th>
                                        <th width="5%" class="text-center">2</th>
                                        <th width="5%" class="text-center">3</th>
                                        <th width="5%" class="text-center">4</th>
                                    </tr>
                                </thead>
                                <tbody class="tr-sortable">
                                    <?php
                                    $hasQuestions = false;

                                    if (is_array($questions)) {
                                        foreach ($questions as $qRow) {
                                            if (is_array($qRow) && $qRow['criteria_id'] == $row['criteria_id']) {
                                                $hasQuestions = true;
                                                ?>
                                                <tr class="bg-white">
                                                    <td class="p-1" width="20%">
                                                        <?= htmlspecialchars($qRow['question']) ?>
                                                        <input type="hidden" name="qid[]" value="<?= $qRow['question_id'] ?>">
                                                    </td>
                                                    <?php for ($c = 0; $c < 4; $c++): ?>
                                                        <td class="text-center">
                                                            <div class="icheck-success d-inline">
                                                                <input type="radio" name="qid[<?= $qRow['question_id'] ?>][]"
                                                                    id="qradio<?= $qRow['question_id'] . '_' . $c ?>" value="<?= $c + 1 ?>">
                                                                <label for="qradio<?= $qRow['question_id'] . '_' . $c ?>"></label>
                                                            </div>
                                                        </td>
                                                    <?php endfor; ?>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                    if (!$hasQuestions): ?>
                                        <tr>
                                            <td colspan="7" class="text-center"></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
</nav>
<style>
    .list-group-item:hover {
        color: black !important;
        font-weight: 700 !important;
    }

    body {
        overflow-y: hidden;
    }

    .main-header {
        max-height: 90vh;
        overflow-y: scroll;
        scrollbar-width: none;
    }

    .main-header::-webkit-scrollbar {
        display: none;
    }

    .circle {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #d4edda;
        color: #155724;
        font-size: 14px;
        font-weight: bold;
        border: 1px solid #155724;
    }
</style>
<noscript>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table.wborder tr,
        table.wborder td,
        table.wborder th {
            border: 1px solid gray;
            padding: 3px
        }

        table.wborder thead tr {
            background: #6c757d linear-gradient(180deg, #828a91, #6c757d) repeat-x !important;
            color: #fff;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }
    </style>
</noscript>
<script>
    document.getElementById('faculty_id').addEventListener('change', function () {
        // Get the selected option
        const selectedOption = this.options[this.selectedIndex];
        // Get the faculty name from the `data-name` attribute
        const facultyName = selectedOption.getAttribute('data-name') || '';
        // Update the span content
        document.getElementById('fname').textContent = facultyName;
    });
</script>
<script>
    $(document).ready(function () {
        $('#faculty_id').change(function () {
            const facultyId = $(this).val();
            if (facultyId) {
                $.ajax({
                    url: 'get_total_evaluated.php',
                    method: 'GET',
                    data: { faculty_id: facultyId },
                    success: function (response) {
                        $('#tse').text(response);
                    },
                    error: function () {
                        alert('Failed to fetch data.');
                    }
                });
            } else {
                $('#tse').text(0);
            }
        });
    });
</script>
<script>
    document.getElementById('faculty_id').addEventListener('change', function () {
        const facultyId = this.value;

        const ratingsTable = document.querySelector('#printable .table-responsive');
        
        if (facultyId) {
            fetch(`get_faculty_ratings.php?faculty_id=${facultyId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        ratingsTable.innerHTML = ''; // Clear previous data

                        // Generate table structure
                        const table = document.createElement('table');
                        table.className = 'table table-condensed wborder';

                        // Add table header
                        const thead = `
                        <thead>
                            <tr class="bg-gradient-secondary">
                                <th class="p-1"><b>Question</b></th>
                                <th width="5%" class="text-center">1</th>
                                <th width="5%" class="text-center">2</th>
                                <th width="5%" class="text-center">3</th>
                                <th width="5%" class="text-center">4</th>
                            </tr>
                        </thead>`;
                        table.innerHTML = thead;

                        // Add table body
                        const tbody = document.createElement('tbody');
                        data.data.forEach(row => {
                            const questionRow = `
                            <tr class="bg-white">
                                <td class="p-1" width="20%">${row.question}</td>
                                <td class="text-center">
                                    <div class="circle">${row.rate1}%</div>
                                </td>
                                <td class="text-center">
                                    <div class="circle">${row.rate2}%</div>
                                </td>
                                <td class="text-center">
                                    <div class="circle">${row.rate3}%</div>
                                </td>
                                <td class="text-center">
                                    <div class="circle">${row.rate4}%</div>
                                </td>
                            </tr>`;
                            tbody.innerHTML += questionRow;
                        });
                        table.appendChild(tbody);

                        // Append the complete table to the ratings container
                        ratingsTable.appendChild(table);
                    } else {
                        console.error(data.message);
                    }
                })
                .catch(error => console.error('Error fetching ratings:', error));
        } else {
            // Reset the table to its normal state
            ratingsTable.innerHTML = `<br>
                <p class="text-center text-muted">Select a faculty to view evaluation ratings.</p>
            `;
        }
    });
</script>
