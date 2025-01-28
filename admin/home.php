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
$status_label = $status_labels[$academic['status']] ?? 'Unknown';

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
                            <h4>Welcome, <?php echo $_SESSION['login_name']; ?>!</h4>
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

            <div class="row mt-3">
                <?php
                $dashboardData = [
                    ["Total Faculties", "college_faculty_list", "fa-user-friends"],
                    ["Total Students", "student_list", "ion-ios-people-outline"],
                    ["Total Users", "users", "fa-users"],
                    ["Total Head Faculties", "head_faculty_list", "fa-users"],
                ];

                foreach ($dashboardData as $data) {
                    $stmt = $conn->query("SELECT * FROM {$data[1]}");
                    $total = $stmt->rowCount();
                    echo "
                    <div class='col-12 col-sm-6 col-md-4 mb-3'>
                        <div class='small-box bg-white shadow-sm rounded border'>
                            <div class='inner'>
                                <h3>{$total}</h3>
                                <p>{$data[0]}</p>
                            </div>
                            <div class='icon'>
                                <i class='fa {$data[2]}'></i>
                            </div>
                        </div>
                    </div>";
                }
                ?>
            </div>

            <div class="row mt-3">
                <div class="col-md-8 offset-md-2">
                    <div class="card shadow-sm rounded">
                        <div class="card-header text-center py-2">
                            <h5 class="mb-0">Select Category</h5>
                        </div>
                        <div class="card-body py-3">
                            <div class="row">
                                <!-- First Row of Buttons -->
                                <div class="col-md-4 mb-2">
                                    <button type="button" class="btn btn-outline-success w-100" id="facultyButton"
                                        data-category="faculty">
                                        Student to Faculty
                                    </button>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <button type="button" class="btn btn-outline-success w-100" id="selfFacultyButton"
                                        data-category="self-faculty">
                                        Self Faculty
                                    </button>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <button type="button" class="btn btn-outline-success w-100"
                                        id="selfHeadFacultyButton" data-category="self-head-faculty">
                                        Self Head Faculty
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <!-- Second Row of Buttons -->
                                <div class="col-md-4 mb-2">
                                    <button type="button" class="btn btn-outline-success w-100"
                                        id="FacultytoFacultyButton" data-category="faculty-to-faculty">
                                        Faculty to Faculty
                                    </button>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <button type="button" class="btn btn-outline-success w-100" id="FacultytoHeadButton"
                                        data-category="faculty-to-head">
                                        Faculty to Head
                                    </button>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <button type="button" class="btn btn-outline-success w-100" id="HeadtoFacultyButton"
                                        data-category="head-to-faculty">
                                        Head to Faculty
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-8 offset-md-2">
                    <div class="card shadow-sm rounded">
                        <div class="card-header text-center py-2">
                            <h5 class="mb-0" id="categoryTitle">Select Faculty to Monitor</h5>
                        </div>
                        <div class="card-body py-3">
                            <form id="facultyForm">
                                <div class="form-group">
                                    <label for="facultySelect" class="form-label" id="facultyLabel">Faculty:</label>
                                    <select class="form-control form-control-sm" id="facultySelect" name="faculty_id">
                                        <option value="" selected disabled>Select Faculty</option>
                                        <!-- Options dynamically populated -->
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-8 offset-md-2">
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
                                            <option value="<?php echo $academicYear['academic_id']; ?>" <?php echo ($academic_id == $academicYear['academic_id']) ? 'selected' : ''; ?>>
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

            <div class="row mt-3">
                <div class="col-md-8 offset-md-2">
                    <div class="card shadow-sm rounded">
                        <div class="card-header text-center py-2">
                            <h5 class="mb-0">Performance Over Time</h5>
                        </div>
                        <div class="card-body py-3">
                            <canvas id="facultyLineChart" style="max-height: 400px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-8 offset-md-2">
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
    </nav>
</div>

