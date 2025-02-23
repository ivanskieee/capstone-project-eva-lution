<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: ../index.php');
    exit;
}

if ($_SESSION['user']['role'] !== 'student') {
    header('Location: unauthorized.php');
    exit;
}

include 'header.php';
include 'sidebar.php';
include '../database/connection.php';

$query_active_academic = 'SELECT year, semester FROM academic_list WHERE status = 1 LIMIT 1';
$stmt_active_academic = $conn->prepare($query_active_academic);
$stmt_active_academic->execute();
$active_academic = $stmt_active_academic->fetch(PDO::FETCH_ASSOC);

$academic_period = $active_academic
    ? htmlspecialchars($active_academic['year']) . ' - ' . htmlspecialchars($active_academic['semester']) . ' Semester'
    : 'No Active Academic Period';
?>

<div class="content">
    <nav class="main-header">
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h5>Welcome, <?php echo htmlspecialchars($_SESSION['login_name']); ?>!</h5>
                    <br>
                    <div class="col-md-5">
                        <?php
                        $student_id = $_SESSION['user']['student_id'];  
                        $query_user = 'SELECT academic_id FROM student_list WHERE student_id = :student_id';
                        $stmt_user = $conn->prepare($query_user);
                        $stmt_user->bindParam(':student_id', $student_id, PDO::PARAM_INT);
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
                                $status_labels = [0 => 'Default (Not Started)', 1 => 'Ongoing', 2 => 'Closed'];
                                $status_label = $status_labels[$academic['status']] ?? 'Unknown';

                                echo '
                                    <div class="callout callout-info shadow-md rounded-lg p-3" style="border-left: 5px solid rgb(51, 128, 64);">
                                        <h5><b>Academic Year:</b> ' . htmlspecialchars($academic['year']) . ' <b>Semester:</b> ' . htmlspecialchars($academic['semester']) . '</h5>
                                        <h6><b>Evaluation Status:</b> ' . htmlspecialchars($status_label) . '</h6>
                                    </div>';
                            } else {
                                echo '
                                    <div class="callout callout-warning shadow-md rounded-lg p-3" style="border-left: 5px solid orange;">
                                        <h5><b>No Academic Year Data</b></h5>
                                        <h6>The user is not associated with an active academic year or semester.</h6>
                                    </div>';
                            }
                        } else {
                            echo '
                                <div class="callout callout-warning shadow-md rounded-lg p-3" style="border-left: 5px solid orange;">
                                    <h5><b>No Academic Association</b></h5>
                                    <h6>The user is not associated with any academic year or semester.</h6>
                                </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Faculty Evaluation Section with Glassmorphism Logo -->
        <div class="d-flex justify-content-center mt-5 position-relative">
            <div class="card shadow-lg rounded-lg p-4 text-center glass-container">
                <h4 class="font-weight-bold text-black">EVALUATION OF FACULTY PERFORMANCE</h4>
                <h5 class="text-muted"><?php echo 'A.Y. ' . htmlspecialchars($academic_period); ?></h5>
                <hr class="my-3" style="border-top: 1px solid #28a745;">
                <p class="text-justify text-secondary">
                    This is intended to secure your <b>HONEST, SINCERE, and OBJECTIVE ASSESSMENT</b> of your
                    teacher’s performance. It will assist the Department and the Administration in improving
                    programs for <b>FACULTY DEVELOPMENT</b>.
                </p>
            </div>
            <!-- Glassmorphism Logo -->
            <img src="images/spclog.png" class="glass-logo">
        </div>
    </nav>
</div>

<style>
    .justify-text {
        text-align: justify;
        max-width: 700px;
        margin: 0 auto;
    }

    .content .main-header {
        max-height: 90vh;
        overflow-y: auto;
        scroll-behavior: smooth;
    }
    .glass-container {
        background: rgba(255, 255, 255, 0.2); /* Semi-transparent white */
        backdrop-filter: blur(10px); /* Glass blur effect */
        border-radius: 15px;
        padding: 20px;
        position: relative;
        max-width: 700px;
        width: 100%;
        z-index: 2;
    }

    .glass-logo {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 240px; /* Adjust logo size */
        opacity: 0.2; /* Faded look */
        z-index: 1;
    }
</style>

<?php include 'footer.php'; ?>