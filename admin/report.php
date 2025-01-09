<?php

include "handlers/report_handler.php";

?>
<nav class="main-header">
    <div class="col-lg-12 mt-3">
        <div class="col-12 mb-3">
            <h2 class="text-start"
                style="font-size: 1.8rem; font-weight: bold; color: #4a4a4a; border-bottom: 2px solid #ccc; padding-bottom: 5px;">
                Evaluation Report</h2>
        </div>
        <div class="callout callout-success" id="facultySelection">
            <div class="d-flex w-100 justify-content-center align-items-center">
                <label for="faculty">Select Faculty</label>
                <div class="mx-2 col-md-4">
                    <select name="" id="faculty_id" class="form-control form-control-sm select2">
                        <option value="">Select Faculty</option>
                        <?php
                        $stmt = $conn->query("
                            SELECT faculty_id, firstname, lastname, 'faculty' AS type FROM college_faculty_list
                            UNION
                            SELECT head_id AS faculty_id, firstname, lastname, 'dean' AS type FROM head_faculty_list
                        ");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $fullName = htmlspecialchars($row['firstname'] . ' ' . $row['lastname']);
                            echo '<option value="' . $row['faculty_id'] . '" data-type="' . $row['type'] . '" data-name="' . $fullName . '">' . $fullName . '</option>';
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
                        <div class="d-flex w-100 justify-content-center align-items-center">
                            <label for="category">Select Category</label>
                            <div class="mx-2 col-md-8">
                                <select id="category" class="form-control form-control-sm">
                                    <option value="faculty">Student to Faculty</option>
                                    <option value="self">Self Faculty</option>
                                    <option value="dean_self">Self Head Faculty</option>
                                    <option value="faculty_faculty">Faculty to Faculty</option>
                                    <option value="faculty_head">Faculty to Head</option>
                                    <option value="head_faculty">Head to Faculty</option>
                                </select>
                            </div>
                        </div>
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
                        <p class=""><b>Total Evaluated: <span id="tse">
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
                                                    <?php if ($qRow['question_type'] == 'mcq'): ?>
                                                        <!-- Multiple-choice question -->
                                                        <?php for ($c = 0; $c < 4; $c++): ?>
                                                            <td class="text-center">
                                                                <div class="icheck-success d-inline">
                                                                    <input type="radio" name="qid[<?= $qRow['question_id'] ?>][]"
                                                                        id="qradio<?= $qRow['question_id'] . '_' . $c ?>" value="<?= $c + 1 ?>">
                                                                    <label for="qradio<?= $qRow['question_id'] . '_' . $c ?>"></label>
                                                                </div>
                                                            </td>
                                                        <?php endfor; ?>
                                                    <?php elseif ($qRow['question_type'] == 'text'): ?>
                                                        <!-- Open-ended question -->
                                                        <td colspan="4" class="text-center">
                                                            <textarea name="comment[<?= $qRow['question_id'] ?>]" class="form-control"
                                                                rows="3" placeholder="Enter your answer"></textarea>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                    if (!$hasQuestions): ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No questions available.</td>
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
        const facultyId = this.value;
        const facultyName = this.options[this.selectedIndex].getAttribute('data-name') || '';
        const selectedCategory = document.getElementById('category').value;

        document.getElementById('fname').textContent = facultyName;
        const academicYearDisplay = document.getElementById('ay');
        const ratingsTable = document.querySelector('#printable .table-responsive');
        const tse = document.getElementById('tse');

        // Reset content
        ratingsTable.innerHTML = `<br><p class="text-center text-muted">Select faculty to view evaluation ratings.</p>`;
        academicYearDisplay.innerHTML = 'Select faculty to view year and semester.';
        tse.textContent = 0;

        if (facultyId) {
            // Fetch academic year
            fetch(`get_academic_info.php?faculty_id=${facultyId}`)
                .then(response => response.json())
                .then(data => {
                    academicYearDisplay.innerHTML = data.status === 'success' ? `${data.year} - ${data.semester}` : 'No academic information available.';
                })
                .catch(() => {
                    academicYearDisplay.innerHTML = 'Error fetching academic information.';
                });

            // Fetch total evaluated
            fetch(`get_total_evaluated.php?faculty_id=${facultyId}&category=${selectedCategory}`)
                .then(response => response.text())
                .then(response => {
                    tse.textContent = response;
                })
                .catch(() => {
                    tse.textContent = 0;
                });

            // Fetch category-specific data
            if (selectedCategory === 'self') {
                fetch(`get_self_eval.php?faculty_id=${facultyId}`)
                    .then(response => response.json())
                    .then(data => {
                        renderSelfEval(data, ratingsTable);
                    })
                    .catch(() => {
                        ratingsTable.innerHTML = `<br><p class="text-center text-muted">Failed to fetch self-evaluation data.</p>`;
                    });
            } else if (selectedCategory === 'dean_self') {
                fetch(`get_dean_self_eval.php?faculty_id=${facultyId}`)
                    .then(response => response.json())
                    .then(data => {
                        renderSelfEval(data, ratingsTable);
                    })
                    .catch(() => {
                        ratingsTable.innerHTML = `<br><p class="text-center text-muted">Failed to fetch dean self-evaluation data.</p>`;
                    });
            } else {
                fetch(`get_faculty_ratings.php?faculty_id=${facultyId}&category=${selectedCategory}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            renderFacultyEval(data, ratingsTable);
                        } else {
                            ratingsTable.innerHTML = `<br><p class="text-center text-muted">${data.message || 'No evaluation ratings available.'}</p>`;
                        }
                    })
                    .catch(() => {
                        ratingsTable.innerHTML = `<br><p class="text-center text-muted">Failed to fetch evaluation ratings.</p>`;
                    });
            }
        }
    });

    function renderSelfEval(data, container) {
        if (data.status === 'success') {
            container.innerHTML = `
            <div class="mb-3">
                <label for="skills" class="form-label">Skills (1-5):</label>
                <input type="number" id="skills" name="skills" class="form-control" value="${data.skills}" readonly>
            </div>
            <div class="mb-3">
                <label for="performance" class="form-label">Performance (1-5):</label>
                <input type="number" id="performance" name="performance" class="form-control" value="${data.performance}" readonly>
            </div>
            <div class="mb-3">
                <label for="comments" class="form-label">Comments:</label>
                <textarea id="comments" name="comments" class="form-control" rows="4" readonly>${data.comments}</textarea>
            </div>`;
        } else {
            container.innerHTML = `<br><p class="text-center text-muted">No self-evaluation data available.</p>`;
        }
    }

    function renderFacultyEval(data, container) {
        container.innerHTML = ''; // Clear previous data
        const table = document.createElement('table');
        table.className = 'table table-condensed wborder';
        table.innerHTML = `
    <thead>
        <tr class="bg-gradient-secondary">
            <th class="p-1"><b>Question</b></th>
            <th width="5%" class="text-center">1</th>
            <th width="5%" class="text-center">2</th>
            <th width="5%" class="text-center">3</th>
            <th width="5%" class="text-center">4</th>
        </tr>
    </thead>`;
        const tbody = document.createElement('tbody');
        data.data.forEach(row => {
            tbody.innerHTML += row.question_type === 'text'
                ? `<tr class="bg-white">
            <td colspan="5">
                <div><strong>${row.question}</strong></div>
                <textarea name="comment[${row.question_id}]" class="form-control mt-2" rows="5" readonly>${row.comments ? row.comments.join('\n') : ''}</textarea>
            </td>
        </tr>`
                : `<tr class="bg-white">
            <td class="p-1" width="20%">${row.question}</td>
            <td class="text-center"><div class="circle">${row.rate1}%</div></td>
            <td class="text-center"><div class="circle">${row.rate2}%</div></td>
            <td class="text-center"><div class="circle">${row.rate3}%</div></td>
            <td class="text-center"><div class="circle">${row.rate4}%</div></td>
        </tr>`;
        });
        table.appendChild(tbody);
        container.appendChild(table);
    }
</script>
<script>
    document.getElementById('print-btn').addEventListener('click', function () {
        const printableContent = document.getElementById('printable').innerHTML;
        console.log(printableContent);  // Debugging line

        // Create a new window for printing
        const printWindow = window.open('', '', 'width=800,height=600');

        if (printWindow) {
            // Write the content into the new window
            printWindow.document.open();
            printWindow.document.write(`
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Print Evaluation Report</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    table, th, td { border: 1px solid black; padding: 8px; text-align: center; }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .text-left { text-align: left; }
                    .wborder { border: 1px solid gray; }
                </style>
            </head>
            <body>
                <h1 class="text-center">Evaluation Report</h1>
                ${printableContent}
            </body>
            </html>
        `);
            printWindow.document.close();

            // Make sure the print window is printed
            printWindow.onload = function () {
                printWindow.print();
                printWindow.onafterprint = function () {
                    printWindow.close();
                };
            };
        } else {
            console.error('Unable to open the print window. It may have been blocked by the browser.');
        }
    });
</script>