<aside class="main-sidebar shadow-lg">
  <div class="brand-container">
    <a href="./" class="brand-link">
      <h3 class="text-center p-0 m-0"><b>Admin Panel</b></h3>
    </a>
  </div>
  
  <div class="sidebar custom-scrollbar">
    <div class="user-profile py-3 mb-2 d-flex align-items-center">
      <div class="user-avatar">
        <i class="fas fa-user-circle fa-2x"></i>
      </div>
      <div class="user-info ml-2">
        <p class="user-name mb-0"><b><?php echo ucwords($_SESSION['login_name']); ?></b></p>
        <small class="user-role text-muted">System Admin</small>
      </div>
    </div>
    
    <div class="sidebar-divider mb-3"></div>
    
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="home.php" class="nav-link dashboard-link <?php echo basename($_SERVER['PHP_SELF']) == 'home.php' ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        
        <li class="nav-header">MANAGEMENT</li>
        
        <li class="nav-item">
          <a href="admin_generate_link.php" class="nav-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['admin_generate_link.php']) ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-link"></i>
            <p>Account Links</p>
          </a>
        </li>
        
        <li class="nav-item">
          <a href="verify_accounts.php" class="nav-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['verify_accounts.php']) ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-user-check"></i>
            <p>Pending Accounts</p>
          </a>
        </li>
        
        <li class="nav-item">
          <a href="academic_list.php" class="nav-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['academic_list.php', 'manage_academic.php']) ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p>Academic Year</p>
          </a>
        </li>
        
        <li class="nav-header">EVALUATION</li>
        
        <li class="nav-item">
          <a href="questionnaire.php" class="nav-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['questionnaire.php', 'manage_questionnaire.php']) ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-clipboard-list"></i>
            <p>Questionnaires</p>
          </a>
        </li>
        
        <li class="nav-item">
          <a href="criteria_list.php" class="nav-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['criteria_list.php']) ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-tasks"></i>
            <p>Evaluation Criteria</p>
          </a>
        </li>
        
        <li class="nav-item">
          <a href="report.php" class="nav-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['report.php']) ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-chart-bar"></i>
            <p>Evaluation Report</p>
          </a>
        </li>
        
        <li class="nav-header">USERS & ACCOUNTS</li>
        
        <li class="nav-item">
          <a href="#" class="nav-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['new_head_faculty.php', 'head_faculty_list.php']) ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-user-tie"></i>
            <p>Academic Heads
              <i class="right fas fa-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="new_head_faculty.php" class="nav-link nav-new_user tree-item">
                <i class="nav-icon fas fa-plus"></i>
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
          <a href="#" class="nav-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['new_faculty.php', 'tertiary_faculty_list.php']) ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-chalkboard-teacher"></i>
            <p>Faculty Members
              <i class="right fas fa-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="new_faculty.php" class="nav-link nav-new_faculty tree-item">
                <i class="nav-icon fas fa-plus"></i>
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
          <a href="#" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'new_student.php' || basename($_SERVER['PHP_SELF']) == 'student_list.php') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-user-graduate"></i>
            <p>Students
              <i class="right fas fa-chevron-right"></i>
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
        
        <li class="nav-item">
          <a href="#" class="nav-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['new_users.php', 'user_list.php']) ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>Users
              <i class="right fas fa-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="new_users.php" class="nav-link nav-new_user tree-item">
                <i class="nav-icon fas fa-plus"></i>
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
        
        <li class="nav-header">SYSTEM</li>
        
        <li class="nav-item">
          <a href="audit_logs.php" class="nav-link <?php echo in_array(basename($_SERVER['PHP_SELF']), ['audit_logs.php']) ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-history"></i>
            <p>Audit Logs</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>

<style>
/* Modern Sidebar Styling */
.main-sidebar {
  background: linear-gradient(180deg, #1e293b, #0f172a);
  color: #fff;
  min-height: 100vh;
  position: fixed;
  left: 0;
  top: 0;
  width: 280px;
  transition: all 0.3s ease;
}

.brand-container {
  padding: 1rem;
  background-color: rgba(0, 0, 0, 0.2);
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.brand-link {
  color: #fff;
  text-decoration: none;
  transition: all 0.3s ease;
}

.brand-link h3 {
  font-weight: 600;
  letter-spacing: 0.5px;
  font-size: 1.25rem;
}

.user-profile {
  padding: 0 1.5rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.user-avatar {
  color: #94a3b8;
}

.user-name {
  font-size: 0.95rem;
  font-weight: 500;
  color: #e2e8f0;
}

.user-role {
  font-size: 0.75rem;
}

.sidebar-divider {
  height: 1px;
  background-color: rgba(255, 255, 255, 0.1);
  margin: 0 1.5rem;
}

.nav-header {
  color: #94a3b8;
  font-size: 0.75rem;
  padding: 1rem 1.5rem 0.5rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.nav-sidebar .nav-item {
  margin-bottom: 0.25rem;
}

.nav-sidebar .nav-link {
  color: #cbd5e1;
  border-radius: 0.375rem;
  margin: 0 0.75rem;
  padding: 0.6rem 1rem;
  transition: all 0.2s ease;
  position: relative;
  display: flex;
  align-items: center;
}

.nav-sidebar .nav-link:hover {
  background-color: rgba(255, 255, 255, 0.1);
  color: #fff;
}

.nav-sidebar .nav-link.active {
  background-color: rgb(51, 128, 64);
  color: #fff;
  box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.4);
}

.nav-sidebar .nav-link p {
  margin-left: 0.75rem;
  margin-bottom: 0;
  font-size: 0.9rem;
  font-weight: 500;
}

.nav-sidebar .nav-treeview {
  margin-left: 1.25rem;
  padding-left: 0.5rem;
  border-left: 1px dashed rgba(255, 255, 255, 0.2);
  margin-top: 0.25rem;
  margin-bottom: 0.5rem;
}

.nav-sidebar .nav-treeview .nav-link {
  padding: 0.4rem 0.75rem;
  font-size: 0.85rem;
}

.nav-sidebar .right {
  margin-left: auto;
  font-size: 0.7rem;
  transition: all 0.3s ease;
}

.nav-sidebar .nav-link.active .right {
  transform: rotate(90deg);
}

.custom-scrollbar {
  max-height: calc(100vh - 60px);
  overflow-y: auto;
}

.custom-scrollbar::-webkit-scrollbar {
  width: 5px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.05);
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.2);
  border-radius: 5px;
}

/* For menu toggling behavior */
.nav-item .nav-treeview {
  display: none;
}

.nav-item.menu-open > .nav-treeview {
  display: block;
}

/* Additional JavaScript for menu toggling behavior */
</style>

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