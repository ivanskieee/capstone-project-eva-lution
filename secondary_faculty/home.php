<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: ../index.php');
    exit;
}

if ($_SESSION['user']['role'] !== 'secondary_faculty') {
    header('Location: unauthorized.php');
    exit;
}

include 'header.php';
include 'sidebar.php';
?>

<nav class="main-header">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                Welcome <?php echo $_SESSION['login_name'] ?>!
                <br>
                <div class="col-md-5">
                    <div class="callout callout-info" style="border-left-color: rgb(51, 128, 64);">
                        <h5><b>Academic Year:
                                Semester</b></h5>
                        <h6><b>Evaluation Status: </b></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<?php include 'footer.php'; ?>