<script>
    const facultyList = <?php echo json_encode($facultyList); ?>;
    const headList = <?php echo json_encode($headList); ?>;
    const academicList = <?php echo json_encode($academicYearList); ?>; // Example: [{ academic_id: 1, year: "2023", semester: "First" }]

    const categoryTitle = document.getElementById('categoryTitle');
    const facultyLabel = document.getElementById('facultyLabel');
    const facultySelect = document.getElementById('facultySelect');
    const facultyButtons = document.querySelectorAll('[data-category]');
    const academicSelect = document.getElementById('academicSelect');
    const feedbackElement = document.getElementById('performanceFeedback'); // Element to display feedback

    // Populate dropdown based on category
    function populateDropdown(category) {
        facultySelect.innerHTML = '<option value="" selected disabled>Select Faculty</option>';

        if (category === 'faculty' || category === 'self-faculty') {
            facultyLabel.textContent = 'Faculty:';
            categoryTitle.textContent = category === 'faculty' ? 'Select Faculty to Monitor' : 'Select Self Faculty to Monitor';
            facultyList.forEach(faculty => {
                const option = document.createElement('option');
                option.value = faculty.faculty_id;
                option.textContent = faculty.faculty_name;
                facultySelect.appendChild(option);
            });
        } else if (category === 'self-head-faculty' || category === 'faculty-to-head') {
            facultyLabel.textContent = 'Head:';
            categoryTitle.textContent = category === 'self-head-faculty'
                ? 'Select Self Head Faculty to Monitor'
                : 'Select Head for Faculty to Head Evaluation';
            headList.forEach(head => {
                const option = document.createElement('option');
                option.value = head.head_id;
                option.textContent = head.head_name;
                facultySelect.appendChild(option);
            });
        } else if (category === 'faculty-to-faculty' || category === 'head-to-faculty') {
            facultyLabel.textContent = 'Faculty:';
            categoryTitle.textContent = `Select Faculty for ${category.replace(/-/g, ' ')}`;
            facultyList.forEach(faculty => {
                const option = document.createElement('option');
                option.value = faculty.faculty_id;
                option.textContent = faculty.faculty_name;
                facultySelect.appendChild(option);
            });
        }
    }

    facultyButtons.forEach(button => {
        button.addEventListener('click', function () {
            facultyButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            const category = this.getAttribute('data-category');
            populateDropdown(category);
        });
    });

    populateDropdown('faculty');

    // Populate academicSelect with year and semester

    const ctx = document.getElementById('facultyLineChart').getContext('2d');

    // Create gradient background
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(51, 128, 64, 0.5)'); // Green at the top
    gradient.addColorStop(1, 'rgba(255, 0, 128, 0.3)'); // Pink at the bottom

    const lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [], // Will be populated dynamically
            datasets: [{
                label: 'Ratings',
                data: [],
                fill: true, // Enable fill for gradient effect
                backgroundColor: gradient,
                borderColor: 'rgba(255, 255, 255, 0.8)', // White Line
                pointBackgroundColor: 'rgb(51, 128, 64)', // Themed Green Points
                pointBorderColor: 'rgba(255, 255, 255, 1)', // White Border
                pointRadius: 4, // Smaller and cooler design
                pointHoverRadius: 6,
                pointHoverBorderWidth: 2, // More defined hover effect
                borderWidth: 3, // Thicker line for clarity
                tension: 0.4, // Smooth curve for a modern look
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false, // Hide legend for a cleaner look
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    padding: 10,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)' // Faint grid lines
                    },
                    ticks: {
                        color: 'white', // White labels
                        font: {
                            size: 14
                        }
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)' // Faint grid lines
                    },
                    ticks: {
                        color: 'white', // White labels
                        font: {
                            size: 14
                        }
                    }
                }
            }
        }
    });

    // Function to update chart dynamically
    function updateLineChart(labels, dataset) {
        lineChart.data.labels = labels;
        lineChart.data.datasets[0].data = dataset;
        lineChart.update();
    }

    function updateChart() {
        const facultyId = facultySelect.value;
        const academicId = academicSelect.value;
        const activeCategory = document.querySelector('[data-category].active')?.getAttribute('data-category');

        if (facultyId && academicId) {
            fetch(`fetch_faculty_data.php?faculty_id=${facultyId}&category=${activeCategory}&academic_id=${academicId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    updateLineChart(data.labels, data.dataset, academicId);
                })
                .catch(error => console.error('Error fetching data:', error));
        }
    }

    facultySelect.addEventListener('change', updateChart);
    academicSelect.addEventListener('change', updateChart);

    function updateLineChart(labels, dataset, academicId) {
        lineChart.data.labels = labels;
        lineChart.data.datasets[0].data = dataset;
        lineChart.update();

        // Skip feedback if there are no results
        if (!dataset || dataset.length === 0) {
            feedbackElement.textContent = ""; // Clear feedback
            feedbackElement.classList.remove("text-success", "text-danger"); // Reset styling
            return;
        }

        // Analyze performance based on academic year and dataset
        const academicDetails = academicList.find(a => a.academic_id == academicId);
        const performanceFeedback = analyzePerformance(dataset, academicDetails);

        // Display feedback
        feedbackElement.textContent = performanceFeedback;

        // Change the feedback color based on the analysis
        if (performanceFeedback.includes("inconsistent")) {
            feedbackElement.classList.remove("text-success");
            feedbackElement.classList.add("text-danger"); // Red for danger
        } else {
            feedbackElement.classList.remove("text-danger");
            feedbackElement.classList.add("text-success"); // Green for success
        }
    }

    function analyzePerformance(ratings, academicDetails) {
        let improvement = 0;
        let consistency = 0;
        let needImprovement = 0;

        // Analyze ratings for the selected academic year
        ratings.forEach(rating => {
            if (rating >= 3) {
                improvement++; // Consistently good rating
            } else if (rating <= 2) {
                needImprovement++; // Needs improvement
            } else {
                consistency++; // Neutral or inconsistent
            }
        });

        const year = academicDetails?.year || "Unknown Year";
        const semester = academicDetails?.semester || "Unknown Semester";

        if (improvement > needImprovement) {
            return `For Year ${year}, ${semester} Semester, performance is improving and consistent. Keep up the good work!`;
        } else if (needImprovement > improvement) {
            return `For Year ${year}, ${semester} Semester, performance needs improvement and is inconsistent. Please work on improving the ratings.`;
        } else {
            return `For Year ${year}, ${semester} Semester, performance is inconsistent. Aim to have consistent positive feedback.`;
        }
    }
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
        max-height: 79vh;
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
</style>


<?php include 'footer.php'; ?>