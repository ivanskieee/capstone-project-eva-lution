<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: ../index.php');
    exit;
}

if ($_SESSION['user']['role'] !== 'faculty') {
    header('Location: unauthorized.php');
    exit;
}

include 'header.php';
include 'sidebar.php';
include '../database/connection.php';
?>
<div class="content">
    <nav class="main-header">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    Welcome <?php echo $_SESSION['login_name'] ?>!
                    <br>
                    <div class="col-md-5">
                        <?php
                        // Fetch the user's academic_id from the `users` table (assuming the user is logged in)
                        $faculty_id = $_SESSION['user']['faculty_id'];  // Adjust this to your session variable
                        $query_user = 'SELECT academic_id FROM college_faculty_list WHERE faculty_id = :faculty_id';
                        $stmt_user = $conn->prepare($query_user);
                        $stmt_user->bindParam(':faculty_id', $faculty_id, PDO::PARAM_INT);
                        $stmt_user->execute();
                        $user = $stmt_user->fetch(PDO::FETCH_ASSOC);

                        if ($user && $user['academic_id']) {
                            $academic_id = $user['academic_id'];

                            // Fetch the academic year, semester, and status from the `academic_list` table
                            $query_academic = 'SELECT year, semester, status FROM academic_list WHERE academic_id = :academic_id';
                            $stmt_academic = $conn->prepare($query_academic);
                            $stmt_academic->bindParam(':academic_id', $academic_id, PDO::PARAM_INT);
                            $stmt_academic->execute();
                            $academic = $stmt_academic->fetch(PDO::FETCH_ASSOC);

                            if ($academic) {
                                // Map the status to a user-friendly label
                                $status_labels = [
                                    0 => 'Default (Not Started)',
                                    1 => 'Ongoing',
                                    2 => 'Closed'
                                ];
                                $status_label = $status_labels[$academic['status']] ?? 'Unknown';

                                // Display the academic year, semester, and status in the callout
                                echo '
                                        <div class="callout callout-info" style="border-left: 5px solid rgb(51, 128, 64);">
                                            <h5><b>Academic Year:</b> ' . htmlspecialchars($academic['year']) . ' <b>Semester:</b> ' . htmlspecialchars($academic['semester']) . '</h5>
                                            <h6><b>Evaluation Status:</b> ' . htmlspecialchars($status_label) . '</h6>
                                        </div>';
                            } else {
                                // No academic data found for the user's academic_id
                                echo '
                                        <div class="callout callout-warning" style="border-left: 5px solid orange;">
                                            <h5><b>No Academic Year Data</b></h5>
                                            <h6>The user is not associated with an active academic year or semester.</h6>
                                        </div>';
                            }
                        } else {
                            // No academic_id found for the user
                            echo '
                                    <div class="callout callout-warning" style="border-left: 5px solid orange;">
                                        <h5><b>No Academic Association</b></h5>
                                        <h6>The user is not associated with any academic year or semester.</h6>
                                    </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-sm rounded">
                        <div class="card-header text-center py-2">
                            <h5 class="mb-0">Performance Over Time</h5>
                        </div>
                        <div class="card-body py-3">
                            <canvas id="facultyLineChart" style="max-height: 400px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm rounded">
                        <div class="card-header text-center py-2">
                            <h5 class="mb-0">Mean Score</h5>
                        </div>
                        <div class="card-body py-3">
                            <canvas id="facultyBarChart" style="max-height: 400px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="legend-container mb-2">
            <div class="legend-item">
                3.25 - 4.00 <br> <b>Strongly Agree</b> <span class="legend-text">High</span>
            </div>
            <div class="legend-item">
                2.50 - 3.24 <br> <b>Agree</b> <span class="legend-text">Moderate-High</span>
            </div>
            <div class="legend-item">
                1.75 - 2.49 <br> <b>Disagree</b> <span class="legend-text">Moderate-Low</span>
            </div>
            <div class="legend-item">
                1.00 - 1.74 <br> <b>Strongly Disagree</b> <span class="legend-text">Low</span>
            </div>
        </div>

        <!-- Feedback Section -->
        <div class="container">
            <div class="col-md-10 mx-auto">
                <div class="card shadow-sm rounded">
                    <div class="card-header text-center py-2">
                        <h5 class="mb-0">Performance Feedback</h5>
                    </div>
                    <div class="card-body py-3 text-center">
                        <p id="feedbackMessage" class="text-secondary"></p>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>
<script>
    let barChart, lineChart;
    let feedbackElement;

    document.addEventListener("DOMContentLoaded", function () {
        feedbackElement = document.getElementById("feedbackMessage");
        fetchChartData();
    });

    function fetchChartData() {
        fetch("get_faculty_data.php")
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    updateLineChart(data.performance_data);
                    updateBarChart(data.criteria_ratings);
                } else {
                    console.error("Error fetching data:", data.message);
                }
            })
            .catch(error => console.error("Fetch error:", error));
    }

    function updateLineChart(performanceData) {
        const labels = Object.keys(performanceData);
        const dataset = Object.values(performanceData);

        const ctxLine = document.getElementById("facultyLineChart").getContext("2d");
        if (lineChart) {
            lineChart.destroy();
        }

        lineChart = new Chart(ctxLine, {
            type: "line",
            data: {
                labels: labels,
                datasets: [{
                    label: "Performance Over Semesters",
                    borderColor: "rgb(51, 128, 64)",
                    backgroundColor: "rgba(51, 128, 64, 0.2)",
                    data: dataset,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, max: 4 } }
            }
        });

        updateFeedback(dataset, "Academic Performance Over Semesters");
    }

    function updateBarChart(criteriaRatings) {
        const labels = Object.keys(criteriaRatings);
        const dataset = labels.map(criteria => {
            const data = criteriaRatings[criteria];
            return ((data.rate1 * 1 + data.rate2 * 2 + data.rate3 * 3 + data.rate4 * 4) / 4).toFixed(2);
        });

        const ctxBar = document.getElementById("facultyBarChart").getContext("2d");
        if (barChart) {
            barChart.destroy();
        }

        barChart = new Chart(ctxBar, {
            type: "bar",
            data: {
                labels: labels,
                datasets: [{
                    label: "Criteria Performance",
                    backgroundColor: "rgb(51, 128, 64)",
                    data: dataset
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, max: 4 } }
            }
        });
    }

    function updateFeedback(dataset, category) {
        if (!feedbackElement) return;

        if (dataset.length < 2) {
            feedbackElement.textContent = "Not enough data to analyze performance.";
            feedbackElement.className = "text-secondary";
            return;
        }

        const firstScore = dataset[0];
        const lastScore = dataset[dataset.length - 1];

        if (lastScore > firstScore) {
            feedbackElement.textContent = `Great job! ${category} has improved to High over time.`;
            feedbackElement.className = "text-success";
        } else if (lastScore < firstScore) {
            feedbackElement.textContent = `Performance in ${category} has declined to Low. Consider reviewing areas for improvement.`;
            feedbackElement.className = "text-danger";
        } else {
            feedbackElement.textContent = `${category} remains stable.`;
            feedbackElement.className = "text-warning";
        }
    }
</script>
<style>
    .card-header {
        background: rgb(51, 128, 64);
        color: white;
    }

    .card-header h5 {
        font-size: 1rem;
    }

    .content .main-header {
        max-height: 81vh;
        overflow-y: auto;
        scroll-behavior: smooth;
    }

    .legend-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .legend-item {
        text-align: center;
        font-size: 14px;
        font-weight: bold;
        color: #333;
        padding: 5px;
        border-bottom: 1px solid #ddd;
        min-width: 180px;
    }

    .legend-text {
        display: block;
        font-size: 12px;
        font-weight: normal;
    }
</style>

<?php include 'footer.php'; ?>