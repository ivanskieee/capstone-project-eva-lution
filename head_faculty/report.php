<?php

include "handlers/report_handler.php";

$userDepartment = $_SESSION['user']['department']; // Ensure the 'department' key exists

// Prepare the SQL statement to fetch faculty members with the same department
$stmt = $conn->prepare("
    SELECT faculty_id, firstname, lastname 
    FROM college_faculty_list 
    WHERE department = :department
");
$stmt->execute(['department' => $userDepartment]);
?>

<div class="content">
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
                    <button class="btn btn-sm btn-success bg-gradient-success mr-3" id="print-btn">
                        <i class="fa fa-print"></i> Print
                    </button>
                    <button class="btn btn-sm btn-info bg-gradient-success mr-3" id="export-csv-btn">
                        <i class="fa fa-file-csv"></i> Export to CSV
                    </button>
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
                                    <option value="" selected disabled>Select Category</option>
                                    <option value="faculty">Student to Faculty Evaluation</option>
                                    <option value="self">Faculty Self-Evaluation</option>
                                    <option value="faculty_faculty">Peer to Peer Evaluation</option>
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
</div>

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
            fetch(`get_academic_info.php?faculty_id=${facultyId}&category=${selectedCategory}`)
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

    // Table header
    table.innerHTML = `
        <thead>
            <tr class="bg-gradient-secondary">
                <th class="p-2"><b>Evaluation Questions</b></th>
                <th width="5%" class="text-center">1</th>
                <th width="5%" class="text-center">2</th>
                <th width="5%" class="text-center">3</th>
                <th width="5%" class="text-center">4</th>
            </tr>
        </thead>
    `;

    const tbody = document.createElement('tbody');

    Object.keys(data.data).forEach(criteria => {
        // Add a criteria header row that aligns with questions
        tbody.innerHTML += `
            <tr class="bg-light">
                <td class="p-2" colspan="5"><strong>${criteria}</strong></td>
            </tr>
        `;

        // Add questions under the respective criteria
        data.data[criteria].forEach(row => {
            tbody.innerHTML += row.question_type === 'text'
                ? `<tr class="bg-white">
                    <td colspan="5" class="p-2">
                        <strong>${row.question}</strong>
                        <div class="comment-display mt-2">${row.comments ? row.comments.join('<br>') : '<em>No comments provided.</em>'}</div>
                    </td>
                </tr>`
                : `<tr class="bg-white">
                    <td class="p-2">${row.question}</td>
                    <td class="text-center p-2"><div class="circle">${row.rate1}%</div></td>
                    <td class="text-center p-2"><div class="circle">${row.rate2}%</div></td>
                    <td class="text-center p-2"><div class="circle">${row.rate3}%</div></td>
                    <td class="text-center p-2"><div class="circle">${row.rate4}%</div></td>
                </tr>`;
        });
    });

    table.appendChild(tbody);
    container.appendChild(table);
}
</script>
<script>
document.getElementById('print-btn').addEventListener('click', function () {
    const printableContent = document.getElementById('printable').cloneNode(true);

    printableContent.querySelectorAll('td.text-left').forEach(td => {
        td.style.textAlign = 'left'; // Ensure left alignment for all question cells
    });

    printableContent.querySelectorAll('input, textarea').forEach(el => {
            const value = el.value || el.innerHTML;
            const parent = el.parentElement;
            const span = document.createElement('span');
            span.textContent = value;
            parent.replaceChild(span, el);
    });

    const printWindow = window.open('', '', 'width=800,height=600');

    if (printWindow) {
        printWindow.document.open();
        printWindow.document.write(`
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Eval-Report</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    table, th, td { border: 1px solid black; padding: 8px; }
                    .text-center { text-align: center; }
                    .text-left { text-align: left; }
                    .wborder { border: 1px solid gray; }
                    .comment-display {
                        font-size: 1rem;
                        font-style: italic;
                        color: #4a4a4a;
                        text-align: left;
                        margin-top: 10px;
                    }
                </style>
            </head>
            <body>
                ${printableContent.innerHTML}
            </body>
            </html>
        `);
        printWindow.document.close();
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

// Export to CSV
document.getElementById('export-csv-btn').addEventListener('click', function () {
    const facultyId = document.getElementById('faculty_id').value;
    const selectedCategory = document.getElementById('category').value;

    if (!facultyId) {
        alert('Please select a faculty to export data.');
        return;
    }

    const table = document.querySelector('#printable table');

    // Start CSV content with headers for metadata
    let csvContent = `Faculty Name:,${document.getElementById('fname').textContent}\n`;
    csvContent += `Academic Year:,${document.getElementById('ay').textContent}\n`;
    csvContent += `Total Evaluated:,${document.getElementById('tse').textContent}\n\n`;

    // Add a separator before table data
    csvContent += '--- Table Data ---\n';

    // Fetch additional data and include it in the export
    fetchAdditionalData(facultyId, selectedCategory, (additionalData) => {
        if (additionalData.trim() !== '') {
            csvContent += `\nAdditional Data:\n${additionalData}`;
        }

        // Extract table headers
        const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.innerText);
        csvContent += headers.join(',') + '\n';

        // Extract table rows
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const cells = Array.from(row.querySelectorAll('td')).map(cell => cell.innerText.trim());
            csvContent += cells.join(',') + '\n';
        });

        // Create a downloadable CSV file
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'evaluation_report.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
});

// Function to fetch additional data
function fetchAdditionalData(facultyId, selectedCategory, callback) {
    let urls = [];
    if (selectedCategory === 'self') {
        urls.push(`get_self_eval.php?faculty_id=${facultyId}`);
    } else if (selectedCategory === 'dean_self') {
        urls.push(`get_dean_self_eval.php?faculty_id=${facultyId}`);
    } else {
        urls.push(`get_faculty_ratings.php?faculty_id=${facultyId}&category=${selectedCategory}`);
    }

    // Fetch all URLs and collect data
    Promise.all(urls.map(url => fetch(url).then(res => res.json())))
        .then(responses => {
            let additionalData = '';
            responses.forEach(response => {
                if (response.status === 'success') {
                    response.data.forEach(item => {
                        const formattedRow = Object.entries(item)
                            .map(([key, value]) => `${key}: ${value}`)
                            .join(', ');
                        additionalData += formattedRow + '\n';
                    });
                } else {
                    additionalData += 'No additional data available.\n';
                }
            });
            callback(additionalData);
        })
        .catch(() => {
            callback('Error fetching additional data.\n');
        });
}
</script>
<style>
    .comment-display {
        font-size: 1rem;
        font-style: italic;
        color: #4a4a4a;
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
