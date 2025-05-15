<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SPC Evaluation System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'primary': 'rgb(51, 128, 64)',
            'primary-dark': '#276833',
            'sidebar-dark': '#1e293b',
            'sidebar-darker': '#0f172a',
          }
        }
      }
    }
  </script>
  <style>
    /* Custom scrollbar styles */
    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
      background: #1e293b;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #475569;
      border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
      background: #64748b;
    }

    /* For Firefox */
    .custom-scrollbar {
      scrollbar-width: thin;
      scrollbar-color: #475569 #1e293b;
    }

    /* Handle sidebar transition more smoothly */
    .sidebar-transition {
      transition: all 0.3s ease-in-out;
    }

    /* Hide text when sidebar is collapsed */
    .sidebar-collapsed .sidebar-text {
      display: none;
    }

    /* Center icons when sidebar is collapsed */
    .sidebar-collapsed .sidebar-icon {
      margin-left: auto;
      margin-right: auto;
    }

    /* Hide headers when sidebar is collapsed */
    .sidebar-collapsed .sidebar-header {
      display: none;
    }

    /* Adjust user info when sidebar is collapsed */
    .sidebar-collapsed .user-info {
      display: none;
    }
  </style>
</head>

<body class="bg-gray-100">
  <!-- Navbar -->
  <nav
    class="fixed top-0 left-0 right-0 bg-primary text-white flex justify-between items-center px-4 h-16 z-50 shadow-md"
    style="background-color: rgb(51, 128, 64);">
    <div class="flex items-center">
      <button id="sidebarToggle" class="text-white p-2 rounded-md hover:bg-primary-dark focus:outline-none">
        <i class="fas fa-bars"></i>
      </button>
      <a href="./" class="flex items-center ml-4">
        <img src="images/spclog.png" alt="SPC Logo" class="h-8 w-8 mr-2">
        <span class="font-semibold text-lg">SPC Evaluation System</span>
      </a>
    </div>
    <div class="flex items-center">
      <button class="text-white p-2 rounded-md hover:bg-primary-dark focus:outline-none">
        <i class="fas fa-expand-arrows-alt"></i>
      </button>
      <div class="relative ml-4">
        <button id="userMenuBtn" class="flex items-center rounded-full py-1 px-2 hover:bg-primary-dark">
          <img src="assets/uploads/admin.jpg" alt="User" class="h-8 w-8 rounded-full border-2 border-white">
          <span class="mx-2 font-medium"><?php echo ucwords($_SESSION['login_name']) ?></span>
          <i class="fas fa-angle-down"></i>
        </button>
        <div id="userDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden">
          <a href="../database/logout.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
            <i class="fa fa-power-off mr-2"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Sidebar -->
  <aside id="sidebar"
    class="fixed left-0 top-0 w-64 h-full bg-gradient-to-b from-sidebar-dark to-sidebar-darker text-white pt-16 transition-all duration-300 ease-in-out z-40 shadow-lg sidebar-transition">
    <div id="userProfile" class="px-6 py-4 border-b border-gray-700/30">
      <div class="flex items-center">
        <div class="text-gray-400">
          <img src="assets/uploads/admin.jpg" alt="User" class="h-4 w-4 rounded-full border-2 border-white">
        </div>
        <div class="ml-3 user-info">
          <p class="text-gray-200 font-medium text-xs"><?php echo ucwords($_SESSION['login_name']) ?></p>
          <p class="text-gray-400 text-xs">System Admin</p>
        </div>
      </div>
    </div>

    <div class="overflow-y-auto h-[calc(100vh-9rem)] custom-scrollbar">
      <ul class="py-2">
        <li class="px-3 py-2">
          <a href="home.php" class="flex items-center px-3 py-2 rounded-md bg-primary text-white hover:bg-opacity-80">
            <i class="fas fa-tachometer-alt w-5 text-center sidebar-icon"></i>
            <span class="ml-3 text-sm font-medium sidebar-text">Dashboard</span>
          </a>
        </li>

        <li class="px-3 pt-5 pb-1 sidebar-header">
          <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">MANAGEMENT</span>
        </li>

        <li class="px-3 py-1">
          <a href="admin_generate_link.php"
            class="flex items-center px-3 py-2 rounded-md text-gray-300 hover:bg-white/10">
            <i class="fas fa-link w-5 text-center sidebar-icon"></i>
            <span class="ml-3 text-sm font-medium sidebar-text">Account Links</span>
          </a>
        </li>

        <li class="px-3 py-1">
          <a href="verify_accounts.php" class="flex items-center px-3 py-2 rounded-md text-gray-300 hover:bg-white/10">
            <i class="fas fa-user-check w-5 text-center sidebar-icon"></i>
            <span class="ml-3 text-sm font-medium sidebar-text">Pending Accounts</span>
          </a>
        </li>

        <li class="px-3 py-1">
          <a href="academic_list.php" class="flex items-center px-3 py-2 rounded-md text-gray-300 hover:bg-white/10">
            <i class="fas fa-calendar-alt w-5 text-center sidebar-icon"></i>
            <span class="ml-3 text-sm font-medium sidebar-text">Academic Year</span>
          </a>
        </li>

        <li class="px-3 pt-5 pb-1 sidebar-header">
          <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">EVALUATION</span>
        </li>

        <li class="px-3 py-1">
          <a href="questionnaire.php" class="flex items-center px-3 py-2 rounded-md text-gray-300 hover:bg-white/10">
            <i class="fas fa-clipboard-list w-5 text-center sidebar-icon"></i>
            <span class="ml-3 text-sm font-medium sidebar-text">Questionnaires</span>
          </a>
        </li>

        <li class="px-3 py-1">
          <a href="criteria_list.php" class="flex items-center px-3 py-2 rounded-md text-gray-300 hover:bg-white/10">
            <i class="fas fa-tasks w-5 text-center sidebar-icon"></i>
            <span class="ml-3 text-sm font-medium sidebar-text">Evaluation Criteria</span>
          </a>
        </li>

        <li class="px-3 py-1">
          <a href="report.php" class="flex items-center px-3 py-2 rounded-md text-gray-300 hover:bg-white/10">
            <i class="fas fa-chart-bar w-5 text-center sidebar-icon"></i>
            <span class="ml-3 text-sm font-medium sidebar-text">Evaluation Report</span>
          </a>
        </li>

        <li class="px-3 pt-5 pb-1 sidebar-header">
          <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">USERS & ACCOUNTS</span>
        </li>

        <li class="px-3 py-1">
          <button
            class="dropdown-toggle flex items-center justify-between w-full px-3 py-2 rounded-md text-gray-300 hover:bg-white/10">
            <div class="flex items-center">
              <i class="fas fa-user-tie w-5 text-center sidebar-icon"></i>
              <span class="ml-3 text-sm font-medium sidebar-text">Academic Heads</span>
            </div>
            <i class="fas fa-chevron-right text-xs transition-transform dropdown-icon sidebar-text"></i>
          </button>
          <ul class="pl-9 mt-1 hidden dropdown-menu">
            <li class="py-1">
              <a href="new_head_faculty.php"
                class="flex items-center px-3 py-2 rounded-md text-gray-300 hover:bg-white/10">
                <i class="fas fa-plus w-5 text-center sidebar-icon"></i>
                <span class="ml-2 text-sm sidebar-text">Add New</span>
              </a>
            </li>
            <li class="py-1">
              <a href="head_faculty_list.php"
                class="flex items-center px-3 py-2 rounded-md text-gray-300 hover:bg-white/10">
                <i class="fas fa-list w-5 text-center sidebar-icon"></i>
                <span class="ml-2 text-sm sidebar-text">List</span>
              </a>
            </li>
          </ul>
        </li>

        <li class="px-3 py-1">
          <button
            class="dropdown-toggle flex items-center justify-between w-full px-3 py-2 rounded-md text-gray-300 hover:bg-white/10">
            <div class="flex items-center">
              <i class="fas fa-chalkboard-teacher w-5 text-center sidebar-icon"></i>
              <span class="ml-3 text-sm font-medium sidebar-text">Faculty Members</span>
            </div>
            <i class="fas fa-chevron-right text-xs transition-transform dropdown-icon sidebar-text"></i>
          </button>
          <ul class="pl-9 mt-1 hidden dropdown-menu">
            <li class="py-1">
              <a href="new_faculty.php" class="flex items-center px-3 py-2 rounded-md text-gray-300 hover:bg-white/10">
                <i class="fas fa-plus w-5 text-center sidebar-icon"></i>
                <span class="ml-2 text-sm sidebar-text">Add New Faculty</span>
              </a>
            </li>
            <li class="py-1">
              <a href="tertiary_faculty_list.php"
                class="flex items-center px-3 py-2 rounded-md text-gray-300 hover:bg-white/10">
                <i class="fas fa-list w-5 text-center sidebar-icon"></i>
                <span class="ml-2 text-sm sidebar-text">Faculty List</span>
              </a>
            </li>
          </ul>
        </li>

        <li class="px-3 py-1">
          <button
            class="dropdown-toggle flex items-center justify-between w-full px-3 py-2 rounded-md text-gray-300 hover:bg-white/10">
            <div class="flex items-center">
              <i class="fas fa-user-graduate w-5 text-center sidebar-icon"></i>
              <span class="ml-3 text-sm font-medium sidebar-text">Students</span>
            </div>
            <i class="fas fa-chevron-right text-xs transition-transform dropdown-icon sidebar-text"></i>
          </button>
          <ul class="pl-9 mt-1 hidden dropdown-menu">
            <li class="py-1">
              <a href="student_list.php" class="flex items-center px-3 py-2 rounded-md text-gray-300 hover:bg-white/10">
                <i class="fas fa-list w-5 text-center sidebar-icon"></i>
                <span class="ml-2 text-sm sidebar-text">List</span>
              </a>
            </li>
          </ul>
        </li>

        <li class="px-3 py-1">
          <button
            class="dropdown-toggle flex items-center justify-between w-full px-3 py-2 rounded-md text-gray-300 hover:bg-white/10">
            <div class="flex items-center">
              <i class="fas fa-users-cog w-5 text-center sidebar-icon"></i>
              <span class="ml-3 text-sm font-medium sidebar-text">Users</span>
            </div>
            <i class="fas fa-chevron-right text-xs transition-transform dropdown-icon sidebar-text"></i>
          </button>
          <ul class="pl-9 mt-1 hidden dropdown-menu">
            <li class="py-1">
              <a href="new_users.php" class="flex items-center px-3 py-2 rounded-md text-gray-300 hover:bg-white/10">
                <i class="fas fa-plus w-5 text-center sidebar-icon"></i>
                <span class="ml-2 text-sm sidebar-text">Add New</span>
              </a>
            </li>
            <li class="py-1">
              <a href="user_list.php" class="flex items-center px-3 py-2 rounded-md text-gray-300 hover:bg-white/10">
                <i class="fas fa-list w-5 text-center sidebar-icon"></i>
                <span class="ml-2 text-sm sidebar-text">List</span>
              </a>
            </li>
          </ul>
        </li>

        <li class="px-3 pt-5 pb-1 sidebar-header">
          <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">SYSTEM</span>
        </li>

        <li class="px-3 py-1">
          <a href="audit_logs.php" class="flex items-center px-3 py-2 rounded-md text-gray-300 hover:bg-white/10">
            <i class="fas fa-history w-5 text-center sidebar-icon"></i>
            <span class="ml-3 text-sm font-medium sidebar-text">Audit Logs</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>

  <script>
    // Toggle user dropdown
    document.getElementById('userMenuBtn').addEventListener('click', function () {
      document.getElementById('userDropdown').classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function (e) {
      if (!document.getElementById('userMenuBtn').contains(e.target)) {
        document.getElementById('userDropdown').classList.add('hidden');
      }
    });

    // Toggle sidebar
    document.getElementById('sidebarToggle').addEventListener('click', function () {
      const sidebar = document.getElementById('sidebar');

      sidebar.classList.toggle('w-64');
      sidebar.classList.toggle('w-16');
      sidebar.classList.toggle('sidebar-collapsed');

      // The main content should be wrapped properly
      const content = document.querySelector('.content');
      if (content) {
        content.classList.toggle('ml-64');
        content.classList.toggle('ml-16');

        // Adjust dropdown menus when sidebar is collapsed
        const dropdownMenus = document.querySelectorAll('.dropdown-menu');
        if (sidebar.classList.contains('sidebar-collapsed')) {
          dropdownMenus.forEach(menu => {
            menu.classList.add('absolute', 'left-16', 'top-0', 'mt-0', 'bg-sidebar-dark', 'rounded-md', 'shadow-lg', 'z-50', 'w-48');
          });
        } else {
          dropdownMenus.forEach(menu => {
            menu.classList.remove('absolute', 'left-16', 'top-0', 'mt-0', 'bg-sidebar-dark', 'rounded-md', 'shadow-lg', 'z-50', 'w-48');
          });
        }
      }
    });

    // Toggle dropdown menus
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    dropdownToggles.forEach(toggle => {
      toggle.addEventListener('click', function () {
        const dropdownContent = this.nextElementSibling;
        const icon = this.querySelector('.dropdown-icon');
        const sidebar = document.getElementById('sidebar');

        // If sidebar is collapsed, change behavior
        if (sidebar.classList.contains('sidebar-collapsed')) {
          // Get position of the toggle button
          const rect = this.getBoundingClientRect();
          const menu = this.nextElementSibling;

          // Set position
          menu.style.top = rect.top + 'px';

          // Hide all other dropdowns
          document.querySelectorAll('.dropdown-menu').forEach(otherMenu => {
            if (otherMenu !== menu) {
              otherMenu.classList.add('hidden');
            }
          });
        }

        dropdownContent.classList.toggle('hidden');

        if (!dropdownContent.classList.contains('hidden')) {
          icon.classList.add('rotate-90');
        } else {
          icon.classList.remove('rotate-90');
        }
      });
    });

    // Highlight current page
    document.addEventListener('DOMContentLoaded', () => {
      const currentPath = window.location.pathname;
      const filename = currentPath.substring(currentPath.lastIndexOf('/') + 1);

      document.querySelectorAll('a').forEach(link => {
        const href = link.getAttribute('href');
        if (href === filename) {
          link.classList.add('bg-primary', 'text-white');
          link.classList.remove('text-gray-300', 'hover:bg-white/10');

          // If in dropdown, expand parent
          const parentDropdown = link.closest('ul');
          if (parentDropdown && parentDropdown.classList.contains('hidden')) {
            const parentToggle = parentDropdown.previousElementSibling;
            parentToggle.click();
          }
        }
      });
    });

    // Handle dropdown when sidebar is collapsed
    document.addEventListener('DOMContentLoaded', () => {
      // Close dropdowns when clicking outside
      document.addEventListener('click', function (e) {
        const sidebar = document.getElementById('sidebar');
        if (sidebar.classList.contains('sidebar-collapsed')) {
          let targetElement = e.target;
          let isDropdownOrToggle = false;

          while (targetElement != null) {
            if (targetElement.classList && (
              targetElement.classList.contains('dropdown-menu') ||
              targetElement.classList.contains('dropdown-toggle'))) {
              isDropdownOrToggle = true;
              break;
            }
            targetElement = targetElement.parentElement;
          }

          if (!isDropdownOrToggle) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
              menu.classList.add('hidden');
            });
            document.querySelectorAll('.dropdown-icon').forEach(icon => {
              icon.classList.remove('rotate-90');
            });
          }
        }
      });
    });
  </script>
</body>

</html>