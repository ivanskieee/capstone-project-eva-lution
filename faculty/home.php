<?php
session_start();
include 'header.php';
include 'sidebar.php';


if (!isset($_SESSION['user'])) {
    header('location: ../index.php');
    exit;
}
?>

<div class="content-wrapper">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 mt-5 text-center">
                <div class="home-box">
                    <div class="home-message">
                        <h1>I miss you, Ginger</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>