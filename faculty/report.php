<?php

include "handlers/report_handler_faculty_faculty.php";

?>
<div class="content">
    <nav class="main-header">
        <div class="col-lg-12 mt-5">
            <div class="row">
                <div class="col-md-12 mb-1">
                    <!-- <div class="d-flex justify-content-end w-100">
                    <button class="btn btn-sm btn-success bg-gradient-success mr-3" id="print-btn"><i
                            class="fa fa-print"></i> Print</button>
                </div> -->
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
                                            <th class=" p-1"><b></b></th>
                                        </tr>
                                    </thead>
                                    <tbody class="tr-sortable" id="ratings-table-body">
                                        <?php
                                        $hasQuestions = false;

                                        if (is_array($questions)) {
                                            foreach ($questions as $qRow) {
                                                if (is_array($qRow) && $qRow['criteria_id'] == $row['criteria_id']) {
                                                    $hasQuestions = true;
                                                    ?>
                                                    <!-- <tr class="bg-white">
                                                        <td class="p-1" width="20%">
                                                            <?= htmlspecialchars($qRow['question']) ?>
                                                            <input type="hidden" name="qid[]" value="<?= $qRow['question_id'] ?>">
                                                        </td>
                                                        <?php for ($c = 0; $c < 4; $c++): ?>
                                                            <td class="text-center">
                                                                <div class="icheck-success d-inline">
                                                                    <input type="radio" name="qid[<?= $qRow['question_id'] ?>][]"
                                                                        id="qradio<?= $qRow['question_id'] . '_' . $c ?>"
                                                                        value="<?= $c + 1 ?>">
                                                                    <label for="qradio<?= $qRow['question_id'] . '_' . $c ?>"></label>
                                                                </div>
                                                            </td>
                                                        <?php endfor; ?>
                                                    </tr> -->
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
</div>
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
<style>
    .content .main-header {
        max-height: 90vh;
        overflow-y: auto;
        scroll-behavior: smooth;
    }
    .mean-score-header {
    white-space: nowrap; /* Keeps "Mean Score" on one line */
    }

    .total-mean-score-container {
        display: flex;
        align-items: center;
        justify-content: center; /* Ensures the circle is properly aligned */
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
    document.addEventListener('DOMContentLoaded', function () {
    fetchRatings();
    fetchTotalEvaluations();
});

function fetchRatings() {
    fetch('get_faculty_ratings.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('fname').innerText = data.faculty_name;

                const ratingsTable = document.getElementById('ratings-table-body');
                ratingsTable.innerHTML = ''; // Clear old data

                let totalMean = 0;
                let questionCount = 0;

                Object.values(data.criteria_ratings).forEach(criteria => {
                    const criteriaRow = document.createElement('tr');
                    criteriaRow.innerHTML = `
                        <td class="font-weight-bold bg-light">${criteria.criteria}</td>
                        <th width="5%" class="text-center">1</th>
                        <th width="5%" class="text-center">2</th>
                        <th width="5%" class="text-center">3</th>
                        <th width="5%" class="text-center">4</th>
                        <th width="10%" class="text-center mean-score-header"><b>Mean Score</b></th>
                    `;
                    ratingsTable.appendChild(criteriaRow);

                    criteria.questions.forEach(row => {
                        const totalResponses = row.rate1 + row.rate2 + row.rate3 + row.rate4;
                        const meanScore = totalResponses
                            ? ((row.rate1 * 1 + row.rate2 * 2 + row.rate3 * 3 + row.rate4 * 4) / totalResponses).toFixed(2)
                            : "0.00";

                        totalMean += parseFloat(meanScore);
                        questionCount++;

                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${row.question}</td>
                            <td class="text-center"><div class="circle">${row.rate1}%</div></td>
                            <td class="text-center"><div class="circle">${row.rate2}%</div></td>
                            <td class="text-center"><div class="circle">${row.rate3}%</div></td>
                            <td class="text-center"><div class="circle">${row.rate4}%</div></td>
                            <td class="text-center"><div class="circle mean-score">${meanScore}</div></td>
                        `;
                        ratingsTable.appendChild(tr);
                    });
                });

                const totalMeanScore = questionCount ? (totalMean / questionCount).toFixed(2) : "0.00";

                // Append Total Mean Score Row
                ratingsTable.innerHTML += `
                    <tr class="bg-gradient-secondary total-mean-score-row">
                        <td class="p-2 text-right align-middle" colspan="5"><strong>Total Mean Score:</strong></td>
                        <td class="text-center p-2 align-middle">
                            <div class="circle total-mean-score"><strong>${totalMeanScore}</strong></div>
                        </td>
                    </tr>
                `;
            } else {
                console.error('Error:', data.message);
            }
        })
        .catch(error => console.error('Error fetching ratings:', error));
}

function fetchTotalEvaluations() {
    fetch('get_total_evaluated.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('tse').innerText = data;
        })
        .catch(error => console.error('Error fetching total evaluations:', error));
}
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        fetchAcademicInfo(); // Automatically fetch academic information on page load
    });

    function fetchAcademicInfo() {
        fetch('get_academic_info.php') // Modify URL if needed to include specific faculty_id
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update the academic year and semester display
                    const academicYearDisplay = document.getElementById('ay');
                    academicYearDisplay.innerText = `${data.year} - ${data.semester}`;
                } else {
                    console.error('Error:', data.message);
                    document.getElementById('ay').innerText = 'No academic information available.';
                }
            })
            .catch(error => console.error('Error fetching academic information:', error));
    }
</script>