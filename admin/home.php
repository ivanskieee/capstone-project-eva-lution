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
$facultyList = fetchFacultyList($conn);
$headList = fetchHeadFacultyList($conn);

// Fetch all academic years (including closed ones)
$academicYearList = fetchAllAcademicYears($conn);

// Fetch the selected academic year details
$query_academic = 'SELECT year, semester, status FROM academic_list WHERE academic_id = :academic_id';
$stmt_academic = $conn->prepare($query_academic);
$stmt_academic->bindParam(':academic_id', $academic_id, PDO::PARAM_INT);
$stmt_academic->execute();
$academic = $stmt_academic->fetch(PDO::FETCH_ASSOC);

$status_labels = [0 => 'Default (Not Started)', 1 => 'Ongoing', 2 => 'Closed'];
if (is_array($academic) && isset($academic['status']) && isset($status_labels[$academic['status']])) {
    $status_label = $status_labels[$academic['status']];
} else {
    $status_label = ''; // Empty, so nothing is shown
}

$facultyList = fetchFacultyList($conn);
$headList = fetchHeadFacultyList($conn);

$primaryColor = "rgb(51, 128, 64)"; 
$primaryHex = "#338040"; 
?>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


<style>
    :root {
        --primary-color: <?php echo $primaryColor; ?>;
        --primary-dark: rgb(41, 105, 52);
        --primary-light: rgb(236, 253, 239);
    }
    
    .bg-primary {
        background-color: var(--primary-color);
    }
    
    .text-primary {
        color: var(--primary-color);
    }
    
    .border-primary {
        border-color: var(--primary-color);
    }
    
    .hover\:bg-primary-dark:hover {
        background-color: var(--primary-dark);
    }
    
    .from-primary {
        --tw-gradient-from: var(--primary-color);
    }
    
    .to-primary-dark {
        --tw-gradient-to: var(--primary-dark);
    }
    
    .ring-primary {
        --tw-ring-color: var(--primary-color);
    }
    
    .bg-primary-light {
        background-color: var(--primary-light);
    }
    
    .card-transition {
        transition: all 0.3s ease;
    }
    
    .card-transition:hover {
        transform: translateY(-3px);
    }
</style>

