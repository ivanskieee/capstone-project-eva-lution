<?php

include "handlers/report_handler.php";

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
                    <select name="" id="faculty_id" class="form-control form-control-sm">
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
                                    <option value="dean_self">Head Self-Evaluation</option>
                                    <option value="faculty_faculty">Peer to Peer Evaluation</option>
                                    <option value="faculty_head">Peer to Head Evaluation</option>
                                    <option value="head_faculty">Head to Faculty Evaluation</option>
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
    document.getElementById('category').addEventListener('change', function () {
        const selectedCategory = this.value;
        const facultyDropdown = document.getElementById('faculty_id');

        facultyDropdown.innerHTML = '<option value="">Select Faculty</option>';
        document.getElementById('fname').textContent = '';
        document.getElementById('ay').textContent = 'Select faculty to view year and semester.';
        document.getElementById('tse').textContent = 0;
        const ratingsTable = document.querySelector('#printable .table-responsive');
        ratingsTable.innerHTML = `<br><p class="text-center text-muted">Select faculty to view evaluation ratings.</p>`;

        fetch(`fetch_faculty_list.php?category=${selectedCategory}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    data.data.forEach(faculty => {
                        const option = document.createElement('option');
                        option.value = faculty.faculty_id;
                        option.textContent = faculty.fullname;
                        option.setAttribute('data-name', faculty.fullname); 
                        facultyDropdown.appendChild(option);
                    });
                } else {
                    console.error(data.message || 'Failed to fetch faculty list.');
                }
            })
            .catch(() => {
                console.error('Error fetching faculty list.');
            });
    });

    document.getElementById('faculty_id').addEventListener('change', function () {
        const facultyId = this.value;
        const facultyName = this.options[this.selectedIndex].getAttribute('data-name') || '';
        const selectedCategory = document.getElementById('category').value;

        document.getElementById('fname').textContent = facultyName;
        const academicYearDisplay = document.getElementById('ay');
        const ratingsTable = document.querySelector('#printable .table-responsive');
        const tse = document.getElementById('tse');

        ratingsTable.innerHTML = `<br><p class="text-center text-muted">Select faculty to view evaluation ratings.</p>`;
        academicYearDisplay.innerHTML = 'Select faculty to view year and semester.';
        tse.textContent = 0;

        if (facultyId) {
            fetch(`get_academic_info.php?faculty_id=${facultyId}&category=${selectedCategory}`)
                .then(response => response.json())
                .then(data => {
                    academicYearDisplay.innerHTML = data.status === 'success' ? `${data.year} - ${data.semester}` : 'No academic information available.';
                })
                .catch(() => {
                    academicYearDisplay.innerHTML = 'Error fetching academic information.';
                });

            fetch(`get_total_evaluated.php?faculty_id=${facultyId}&category=${selectedCategory}`)
                .then(response => response.text())
                .then(response => {
                    tse.textContent = response;
                })
                .catch(() => {
                    tse.textContent = 0;
                });

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
    container.innerHTML = ''; 
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
                <th width="10%" class="text-center mean-score-header"><b>Mean Score</b></th>
            </tr>
        </thead>
    `;

    const tbody = document.createElement('tbody');
    let totalMean = 0;
    let questionCount = 0;

    Object.keys(data.data).forEach(criteria => {
        tbody.innerHTML += `
            <tr class="bg-light">
                <td class="p-2" colspan="6"><strong>${criteria}</strong></td>
            </tr>
        `;

        data.data[criteria].forEach(row => {
            const totalResponses = row.rate1 + row.rate2 + row.rate3 + row.rate4;
            const meanScore = totalResponses ? 
                ((row.rate1 * 1 + row.rate2 * 2 + row.rate3 * 3 + row.rate4 * 4) / totalResponses).toFixed(2) : "0.00";
            
            totalMean += parseFloat(meanScore);
            questionCount++;

            tbody.innerHTML += row.question_type === 'text'
                ? `<tr class="bg-white">
                    <td colspan="6" class="p-2">
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
                    <td class="text-center p-2">
                        <div class="circle mean-score">${meanScore}</div>
                    </td>
                </tr>`;
        });
    });

    const totalMeanScore = questionCount ? (totalMean / questionCount).toFixed(2) : "0.00";
    tbody.innerHTML += `
    <tr class="bg-gradient-secondary total-mean-score-row">
        <td class="p-2 text-right align-middle" colspan="5"><strong>Total Mean Score:</strong></td>
        <td class="text-center p-2 align-middle">
            <div class="circle total-mean-score"><strong>${totalMeanScore}</strong></div>
        </td>
    </tr>
`;

    table.appendChild(tbody);
    container.appendChild(table);
}
</script>
<script>
document.getElementById('print-btn').addEventListener('click', function () {
    const printableContent = document.getElementById('printable').cloneNode(true);

    printableContent.querySelectorAll('td.text-left').forEach(td => {
        td.style.textAlign = 'left';
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

    logAuditAction('Print Report', 'Printed as PDF');
});

document.getElementById('export-csv-btn').addEventListener('click', async function () {
    const facultyId = document.getElementById('faculty_id')?.value;
    const selectedCategory = document.getElementById('category')?.value;

    if (!facultyId) {
        alert('Please select a faculty to export data.');
        return;
    }

    const table = document.querySelector('#printable table');
    if (!table) {
        alert('No table data available.');
        return;
    }

    let csvContent = `"Faculty Name","${document.getElementById('fname')?.textContent.trim() || 'N/A'}"\n`;
    csvContent += `"Academic Year","${document.getElementById('ay')?.textContent.trim() || 'N/A'}"\n`;
    csvContent += `"Total Evaluated","${document.getElementById('tse')?.textContent.trim() || 'N/A'}"\n\n`;
    csvContent += `"--- Table Data ---"\n\n`;

    try {
        // Fetch additional data and append it
        const additionalData = await fetchAdditionalData(facultyId, selectedCategory);
        csvContent += `"Additional Data"\n${additionalData.trim() !== '' ? additionalData : '"No additional data available."'}\n\n`;

        // Extract table headers
        const headers = Array.from(table.querySelectorAll('thead th')).map(th => `"${th.innerText.trim()}"`);
        const rows = Array.from(table.querySelectorAll('tbody tr')).map(row =>
            Array.from(row.querySelectorAll('td')).map(cell => `"${cell.innerText.trim()}"`).join(',')
        );

        csvContent += headers.join(',') + '\n' + rows.join('\n');

        // Download CSV
        downloadCSV(csvContent);

        // Log the audit action
        logAuditAction('Excel Report', 'Exported as CSV');

    } catch (error) {
        console.error('Error exporting:', error);
        alert('Error exporting data. Please try again.');
    }
});

// Function to download CSV
function downloadCSV(content) {
    const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', 'evaluation_report.csv');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
}

// Function to fetch additional data
async function fetchAdditionalData(facultyId, selectedCategory) {
    let url = selectedCategory === 'self'
        ? `get_self_eval.php?faculty_id=${facultyId}`
        : selectedCategory === 'dean_self'
            ? `get_dean_self_eval.php?faculty_id=${facultyId}`
            : `get_faculty_ratings.php?faculty_id=${facultyId}&category=${selectedCategory}`;

    try {
        const response = await fetch(url);
        const data = await response.json();
        
        if (data.status !== 'success' || !data.data) {
            return '"No additional data available."\n';
        }

        let additionalData = `"Criteria","Question","Type","Rate 1 (%)","Rate 2 (%)","Rate 3 (%)","Rate 4 (%)","Comments"\n`;
        Object.entries(data.data).forEach(([criteria, questions]) => {
            questions.forEach(q => {
                let cleanedComments = q.comments.map(c => c.trim()).filter(c => c).join('; ');
                additionalData += `"${criteria}","${q.question.trim()}","${q.question_type}","${q.rate1}","${q.rate2}","${q.rate3}","${q.rate4}","${cleanedComments}"\n`;
            });
        });

        return additionalData;
    } catch (error) {
        console.error('Error fetching additional data:', error);
        return '"Error fetching additional data."\n';
    }
}

function logAuditAction(action, details) {
    const userId = <?php echo $_SESSION['user']['id']; ?>; 
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'log_audit_action.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status !== 200) {
            console.error('Audit log failed:', xhr.responseText);
        }
    };

    xhr.send(`user_id=${userId}&action=${action}&details=${details}`);
}
</script>
<style>
    .comment-display {
        font-size: 1rem;
        font-style: italic;
        color: #4a4a4a;
    }
    .mean-score-header {
    white-space: nowrap; 
    }

    .total-mean-score-container {
        display: flex;
        align-items: center;
        justify-content: center; 
    }

    .circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #f8f9fa;
        border: 1px solid #ccc;
        text-align: center;
        font-weight: bold;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tables = document.querySelectorAll('table');
        
        tables.forEach(table => {
            const rows = table.querySelectorAll('tbody tr');
            let hasTextQuestion = false;

            rows.forEach(row => {
                if (row.querySelector('textarea')) {
                    hasTextQuestion = true;
                }
            });

            if (hasTextQuestion) {
                const headerCells = table.querySelectorAll('thead th');
                headerCells.forEach((cell, index) => {
                    if (index > 0) {
                        cell.style.display = 'none'; 
                    }
                });
            }
        });
    });
</script>
