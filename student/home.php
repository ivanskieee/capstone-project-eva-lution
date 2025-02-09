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

$academic_period = $active_academic ? htmlspecialchars($active_academic['year']) . ' - ' . htmlspecialchars($active_academic['semester']) : 'No Active Academic Period';
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
                        $student_id = $_SESSION['user']['student_id'];  // Adjust this to your session variable
                        $query_user = 'SELECT academic_id FROM student_list WHERE student_id = :student_id';
                        $stmt_user = $conn->prepare($query_user);
                        $stmt_user->bindParam(':student_id', $student_id, PDO::PARAM_INT);
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
        <div class="col-12 mt-5">
            <div class="alert text-center ml-2">
                <h4><b>EVALUATION OF FACULTY PERFORMANCE</b></h4>
                <h5><?php echo 'CCS - ' . $academic_period; ?></h5><br>
                <p class="justify-text">
                    This is intended to secure your <b>HONEST, SINCERE, and OBJECTIVE ASSESSMENT</b> of your
                    teacher’s performance.It will assist the Department and the Administration in improving
                    programs for <b>FACULTY DEVELOPMENT</b>.
                </p>
            </div>
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
        max-height: 81vh;
        overflow-y: auto;
        scroll-behavior: smooth;
    }
</style>

<?php include 'footer.php'; ?>