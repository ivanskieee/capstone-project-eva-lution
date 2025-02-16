<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <div class="dropdown">
    <a href="./" class="brand-link">
      <h3 class="text-center p-0 m-0"><b>Admin Panel</b></h3>
    </a>

  </div>
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu"
        data-accordion="false">
        <li class="nav-item dropdown">
          <a href="home.php"
            class="dashboard-link <?php echo basename($_SERVER['PHP_SELF']) == 'home.php' ? 'active' : ''; ?>"
            style="<?php echo basename($_SERVER['PHP_SELF']) == 'home.php' ? 'background-color: rgb(51, 128, 64); color: #fff; border: 1px solid #343a40;' : ''; ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a href="admin_generate_link.php"
            class="nav-link nav-subject_list <?php echo in_array(basename($_SERVER['PHP_SELF']), ['admin_generate_link.php']) ? 'active' : ''; ?>"
            style="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['admin_generate_link.php']) ? 'background-color: rgb(51, 128, 64); color: #fff;' : ''; ?>">
            <i class="nav-icon fas fa-th-list"></i>
            <p>Account Links</p>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a href="verify_accounts.php"
            class="nav-link nav-verify_accounts <?php echo in_array(basename($_SERVER['PHP_SELF']), ['verify_accounts.php']) ? 'active' : ''; ?>"
            style="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['verify_accounts.php']) ? 'background-color: rgb(51, 128, 64); color: #fff;' : ''; ?>">
            <i class="nav-icon fas fa-user-clock"></i> <!-- Updated icon -->
            <p>Pending Accounts</p>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a href="academic_list.php"
            class="nav-link nav-academic_list <?php echo in_array(basename($_SERVER['PHP_SELF']), ['academic_list.php']) ? 'active' : ''; ?>"
            style="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['academic_list.php', 'manage_academic.php']) ? 'background-color: rgb(51, 128, 64); color: #fff;' : ''; ?>">
            <i class="nav-icon fas fa-calendar"></i>
            <p>
              Academic Year
            </p>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a href="questionnaire.php"
            class="nav-link nav-questionnaire <?php echo in_array(basename($_SERVER['PHP_SELF']), ['questionnaire.php']) ? 'active' : ''; ?>"
            style="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['questionnaire.php', 'manage_questionnaire.php']) ? 'background-color: rgb(51, 128, 64); color: #fff;' : ''; ?>">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>
              Questionnaires
            </p>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a href="criteria_list.php"
            class="nav-link nav-criteria_list <?php echo in_array(basename($_SERVER['PHP_SELF']), ['criteria_list.php']) ? 'active' : ''; ?>"
            style="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['criteria_list.php']) ? 'background-color: rgb(51, 128, 64); color: #fff;' : ''; ?>">
            <i class="nav-icon fas fa-list-alt"></i>
            <p>
              Evaluation Criteria
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#"
            class="nav-link nav-edit_user <?php echo in_array(basename($_SERVER['PHP_SELF']), ['new_head_faculty.php', 'head_faculty_list.php']) ? 'active' : ''; ?>"
            style="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['new_head_faculty.php', 'head_faculty_list.php']) ? 'background-color: rgb(51, 128, 64); color: #fff;' : ''; ?>">
            <i class="nav-icon fas fa-users"></i>
            <p>
              Academic Heads
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="new_head_faculty.php" class="nav-link nav-new_user tree-item">
                <i class="nav-icon fas fa-plus-circle"></i>
                <p>Add New</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="head_faculty_list.php" class="nav-link nav-user_list tree-item">
                <i class="nav-icon fas fa-list"></i>
                <p>List</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#"
            class="nav-link nav-edit_faculty <?php echo in_array(basename($_SERVER['PHP_SELF']), ['new_faculty.php', 'tertiary_faculty_list.php']) ? 'active' : ''; ?>"
            style="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['new_faculty.php', 'tertiary_faculty_list.php']) ? 'background-color: rgb(51, 128, 64); color: #fff;' : ''; ?>">
            <i class="nav-icon fas fa-user-friends"></i>
            <p>
              Faculty Members
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="new_faculty.php" class="nav-link nav-new_faculty tree-item">
                <i class="nav-icon fas fa-plus-circle"></i>
                <p>Add New Faculty</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="tertiary_faculty_list.php" class="nav-link nav-tertiary_faculty_list tree-item">
                <i class="nav-icon fas fa-list"></i>
                <p>Faculty List</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a href="#"
            class="nav-link nav-edit_student <?php echo (basename($_SERVER['PHP_SELF']) == 'new_student.php' || basename($_SERVER['PHP_SELF']) == 'student_list.php') ? 'active' : ''; ?>"
            style="<?php echo (basename($_SERVER['PHP_SELF']) == 'new_student.php' || basename($_SERVER['PHP_SELF']) == 'student_list.php') ? 'background-color: rgb(51, 128, 64); color: #fff;' : ''; ?>">
            <i class="nav-icon fa ion-ios-people-outline"></i>
            <p>
              Students
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="student_list.php" class="nav-link nav-student_list tree-item">
                <i class="nav-icon fas fa-list"></i>
                <p>List</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a href="report.php"
            class="nav-link nav-report <?php echo in_array(basename($_SERVER['PHP_SELF']), ['report.php']) ? 'active' : ''; ?>"
            style="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['report.php']) ? 'background-color: rgb(51, 128, 64); color: #fff;' : ''; ?>">
            <i class="nav-icon fas fa-list-alt"></i>
            <p>
              Evaluation Report
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#"
            class="nav-link nav-edit_user <?php echo in_array(basename($_SERVER['PHP_SELF']), ['users.php', 'edit_user.php']) ? 'active' : ''; ?>"
            style="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['new_users.php', 'user_list.php']) ? 'background-color: rgb(51, 128, 64); color: #fff;' : ''; ?>">
            <i class="nav-icon fas fa-users"></i>
            <p>
              Users
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="new_users.php" class="nav-link nav-new_user tree-item">
                <i class="nav-icon fas fa-plus-circle"></i>
                <p>Add New</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="user_list.php" class="nav-link nav-user_list tree-item">
                <i class="nav-icon fas fa-list"></i>
                <p>List</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a href="audit_logs.php"
            class="nav-link nav-report <?php echo in_array(basename($_SERVER['PHP_SELF']), ['audit_logs.php']) ? 'active' : ''; ?>"
            style="<?php echo in_array(basename($_SERVER['PHP_SELF']), ['audit_logs.php']) ? 'background-color: rgb(51, 128, 64); color: #fff;' : ''; ?>">
            <i class="nav-icon fas fa-clipboard-list"></i> <!-- Alternative icon -->
            <p>Audit Logs</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
<script>
  $(document).ready(function () {
    var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
    var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
    if (s != '')
      page = page + '_' + s;
    if ($('.nav-link.nav-' + page).length > 0) {
      $('.nav-link.nav-' + page).addClass('active')
      if ($('.nav-link.nav-' + page).hasClass('tree-item') == true) {
        $('.nav-link.nav-' + page).closest('.nav-treeview').siblings('a').addClass('active')
        $('.nav-link.nav-' + page).closest('.nav-treeview').parent().addClass('menu-open')
      }
      if ($('.nav-link.nav-' + page).hasClass('nav-is-tree') == true) {
        $('.nav-link.nav-' + page).parent().addClass('menu-open')
      }

    }

  })

  document.addEventListener('DOMContentLoaded', function () {
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
      if (link.href === window.location.href) {
        link.classList.add('active');
      }
    });
  });
</script>
<style>
  .dashboard-link {
    display: flex;
    align-items: center;
    padding: 0.5rem 1.5rem;
  }

  .dashboard-link:hover {
    background-color: #495057;
  }

  .dashboard-link p {
    margin: 0;
    font-size: 1rem;
    margin-left: 0.15rem;
    color: inherit;
  }
</style>