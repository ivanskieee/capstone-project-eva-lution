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
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
              <span class="mr-1"><img src="assets/uploads/default.png" alt="User" class="user-img border"></span>
              <span class="dropdown-text"><b><?php echo ucwords($_SESSION['login_name']) ?></b></span>
              <span class="fa fa-angle-down ml-2 dropdown-arrow"></span>
            </div>
          </span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="account_settings">
          <a class="dropdown-item" href="javascript:void(0)" id="manage_account"><i class="fa fa-cog"></i> Manage
            Account</a>
          <a class="dropdown-item" href="../database/logout.php"><i class="fa fa-power-off"></i> Logout</a>
        </div>
      </li>
    </ul>
  </nav>
  <div class="modal fade" id="manageAccountModal" tabindex="-1" aria-labelledby="manageAccountModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="manageAccountModalLabel">Manage Account</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="manageAccountContent">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success" id="saveAccountChanges">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    $('#manage_account').click(function () {
      uni_modal('Manage Account', 'manage_user.php?id=<?php echo $_SESSION['login_id'] ?>')
    })
  </script>
  <script>
    $(document).ready(function () {
      // Open Manage Account Modal and Load Form
      $('#manage_account').on('click', function () {
        $.ajax({
          url: 'fetch_account_data.php',
          type: 'GET',
          success: function (data) {
            $('#manageAccountContent').html(data);
            $('#manageAccountModal').modal('show');
          },
          error: function () {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Failed to load account data.',
            });
          }
        });
      });

      // Save Account Changes
      $('#saveAccountChanges').on('click', function () {
        const password = $('#password').val();
        const cpass = $('#cpass').val();

        // Check if passwords match
        if (password && password !== cpass) {
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Passwords do not match!',
          });
          return; // Stop further execution
        }

        const formData = new FormData($('#accountForm')[0]);

        $.ajax({
          url: 'update_account.php',
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          dataType: 'json',
          success: function (response) {
            if (response.success) {
              Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Account updated successfully.',
              }).then(() => {
                $('#manageAccountModal').modal('hide');
                location.reload(); // Reload the page to reflect changes
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: response.message || 'Failed to update account.',
              });
            }
          },
          error: function (xhr, status, error) {
            console.error(xhr.responseText);
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'An error occurred while updating the account.',
            });
          }
        });
      });
    });
  </script>