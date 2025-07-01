<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: ../index.php');
    exit;
}

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: unauthorized.php');
    exit;
}

include 'header.php';
include 'sidebar.php';
include '../database/connection.php';

// Function to fetch faculty list
function fetchFacultyList($conn)
{
    $stmt = $conn->query("SELECT faculty_id, CONCAT(firstname, ' ', lastname) AS faculty_name FROM college_faculty_list");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to fetch head faculty list
function fetchHeadFacultyList($conn)
{
    $stmt = $conn->query("SELECT head_id, CONCAT(firstname, ' ', lastname) AS head_name FROM head_faculty_list");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetchAllAcademicYears($conn)
{
    $stmt = $conn->query("SELECT academic_id, year, semester, status FROM academic_list");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch the user's current academic year
$academic_id = $_SESSION['user']['academic_id'];  // Assuming the user's academic_id is stored in session
$facultyList = fetchFacultyList($conn, $academic_id);
$headList = fetchHeadFacultyList($conn, $academic_id);

// Fetch all academic years (including closed ones)
$academicYearList = fetchAllAcademicYears($conn);

// Fetch the selected academic year details
$query_academic = 'SELECT year, semester, status FROM academic_list WHERE academic_id = :academic_id';
$stmt_academic = $conn->prepare($query_academic);
$stmt_academic->bindParam(':academic_id', $academic_id, PDO::PARAM_INT);
$stmt_academic->execute();
$academic = $stmt_academic->fetch(PDO::FETCH_ASSOC);

$status_labels = [0 => 'Default (Not Started)', 1 => 'Ongoing', 2 => 'Closed'];
// $status_label = $status_labels[$academic['status']] ?? 'Unknown';
if (is_array($academic) && isset($academic['status']) && isset($status_labels[$academic['status']])) {
    $status_label = $status_labels[$academic['status']];
} else {
    $status_label = ''; // Empty, so nothing is shown
}

// Fetch data
$facultyList = fetchFacultyList($conn);
$headList = fetchHeadFacultyList($conn);

?>
<div class="content">
    <nav class="main-header">
        <div class="container-fluid mt-3">
            <div class="row mb-3">
                <div class="col-12">
                    <h2 class="text-start"
                        style="font-size: 1.8rem; font-weight: bold; color: #4a4a4a; border-bottom: 2px solid #ccc; padding-bottom: 5px;">
                        Dashboard</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm rounded">
                        <div class="card-body">
                            <h4 class="ml-2">Welcome, <?php echo $_SESSION['login_name']; ?>!</h4>
                            <div class="col-md-5 mt-3">
                                <?php
                                // Fetch the user's academic_id from the `users` table (assuming the user is logged in)
                                $user_id = $_SESSION['user']['id'];  // Adjust this to your session variable
                                $query_user = 'SELECT academic_id FROM users WHERE id = :user_id';
                                $stmt_user = $conn->prepare($query_user);
                                $stmt_user->bindParam(':user_id', $user_id, PDO::PARAM_INT);
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
            </div>

            <div class="row mt-4">
                <?php
                $dashboardData = [
                    ["Faculty Members", "college_faculty_list", "fa-user-friends", "tertiary_faculty_list.php"],
                    ["Students", "student_list", "ion-ios-people-outline", "student_list.php"],
                    ["Users", "users", "fa-users", "user_list.php"],
                    ["Academic Heads", "head_faculty_list", "fa-users", "head_faculty_list.php"],
                ];

                foreach ($dashboardData as $data) {
                    $stmt = $conn->query("SELECT * FROM {$data[1]}");
                    $total = $stmt->rowCount();
                    $listPage = $data[3]; // Correct file name mapping
                
                    echo "
                    <div class='col-12 col-sm-6 col-md-3 mb-3'> 
                    <a href='{$listPage}' class='text-decoration-none'>
                    <div class='small-box bg-white shadow-sm rounded border p-4 text-center hover-effect'>
                    <div class='inner'>
                    <h3>{$total}</h3>
                    <p class='mb-0'>{$data[0]}</p>
                    </div>
                    <div class='icon'>
                    <i class='fa {$data[2]} fa-3x'></i>
                    </div>
                    </div>
                        </a>
                    </div>";
                }
                ?>
            </div>

            <div class="row mt-4">
                <div class="col-md-4 mb-3">
                    <div class="card glass-card" data-category="faculty">
                        <div class="card-body text-center">
                            <h6 class="card-title">Student to Faculty Evaluation</h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card glass-card" data-category="self-faculty">
                        <div class="card-body text-center">
                            <h6 class="card-title">Faculty Self-Evaluation</h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card glass-card" data-category="self-head-faculty">
                        <div class="card-body text-center">
                            <h6 class="card-title">Head Self-Evaluation</h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card glass-card" data-category="faculty-to-faculty">
                        <div class="card-body text-center">
                            <h6 class="card-title">Peer to Peer Evaluation</h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card glass-card" data-category="faculty-to-head">
                        <div class="card-body text-center">
                            <h6 class="card-title">Peer to Head Evaluation</h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card glass-card" data-category="head-to-faculty">
                        <div class="card-body text-center">
                            <h6 class="card-title">Head to Faculty Evaluation</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container mt-5">
                <div class="row">
                    <!-- Faculty and Academic Year Selection -->
                    <div class="col-md-6">
                        <div class="card shadow-sm rounded">
                            <div class="card-header text-center py-2">
                                <h5 class="mb-0">Select Faculty to Monitor</h5>
                            </div>
                            <div class="card-body py-3">
                                <form id="facultyForm">
                                    <div class="form-group">
                                        <label for="facultySelect" class="form-label">Faculty:</label>
                                        <select class="form-control form-control-sm" id="facultySelect" name="faculty_id">
                                            <option value="" selected disabled>Select Faculty</option>
                                            <!-- Options dynamically populated -->
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card shadow-sm rounded">
                            <div class="card-header text-center py-2">
                                <h5 class="mb-0">Select Academic Year</h5>
                            </div>
                            <div class="card-body py-3">
                                <form id="academicForm">
                                    <div class="form-group">
                                        <label for="academicSelect" class="form-label">Academic Year:</label>
                                        <select class="form-control form-control-sm" id="academicSelect" name="academic_id">
                                            <option value="" selected disabled>Select Academic Year</option>
                                            <?php foreach ($academicYearList as $academicYear): ?>
                                                <option value="<?php echo $academicYear['academic_id']; ?>" 
                                                    <?php echo ($academic_id == $academicYear['academic_id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($academicYear['year'] . ' - ' . $academicYear['semester']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphs Section -->
                <div class="row mt-3">
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
                <div class="legend-container">
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
                <div class="row mt-3">
                    <div class="col-md-10 mx-auto">
                        <div class="card shadow-sm rounded">
                            <div class="card-header text-center py-2">
                                <h5 class="mb-0">Performance Feedback</h5>
                            </div>
                            <div class="card-body py-3 text-center">
                                <p id="performanceFeedback" class="text-success"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                const facultyList = <?php echo json_encode($facultyList); ?>;
                const headList = <?php echo json_encode($headList); ?>;

                document.addEventListener("DOMContentLoaded", function () {
                    let barChart;
                    let lineChart;

                    const facultyButtons = document.querySelectorAll('[data-category]');
                    const facultySelect = document.getElementById('facultySelect');
                    const academicSelect = document.getElementById('academicSelect');
                    const feedbackElement = document.getElementById('performanceFeedback');

                    let selectedCategory = null; // Track the active category

                    function populateDropdown(category) {
                        facultySelect.innerHTML = '<option value="" selected disabled>Select Faculty</option>';

                        if (['faculty', 'self-faculty', 'faculty-to-faculty', 'head-to-faculty'].includes(category)) {
                            facultyList.forEach(faculty => {
                                const option = document.createElement('option');
                                option.value = faculty.faculty_id;
                                option.textContent = faculty.faculty_name;
                                facultySelect.appendChild(option);
                            });
                        } else if (['self-head-faculty', 'faculty-to-head'].includes(category)) {
                            headList.forEach(head => {
                                const option = document.createElement('option');
                                option.value = head.head_id;
                                option.textContent = head.head_name;
                                facultySelect.appendChild(option);
                            });
                        }
                    }

                    facultyButtons.forEach(button => {
                        button.addEventListener('click', function () {
                            facultyButtons.forEach(btn => btn.classList.remove('active'));
                            this.classList.add('active');

                            selectedCategory = this.getAttribute('data-category'); // Store active category
                            populateDropdown(selectedCategory);
                            
                            // Clear charts and feedback when category changes
                            clearCharts();
                            clearFeedback();
                        });
                    });

                    facultySelect.addEventListener('change', () => fetchData());
                    academicSelect.addEventListener('change', () => fetchData());

                    function fetchData() {
                        if (!selectedCategory) {
                            clearCharts();
                            clearFeedback();
                            return;
                        }

                        const facultyId = facultySelect.value;
                        const academicId = academicSelect.value;

                        if (facultyId && academicId) {
                            fetch(`fetch_faculty_data.php?faculty_id=${facultyId}&category=${selectedCategory}&academic_id=${academicId}`)
                                .then(response => response.json())
                                .then(data => {
                                    updateBarChart(data.labels, data.dataset, selectedCategory);
                                    updateLineChart(data.line_labels, data.line_dataset, selectedCategory);
                                    updateFeedback(data.line_dataset, selectedCategory);
                                })
                                .catch(error => console.error('Error fetching data:', error));
                        } else {
                            clearCharts();
                            clearFeedback();
                        }
                    }

                    function updateBarChart(labels, dataset, category) {
                        const ctxBar = document.getElementById("facultyBarChart").getContext("2d");

                        if (!selectedCategory || selectedCategory !== category) {
                            clearCharts();
                            return;
                        }

                        if (barChart) {
                            barChart.destroy();
                        }

                        barChart = new Chart(ctxBar, {
                            type: "bar",
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: "Performance Score (%)",
                                    backgroundColor: "rgb(51, 128, 64)",
                                    data: dataset
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 100
                                    }
                                }
                            }
                        });
                    }

                    function updateLineChart(labels, dataset, category) {
                        const ctxLine = document.getElementById("facultyLineChart").getContext("2d");

                        if (!selectedCategory || selectedCategory !== category) {
                            clearCharts();
                            return;
                        }

                        if (lineChart) {
                            lineChart.destroy();
                        }

                        lineChart = new Chart(ctxLine, {
                            type: "line",
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: "Average Performance Score",
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
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 100
                                    }
                                }
                            }
                        });
                    }

                    function updateFeedback(dataset, category) {
                        if (!selectedCategory || selectedCategory !== category) {
                            clearFeedback();
                            return;
                        }

                        if (dataset.length < 2) {
                            feedbackElement.textContent = "Not enough data to analyze performance.";
                            feedbackElement.className = "text-secondary";
                            return;
                        }

                        const firstScore = dataset[0];
                        const lastScore = dataset[dataset.length - 1];

                        const firstPerformance = getPerformanceLevel(firstScore);
                        const lastPerformance = getPerformanceLevel(lastScore);

                        if (lastPerformance === "High" && firstPerformance !== "High") {
                            feedbackElement.textContent = `Great job! Performance in ${category} has improved to High.`;
                            feedbackElement.className = "text-success";
                        } else if (lastPerformance === "Low" && firstPerformance !== "Low") {
                            feedbackElement.textContent = `Performance in ${category} has declined to Low. Consider reviewing areas for improvement.`;
                            feedbackElement.className = "text-danger";
                        } else if (lastPerformance === firstPerformance) {
                            feedbackElement.textContent = `Performance in ${category} remains at ${lastPerformance}.`;
                            feedbackElement.className = "text-warning";
                        } else {
                            feedbackElement.textContent = `Performance in ${category} has changed from ${firstPerformance} to ${lastPerformance}.`;
                            feedbackElement.className = "text-info";
                        }
                    }

                    function getPerformanceLevel(score) {
                        if (score >= 2.50) {
                            return "High";
                        } else {
                            return "Low";
                        }
                    }


                    function clearCharts() {
                        if (barChart) {
                            barChart.destroy();
                            barChart = null;
                        }
                        if (lineChart) {
                            lineChart.destroy();
                            lineChart = null;
                        }
                    }

                    function clearFeedback() {
                        feedbackElement.textContent = "";
                        feedbackElement.className = "d-none"; // Hide feedback message
                    }
                });
            </script>


                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const cards = document.querySelectorAll(".glass-card");

                            cards.forEach(card => {
                                card.addEventListener("click", function () {
                                    // Toggle selection on click
                                    if (this.classList.contains("selected")) {
                                        this.classList.remove("selected"); // Unselect if already selected
                                        console.log("Deselected:", this.getAttribute("data-category"));
                                    } else {
                                        // Remove 'selected' class from all other cards
                                        cards.forEach(c => c.classList.remove("selected"));

                                        // Add 'selected' class to the clicked card
                                        this.classList.add("selected");
                                        console.log("Selected:", this.getAttribute("data-category"));
                                    }
                                });
                            });
                        });
            </script>

            <style>
                .card {
                    border-radius: 10px;
                    overflow: hidden;
                }

                .card-header {
                    background: rgb(51, 128, 64);
                    color: white;
                }

                .card-header h5 {
                    font-size: 1rem;
                }

                #facultySelect {
                    border: 1px solid rgb(51, 128, 64);
                    border-radius: 5px;
                    font-size: 0.9rem;
                }

                .container-fluid {
                    max-width: 90%;
                }

                .form-label {
                    font-size: 0.9rem;
                    font-weight: 500;
                }

                .content .main-header {
                    max-height: 88vh;
                    overflow-y: auto;
                    scroll-behavior: smooth;
                }

                .card-body {
                    padding: 0.75rem;
                }

                .form-control-sm {
                    height: calc(1.5em + 0.5rem + 2px);
                    padding: 0.25rem 0.5rem;
                    font-size: 0.875rem;
                }

                .btn {
                    height: 40px;
                    /* Adjust height as needed */
                    line-height: 1.5;
                    /* Vertically centers text */
                }

                .small-box {
                    transition: transform 0.2s ease-in-out, box-shadow 0.2s;
                }

                .small-box:hover {
                    transform: scale(1.05);
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
                }

                .hover-effect:hover {
                    background-color: #f8f9fa;

                }

                .glass-card {
                    background: rgba(255, 255, 255, 0.4);
                    backdrop-filter: blur(10px);
                    border-radius: 15px;
                    padding: 20px;
                    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
                    border: 1px solid rgba(0, 0, 0, 0.1);
                    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out, background 0.3s, border 0.3s;
                    cursor: pointer;
                }

                .glass-card:hover {
                    transform: scale(1.05);
                    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
                }

                /* Selected card effect */
                .glass-card.selected {
                    border: 2px solid rgb(51, 128, 64);
                    background: rgba(51, 128, 64, 0.2);
                }
                /* .legend-item {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    font-size: 0.75rem;
                } */
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