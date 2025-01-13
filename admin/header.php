<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SPC Evaluation System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" type="text/css" href="styles.css">

  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <link rel="stylesheet" href="assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="assets/plugins/jquery-ui/jquery-ui.min.css">
  <link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="assets/plugins/dropzone/min/dropzone.min.css">
  <link rel="stylesheet" href="assets/dist/css/jquery.datetimepicker.min.css">
  <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="assets/plugins/bootstrap4-toggle/css/bootstrap4-toggle.min.css">
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="assets/dist/css/styles.css">
  <script src="assets/plugins/jquery/jquery.min.js"></script>
  <script src="assets/plugins/jquery-ui/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="assets/plugins/summernote/summernote-bs4.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.9/dist/sweetalert2.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    .bars {
      background: none;
      border: none;
      color: #fff;
      cursor: pointer;
    }

    .user-img {
      border-radius: 50%;
      height: 25px;
      width: 25px;
      object-fit: cover;
    }

    @media (max-width: 575.98px) {
      .navbar-brand {
        font-size: 14px;
      }

      .dropdown-text,

      .dropdown-arrow {

        display: none;
      }

      .user-img {
        width: 25px;

        height: 25px;
      }

      .nav-link>.d-flex {
        justify-content: center;

      }
    }

    .navbar-nav .dropdown-menu {
      position: fixed;
      /* Make the dropdown appear outside the scrolling container */
      top: auto;
      /* Allow natural placement */
      left: auto;
      /* Allow natural placement */
      z-index: 1050;
      /* Ensure it appears above other elements */
    }

    /* .navbar-nav {
      display: flex;
      flex-wrap: nowrap;
      overflow-x: auto;
    }

    .navbar-nav::-webkit-scrollbar {
      height: 8px;
    }

    .navbar-nav::-webkit-scrollbar-thumb {
      background-color: rgba(0, 0, 0, 0.3);
      border-radius: 10px;
    }

    .navbar-nav::-webkit-scrollbar-track {
      background-color: rgba(0, 0, 0, 0.1);
    } */
  </style>
</head>

<body>
  <nav class="main-header navbar navbar-expand navbar-dark bg-navbar" style="background-color: rgb(51, 128, 64);">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="javascript:void(0)" role="button"><i
            class="fas fa-bars"></i></a>
      </li>
      <li>
        <a class="navbar-brand d-flex text-white align-items-center" href="./">
          <img src="images/spclog.png" alt="SPC Logo" width="30" height="30" class="d-inline-block align-text-top me-2">
          SPC Evaluation System
        </a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="javascript:void(0)" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" aria-expanded="false" href="javascript:void(0)">
          <span>
            <div class="d-flex badge-pill align-items-center">
              <span class="mr-1"><img src="assets/uploads/admin.jpg" alt="User" class="user-img border"></span>
              <span class="dropdown-text"><b><?php echo ucwords($_SESSION['login_name']) ?></b></span>
              <span class="fa fa-angle-down ml-2 dropdown-arrow"></span>
            </div>
          </span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="account_settings">
          <!-- <a class="dropdown-item" href="javascript:void(0)" id="manage_account"><i class="fa fa-cog"></i> Manage
            Account</a> -->
          <a class="dropdown-item" href="../database/logout.php"><i class="fa fa-power-off"></i> Logout</a>
        </div>
      </li>
    </ul>
  </nav>

  <script>
    $('#manage_account').click(function () {
      uni_modal('Manage Account', 'manage_user.php?id=<?php echo $_SESSION['login_id'] ?>')
    })
  </script>