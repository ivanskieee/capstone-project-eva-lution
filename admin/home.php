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

// Fetch data
$facultyList = fetchFacultyList($conn);
$headList = fetchHeadFacultyList($conn);

?>
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
                                <button type="button" class="btn btn-outline-success w-100" id="selfHeadFacultyButton"
                                    data-category="self-head-faculty">
                                    Self Head Faculty
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Second Row of Buttons -->
                            <div class="col-md-4 mb-2">
                                <button type="button" class="btn btn-outline-success w-100" id="FacultytoFacultyButton"
                                    data-category="faculty-to-faculty">
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


        <script>
            // Mock data for testing
            const facultyList = <?php echo json_encode($facultyList); ?>; // Example [{faculty_id: 1, faculty_name: "John Doe"}]
            const headList = <?php echo json_encode($headList); ?>; // Example [{head_id: 1, head_name: "Jane Smith"}]

            const categoryTitle = document.getElementById('categoryTitle');
            const facultyLabel = document.getElementById('facultyLabel');
            const facultySelect = document.getElementById('facultySelect');
            const facultyButtons = document.querySelectorAll('[data-category]');

            // Populate dropdown based on category
            function populateDropdown(category) {
                facultySelect.innerHTML = '<option value="" selected disabled>Select Faculty</option>'; // Reset options

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

            // Handle category button clicks
            facultyButtons.forEach(button => {
                button.addEventListener('click', function () {
                    facultyButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    const category = this.getAttribute('data-category');
                    populateDropdown(category);
                });
            });

            // Initialize dropdown for default category
            populateDropdown('faculty');

            // Update chart on selection
            facultySelect.addEventListener('change', function () {
                const id = this.value;
                const activeCategory = document.querySelector('[data-category].active').getAttribute('data-category');

                if (id) {
                    fetch(`fetch_faculty_data.php?faculty_id=${id}&category=${activeCategory}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                alert(data.error);
                                return;
                            }
                            updateLineChart(data.labels, data.dataset);
                        })
                        .catch(error => console.error('Error fetching data:', error));
                }
            });

            // Chart setup
            const ctx = document.getElementById('facultyLineChart').getContext('2d');
            const lineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Ratings',
                        data: [],
                        borderColor: 'rgb(51, 128, 64)',
                        tension: 0.1,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Rating (1-4)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Evaluation Sequence'
                            }
                        }
                    }
                }
            });

            // Update chart data
            // Update chart data and provide feedback
            function updateLineChart(labels, dataset) {
                lineChart.data.labels = labels;
                lineChart.data.datasets[0].data = dataset;
                lineChart.update();

                // Analyze performance based on the ratings
                const performanceFeedback = analyzePerformance(dataset);

                // Display feedback below the chart
                const feedbackElement = document.getElementById('performanceFeedback');
                feedbackElement.textContent = performanceFeedback;
            }

            // Function to analyze the performance of the faculty
            function analyzePerformance(ratings) {
                let improvement = 0;
                let consistency = 0;
                let needImprovement = 0;

                // Analyze ratings
                ratings.forEach(rating => {
                    if (rating >= 3) {
                        improvement++; // Consistently good rating
                    } else if (rating <= 2) {
                        needImprovement++; // Needs improvement
                    } else {
                        consistency++; // Neutral or inconsistent
                    }
                });

                // Feedback based on the analysis
                if (improvement > needImprovement) {
                    return "Performance is improving and consistent. Keep up the good work!";
                } else if (needImprovement > improvement) {
                    return "Performance needs improvement and is inconsistent. Please work on improving the ratings.";
                } else {
                    return "Performance is inconsistent. Aim to have consistent positive feedback.";
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

            body {
                overflow-y: hidden;
            }

            html {
                scroll-behavior: smooth;
            }

            .main-header {
                max-height: 81vh;
                overflow-y: scroll;
                scrollbar-width: none;
                scroll-behavior: smooth;
            }

            .main-header::-webkit-scrollbar {
                display: none;
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