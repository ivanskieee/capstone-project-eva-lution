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

function ordinal_suffix1($num)
{
    $num = $num % 100;
    if ($num < 11 || $num > 13) {
        switch ($num % 10) {
            case 1:
                return $num . 'st';
            case 2:
                return $num . 'nd';
            case 3:
                return $num . 'rd';
        }
    }
    return $num . 'th';
}
?>
<nav class="main-header">
    <div class="col-12 mt-5">
        <div class="card">
            <div class="card-body">
                Welcome <?php echo $_SESSION['login_name'] ?>!
                <br>
                <div class="col-md-5">
                    <div class="callout callout-info" style="border-left-color: rgb(51, 128, 64);">
                        <h5><b>Academic Year:
                                <!-- <?php echo $_SESSION['academic_info'] ?> -->
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
                        $stmt = $conn->query("SELECT * FROM college_faculty_list");
                        $totalFaculties = $stmt->rowCount();
                        echo $totalFaculties;
                        ?>
                    </h3>

                    <p>Total Tertiary Faculties</p>
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

                    $stmt = $conn->query("SELECT * FROM student_list");
                    $totalStudents = $stmt->rowCount();
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
                    $stmt = $conn->query("SELECT * FROM users");
                    $totalUsers = $stmt->rowCount();
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
                    $stmt = $conn->query("SELECT * FROM class_list");
                    $totalClasses = $stmt->rowCount();
                    echo $totalClasses;
                    ?></h3>

                    <p>Total Classes</p>
                </div>
                <div class="icon">
                    <i class="fa fa-list-alt"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="small-box bg-light shadow-sm border">
                <div class="inner">
                    <h3><?php
                    $stmt = $conn->query("SELECT * FROM secondary_faculty_list");
                    $totalSecondary_Faculty = $stmt->rowCount();
                    echo $totalSecondary_Faculty;
                    ?></h3>

                    <p>Total Secondary Faculties</p>
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
                    $stmt = $conn->query("SELECT * FROM head_faculty_list");
                    $totalHead_Faculty = $stmt->rowCount();
                    echo $totalHead_Faculty;
                    ?></h3>

                    <p>Total Head Faculties</p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mt-5">
        <div class="card">
            <div class="card-body">
                <canvas id="lineChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12 mt-5">
        <div class="card">
            <div class="card-body">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12 mt-5">
        <div class="card">
            <div class="card-body">
                <canvas id="barChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12 mt-5">
        <div class="card">
            <div class="card-body">
                <canvas id="histogramChart"></canvas>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Charts
        const lineChart = new Chart(document.getElementById('lineChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May'],
                datasets: [{
                    label: 'Data',
                    data: [65, 59, 80, 81, 56],
                    borderColor: 'rgb(51, 128, 64)',
                    tension: 0.1
                }]
            }
        });

        const pieChart = new Chart(document.getElementById('pieChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['Red', 'Blue', 'Yellow'],
                datasets: [{
                    data: [300, 50, 100],
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                }]
            }
        });

        const barChart = new Chart(document.getElementById('barChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                datasets: [{
                    label: 'Dataset 1',
                    data: [10, 20, 30, 40, 50],
                    backgroundColor: 'rgb(51, 128, 64)'
                }]
            }
        });

        const histogramChart = new Chart(document.getElementById('histogramChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Group 1', 'Group 2', 'Group 3', 'Group 4'],
                datasets: [{
                    label: 'Scores',
                    data: [12, 19, 3, 5],
                    backgroundColor: 'rgb(51, 128, 64)'
                }]
            }
        });

        let charts = document.querySelectorAll('canvas');
        let options = {
            threshold: 0.3
        };

        let chartObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                } else {
                    entry.target.classList.remove('fade-in');
                }
            });
        }, options);

        charts.forEach(chart => {
            chartObserver.observe(chart);
        });

    });
</script>

<style>
    canvas {
        opacity: 0;
        transform: translateY(20px);
        transition: all 1s ease-out;
    }

    canvas.fade-in {
        opacity: 1;
        transform: translateY(0);
    }

    .main-header {
        position: sticky;
        top: 0;
        z-index: 1000;
        background-color: white;
        box-shadow: 0px 4px 2px -2px gray;
    }

    html {
        scroll-behavior: smooth;
    }

    .list-group-item:hover {
        color: black !important;
        font-weight: 700 !important;
    }

    body {
        overflow-y: hidden;
    }

    .main-header {
        max-height: 95vh;
        overflow-y: scroll;
        scrollbar-width: none;
    }

    .main-header::-webkit-scrollbar {
        display: none;
    }
</style>


<?php include 'footer.php'; ?>