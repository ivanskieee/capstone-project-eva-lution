<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: ../index.php');
    exit;
}

if ($_SESSION['user']['role'] !== 'admin') {
    // Redirect to an unauthorized page or login page if they don't have the correct role
    header('Location: unauthorized.php');
    exit;
}
include 'header.php';
include 'sidebar.php';
include '../database/connection.php';


?>
<nav class="main-header">
    <div class="col-12 mt-5">
        <div class="card">
            <div class="card-body">
                Welcome!
                <br>
                <div class="col-md-5">
                    <div class="callout callout-info" style="border-left-color: rgb(51, 128, 64);">
                        <h5><b>Academic Year:
                                Semester</b></h5>
                        <h6><b>Evaluation Status:</b></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mx-3">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="small-box bg-light shadow-sm border">
                <div class="inner">
                    <h3>
                        <?php
                        // Assuming $conn is your PDO connection
                        $stmt = $conn->query("SELECT * FROM college_faculty_list");
                        $totalFaculties = $stmt->rowCount(); // Use rowCount() instead of num_rows
                        echo $totalFaculties;
                        ?>
                    </h3>

                    <p>Total Teachers</p>
                </div>
                <div class="icon">
                    <i class="fa fa-user-friends"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="small-box bg-light shadow-sm border">
                <div class="inner">
                    <h3><?php
                        // Assuming $conn is your PDO connection
                        $stmt = $conn->query("SELECT * FROM student_list");
                        $totalStudents= $stmt->rowCount(); // Use rowCount() instead of num_rows
                        echo $totalStudents;
                        ?></h3>

                    <p>Total Students</p>
                </div>
                <div class="icon">
                    <i class="fa ion-ios-people-outline"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="small-box bg-light shadow-sm border">
                <div class="inner">
                    <h3><?php
                        // Assuming $conn is your PDO connection
                        $stmt = $conn->query("SELECT * FROM users");
                        $totalUsers= $stmt->rowCount(); // Use rowCount() instead of num_rows
                        echo $totalUsers;
                        ?></h3>

                    <p>Total Users</p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="small-box bg-light shadow-sm border">
                <div class="inner">
                    <h3> <?php
                    // Assuming $conn is your PDO connection
                    $stmt = $conn->query("SELECT * FROM class_list");
                    $totalClasses = $stmt->rowCount(); // Use rowCount() instead of num_rows
                    echo $totalClasses;
                    ?></h3>

                    <p>Total Classes</p>
                </div>
                <div class="icon">
                    <i class="fa fa-list-alt"></i>
                </div>
            </div>
        </div>
    </div>
</nav>

<?php include 'footer.php'; ?>