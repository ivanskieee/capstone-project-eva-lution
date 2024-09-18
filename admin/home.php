<?php
session_start();
include 'header.php';
include 'sidebar.php';

if (!isset($_SESSION['user'])) {
    header('location: ../index.php');
    exit;
}
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
                <h3>1</h3>

                <p>Total Faculties</p>
            </div>
            <div class="icon">
                <i class="fa fa-user-friends"></i>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="small-box bg-light shadow-sm border">
            <div class="inner">
                <h3>1</h3>

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
                <h3>1</h3>

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
                <h3>0</h3>

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