<div class="content bg-gray-50 ml-64 transition-all duration-300 ease-in-out" style="margin-top: 4rem;">
    <div class="main-header">
        <div class="container mx-auto px-4 py-6">
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <h2 class="text-3xl font-bold text-gray-800 pb-2">
                        Dashboard
                    </h2>
                    <div class="text-sm text-gray-500">
                        <span class="font-medium"><?php echo date('l, F j, Y'); ?></span>
                    </div>
                </div>
                <div class="h-1 w-32 bg-primary rounded-full"></div>
            </div>

            <div class="bg-white rounded-lg shadow-md mb-8 border-l-4">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-2xl font-semibold text-gray-800">Welcome, <?php echo $_SESSION['login_name']; ?></h4>
                            <p class="text-gray-600 mt-1">Admin Dashboard</p>
                        </div>
                        <div class="hidden md:flex space-x-3">
                            <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-primary-light text-primary">
                                <i class="fas fa-user-shield mr-1"></i> Administrator
                            </span>
                            <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Online
                            </span>
                        </div>
                    </div>

                    <div class="mt-5 max-w-3xl">
                        <?php
                        $user_id = $_SESSION['user']['id'];  
                        $query_user = 'SELECT academic_id FROM users WHERE id = :user_id';
                        $stmt_user = $conn->prepare($query_user);
                        $stmt_user->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                        $stmt_user->execute();
                        $user = $stmt_user->fetch(PDO::FETCH_ASSOC);

                        if ($user && $user['academic_id']) {
                            $academic_id = $user['academic_id'];

                            $query_academic = 'SELECT year, semester, status FROM academic_list WHERE academic_id = :academic_id';
                            $stmt_academic = $conn->prepare($query_academic);
                            $stmt_academic->bindParam(':academic_id', $academic_id, PDO::PARAM_INT);
                            $stmt_academic->execute();
                            $academic = $stmt_academic->fetch(PDO::FETCH_ASSOC);

                            if ($academic) {
                                $status_labels = [
                                    0 => 'Default (Not Started)',
                                    1 => 'Ongoing',
                                    2 => 'Closed'
                                ];
                                $status_label = $status_labels[$academic['status']] ?? 'Unknown';

                             
                                $status_colors = [
                                    0 => 'bg-yellow-100 text-yellow-800', 
                                    1 => 'bg-green-100 text-green-800',  
                                    2 => 'bg-gray-100 text-gray-700'      
                                ];
                                $status_color = $status_colors[$academic['status']] ?? 'bg-gray-100 text-gray-700';

                          
                                echo '
                                <div class="bg-gray-50 border-l-4 border-primary rounded-md p-4">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                        <div>
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar-alt text-primary mr-2"></i>
                                                <h5 class="font-semibold text-gray-800">Academic Year: <span class="text-primary">' . htmlspecialchars($academic['year']) . '</span></h5>
                                            </div>
                                            <div class="flex items-center mt-2">
                                                <i class="fas fa-book text-primary mr-2"></i>
                                                <h5 class="font-semibold text-gray-800">Semester: <span class="text-primary">' . htmlspecialchars($academic['semester']) . '</span></h5>
                                            </div>
                                        </div>
                                        <div class="mt-3 md:mt-0">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ' . $status_color . '">
                                                <i class="fas fa-clock mr-1"></i>
                                                ' . htmlspecialchars($status_label) . '
                                            </span>
                                        </div>
                                    </div>
                                </div>';
                            } else {
                        
                                echo '
                                <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-md p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h5 class="text-yellow-800 font-semibold">No Academic Year Data</h5>
                                            <p class="text-yellow-700 text-sm">The user is not associated with an active academic year or semester.</p>
                                        </div>
                                    </div>
                                </div>';
                            }
                        } else {
                        
                            echo '
                            <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-md p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h5 class="text-yellow-800 font-semibold">No Academic Association</h5>
                                        <p class="text-yellow-700 text-sm">The user is not associated with any academic year or semester.</p>
                                    </div>
                                </div>
                            </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>


            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                <?php
                $dashboardData = [
                    ["Faculty Members", "college_faculty_list", "fas fa-user-friends", "tertiary_faculty_list.php", "from-primary to-primary-dark"],
                    ["Students", "student_list", "fas fa-graduation-cap", "student_list.php", "from-primary to-primary-dark"],
                    ["Users", "users", "fas fa-users", "user_list.php", "from-primary to-primary-dark"],
                    ["Academic Heads", "head_faculty_list", "fas fa-user-tie", "head_faculty_list.php", "from-primary to-primary-dark"],
                ];

                foreach ($dashboardData as $index => $data) {
                    $stmt = $conn->query("SELECT * FROM {$data[1]}");
                    $total = $stmt->rowCount();
                    $listPage = $data[3]; 
                    $gradient = $data[4];
                
                    echo "
                    <a href='{$listPage}' class='card-transition'>
                        <div class='rounded-lg shadow-md overflow-hidden bg-gradient-to-r {$gradient} text-white relative'>
                            <div class='p-5'>
                                <div class='flex items-center justify-between'>
                                    <div>
                                        <p class='text-white text-opacity-90 font-medium'>{$data[0]}</p>
                                        <h3 class='text-3xl font-bold mt-1'>{$total}</h3>
                                    </div>
                                    <div class='rounded-full bg-white bg-opacity-20 p-3'>
                                        <i class='{$data[2]} text-xl'></i>
                                    </div>
                                </div>
                                <div class='flex items-center mt-4 text-sm text-white text-opacity-90'>
                                    <span>View Details</span>
                                    <i class='fas fa-arrow-right ml-1'></i>
                                </div>
                            </div>
                            <div class='absolute top-0 right-0 bottom-0 opacity-10'>
                                <i class='{$data[2]} text-9xl transform translate-x-8 translate-y-6'></i>
                            </div>
                        </div>
                    </a>";
                }
                ?>
            </div>

       
            <div class="mb-8">
                <div class="flex items-center mb-4">
                    <div class="w-1 h-6 bg-primary rounded-full mr-2"></div>
                    <h3 class="text-xl font-bold text-gray-800">Evaluation Categories</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    <?php
                    $evaluationCategories = [
                        ["Student to Faculty Evaluation", "faculty", "fas fa-chalkboard-teacher", "border-green-500"],
                        ["Faculty Self-Evaluation", "self-faculty", "fas fa-user-check", "border-amber-500"],
                        ["Head Self-Evaluation", "self-head-faculty", "fas fa-user-tie", "border-lime-500"],
                        ["Peer to Peer Evaluation", "faculty-to-faculty", "fas fa-users-cog", "border-violet-500"],
                        ["Peer to Head Evaluation", "faculty-to-head", "fas fa-user-graduate", "border-pink-500"],
                        ["Head to Faculty Evaluation", "head-to-faculty", "fas fa-user-shield", "border-indigo-500"]
                    ];

                    foreach ($evaluationCategories as $category) {
                        echo "
                        <div class='evaluation-card cursor-pointer card-transition' data-category='{$category[1]}'>
                            <div class='bg-white rounded-lg shadow-md overflow-hidden border-l-4 {$category[3]}'>
                                <div class='p-5 flex items-center space-x-4'>
                                    <div class='flex-shrink-0'>
                                        <div class='rounded-full p-3 bg-gray-100 text-gray-700'>
                                            <i class='{$category[2]} text-lg'></i>
                                        </div>
                                    </div>
                                    <div class='flex-1 min-w-0'>
                                        <h6 class='text-gray-800 text-base font-semibold truncate'>{$category[0]}</h6>
                                        <p class='text-sm text-gray-500'>Click to select</p>
                                    </div>
                                    <div class='indicator-dot hidden w-3 h-3 rounded-full bg-primary'></div>
                                </div>
                            </div>
                        </div>";
                    }
                    ?>
                </div>
            </div>

          
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
             
                <div class="bg-white rounded-lg shadow-md">
                    <div class="bg-primary text-white rounded-t-lg py-3 px-4">
                        <h5 class="text-lg font-semibold">Select Faculty to Monitor</h5>
                    </div>
                    <div class="p-5">
                        <form id="facultyForm">
                            <div class="mb-4">
                                <label for="facultySelect" class="block text-sm font-medium text-gray-700 mb-1">Faculty:</label>
                                <select id="facultySelect" name="faculty_id" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                    <option value="" selected disabled>Select Faculty</option>
                                 
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

          
                <div class="bg-white rounded-lg shadow-md">
                    <div class="bg-primary text-white rounded-t-lg py-3 px-4">
                        <h5 class="text-lg font-semibold">Select Academic Year</h5>
                    </div>
                    <div class="p-5">
                        <form id="academicForm">
                            <div class="mb-4">
                                <label for="academicSelect" class="block text-sm font-medium text-gray-700 mb-1">Academic Year:</label>
                                <select id="academicSelect" name="academic_id" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
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

          
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
               
                <div class="bg-white rounded-lg shadow-md">
                    <div class="bg-primary text-white rounded-t-lg py-3 px-4">
                        <h5 class="text-lg font-semibold">Performance Over Time</h5>
                    </div>
                    <div class="p-5">
                        <canvas id="facultyLineChart" class="max-h-80"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md">
                    <div class="bg-primary text-white rounded-t-lg py-3 px-4">
                        <h5 class="text-lg font-semibold">Mean Score</h5>
                    </div>
                    <div class="p-5">
                        <canvas id="facultyBarChart" class="max-h-80"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-5 mb-6">
                <div class="flex items-center mb-3">
                    <div class="w-1 h-5 bg-primary rounded-full mr-2"></div>
                    <h4 class="text-lg font-semibold text-gray-800">Performance Scale</h4>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="border-l-4 border-green-500 bg-green-50 p-3 rounded">
                        <div class="text-sm text-gray-800 font-medium">3.25 - 4.00</div>
                        <div class="font-bold text-green-700">Strongly Agree</div>
                        <div class="text-xs text-gray-500">High</div>
                    </div>
                    <div class="border-l-4 border-blue-500 bg-blue-50 p-3 rounded">
                        <div class="text-sm text-gray-800 font-medium">2.50 - 3.24</div>
                        <div class="font-bold text-blue-700">Agree</div>
                        <div class="text-xs text-gray-500">Moderate-High</div>
                    </div>
                    <div class="border-l-4 border-yellow-500 bg-yellow-50 p-3 rounded">
                        <div class="text-sm text-gray-800 font-medium">1.75 - 2.49</div>
                        <div class="font-bold text-yellow-700">Disagree</div>
                        <div class="text-xs text-gray-500">Moderate-Low</div>
                    </div>
                    <div class="border-l-4 border-red-500 bg-red-50 p-3 rounded">
                        <div class="text-sm text-gray-800 font-medium">1.00 - 1.74</div>
                        <div class="font-bold text-red-700">Strongly Disagree</div>
                        <div class="text-xs text-gray-500">Low</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md mb-8">
                <div class="bg-primary text-white rounded-t-lg py-3 px-4">
                    <h5 class="text-lg font-semibold">Performance Feedback</h5>
                </div>
                <div class="p-6 text-center">
                    <p id="performanceFeedback" class="text-lg font-medium text-gray-800"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const facultyList = <?php echo json_encode($facultyList); ?>;
    const headList = <?php echo json_encode($headList); ?>;
    const primaryColor = "<?php echo $primaryColor; ?>";
    const primaryHex = "<?php echo $primaryHex; ?>";

    document.addEventListener("DOMContentLoaded", function () {
        let barChart;
        let lineChart;

        const evaluationCards = document.querySelectorAll('.evaluation-card');
        const facultySelect = document.getElementById('facultySelect');
        const academicSelect = document.getElementById('academicSelect');
        const feedbackElement = document.getElementById('performanceFeedback');

        let selectedCategory = null; 

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

        evaluationCards.forEach(card => {
            card.addEventListener('click', function () {
                evaluationCards.forEach(c => {
                    c.classList.remove('ring-2', 'ring-primary');
                    c.querySelector('.indicator-dot').classList.add('hidden');
                });
                
                this.classList.add('ring-2', 'ring-primary');
                this.querySelector('.indicator-dot').classList.remove('hidden');

                selectedCategory = this.getAttribute('data-category'); 
                populateDropdown(selectedCategory);
             
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
                        backgroundColor: getGradientColor(ctxBar, primaryColor),
                        borderColor: primaryHex,
                        borderWidth: 1,
                        borderRadius: 4,
                        data: dataset
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    family: "'Inter', sans-serif",
                                    size: 12
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            grid: {
                                drawBorder: false,
                                color: '#f3f4f6'
                            },
                            ticks: {
                                font: {
                                    family: "'Inter', sans-serif",
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: "'Inter', sans-serif",
                                    size: 11
                                }
                            }
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
                        borderColor: primaryHex,
                        backgroundColor: getGradientFill(ctxLine, primaryColor),
                        data: dataset,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: primaryHex,
                        pointBorderColor: "#fff",
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    family: "'Inter', sans-serif",
                                    size: 12
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            grid: {
                                drawBorder: false,
                                color: '#f3f4f6'
                            },
                            ticks: {
                                font: {
                                    family: "'Inter', sans-serif",
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: "'Inter', sans-serif",
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }

        function getGradientColor(ctx, color) {
            let gradient;
            if (color === 'indigo') {
                gradient = ctx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(79, 70, 229, 1)');
                gradient.addColorStop(1, 'rgba(99, 102, 241, 0.8)');
            }
            return gradient;
        }

        function getGradientFill(ctx, color) {
            let gradient;
            if (color === 'indigo') {
                gradient = ctx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(79, 70, 229, 0.4)');
                gradient.addColorStop(1, 'rgba(79, 70, 229, 0.05)');
            }
            return gradient;
        }

        function updateFeedback(dataset, category) {
            if (!selectedCategory || selectedCategory !== category) {
                clearFeedback();
                return;
            }

            if (dataset.length < 2) {
                feedbackElement.textContent = "Not enough data to analyze performance.";
                feedbackElement.className = "text-lg font-medium text-gray-500";
                return;
            }

            const firstScore = dataset[0];
            const lastScore = dataset[dataset.length - 1];

            const firstPerformance = getPerformanceLevel(firstScore);
            const lastPerformance = getPerformanceLevel(lastScore);

            if (lastPerformance === "High" && firstPerformance !== "High") {
                feedbackElement.textContent = `Great job! Performance in ${formatCategoryName(category)} has improved to High.`;
                feedbackElement.className = "text-lg font-medium text-green-600";
           } else if (lastPerformance === "Low" && firstPerformance !== "Low") {
                feedbackElement.textContent = `Performance in ${formatCategoryName(category)} has declined to Low. Consider reviewing areas for improvement.`;
                feedbackElement.className = "text-lg font-medium text-red-600";
            } else if (lastPerformance === firstPerformance) {
                feedbackElement.textContent = `Performance in ${formatCategoryName(category)} remains at ${lastPerformance}.`;
                feedbackElement.className = "text-lg font-medium text-yellow-600";
            } else {
                feedbackElement.textContent = `Performance in ${formatCategoryName(category)} has changed from ${firstPerformance} to ${lastPerformance}.`;
                feedbackElement.className = "text-lg font-medium text-blue-600";
            }
        }

        function getPerformanceLevel(score) {
            if (score >= 75) {
                return "High";
            } else if (score >= 50) {
                return "Moderate";
            } else {
                return "Low";
            }
        }

        function formatCategoryName(category) {
            const categoryMap = {
                'faculty': 'Student to Faculty Evaluation',
                'self-faculty': 'Faculty Self-Evaluation',
                'self-head-faculty': 'Head Self-Evaluation',
                'faculty-to-faculty': 'Peer to Peer Evaluation',
                'faculty-to-head': 'Peer to Head Evaluation',
                'head-to-faculty': 'Head to Faculty Evaluation'
            };
            
            return categoryMap[category] || category;
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
            feedbackElement.className = "text-lg font-medium text-gray-500";
        }
    });
</script>

<?php include 'footer.php'; ?>