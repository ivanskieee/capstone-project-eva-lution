<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="dropdown">
        <a href="./" class="brand-link">
            <h3 class="text-center p-0 m-0"><b>Faculty Panel</b></h3>

        </a>

    </div>
    <div class="sidebar ">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item dropdown">
                    <a href="home.php"
                        class="nav-link nav-home <?php echo basename($_SERVER['PHP_SELF']) == 'home.php' ? 'active' : ''; ?>"
                        style="<?php echo basename($_SERVER['PHP_SELF']) == 'home.php' ? 'background-color: rgb(51, 128, 64); color: #fff; border: 1px solid #343a40;' : 'background-color: #343a40; color: #fff; border: 1px solid #343a40;'; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <!-- <li class="nav-item dropdown">
                    <a href="verify_accounts.php"
                        class="nav-link nav-evaluate <?php echo basename($_SERVER['PHP_SELF']) == 'verify_accounts.php' ? 'active' : ''; ?>"
                        style="<?php echo basename($_SERVER['PHP_SELF']) == 'verify_accounts.php' ? 'background-color: rgb(51, 128, 64); color: #fff; border: 1px solid #343a40;' : 'background-color: #343a40; color: #fff; border: 1px solid #343a40;'; ?>">
                        <i class="nav-icon fas fa-th-list"></i>
                        <p>Pending Accounts</p>
                    </a>
                </li> -->
                <li class="nav-item dropdown">
                    <a href="evaluate.php"
                        class="nav-link nav-evaluate <?php echo basename($_SERVER['PHP_SELF']) == 'evaluate.php' ? 'active' : ''; ?>"
                        style="<?php echo basename($_SERVER['PHP_SELF']) == 'evaluate.php' ? 'background-color: rgb(51, 128, 64); color: #fff; border: 1px solid #343a40;' : 'background-color: #343a40; color: #fff; border: 1px solid #343a40;'; ?>">
                        <i class="nav-icon fas fa-th-list"></i>
                        <p>Evaluate Self</p>
                    </a>
                </li>
                <?php
                include '../database/connection.php';

                $faculty_id = $_SESSION['user']['faculty_id'];

                // Fetch the department of the logged-in faculty
                $query = "SELECT LOWER(department) AS department FROM college_faculty_list WHERE faculty_id = :faculty_id";
                $stmt = $conn->prepare($query);
                $stmt->execute(['faculty_id' => $faculty_id]);
                $faculty = $stmt->fetch(PDO::FETCH_ASSOC);

                $departments = $faculty['department'] ?? '';

                // Normalize the department string for use in URLs
                $normalizedDepartments = array_map('trim', explode(',', strtolower($departments)));
                $normalizedDepartmentsString = implode(',', $normalizedDepartments);
                ?>
                <li class="nav-item dropdown">
                    <a href="evaluate_faculties.php?departments=<?php echo urlencode($normalizedDepartmentsString); ?>"
                        class="nav-link nav-evaluate <?php echo basename($_SERVER['PHP_SELF']) == 'evaluate_faculties.php' ? 'active' : ''; ?>"
                        style="<?php echo basename($_SERVER['PHP_SELF']) == 'evaluate_faculties.php' ? 'background-color: rgb(51, 128, 64); color: #fff; border: 1px solid #343a40;' : 'background-color: #343a40; color: #fff; border: 1px solid #343a40;'; ?>">
                        <i class="nav-icon fas fa-th-list"></i>
                        <p>Evaluate Faculty Members</p>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a href="evaluate_deans.php?departments=<?php echo urlencode($normalizedDepartmentsString); ?>"
                        class="nav-link nav-evaluate <?php echo basename($_SERVER['PHP_SELF']) == 'evaluate_deans.php' ? 'active' : ''; ?>"
                        style="<?php echo basename($_SERVER['PHP_SELF']) == 'evaluate_deans.php' ? 'background-color: rgb(51, 128, 64); color: #fff; border: 1px solid #343a40;' : 'background-color: #343a40; color: #fff; border: 1px solid #343a40;'; ?>">
                        <i class="nav-icon fas fa-th-list"></i>
                        <p>Evaluate Academic Head</p>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a href="report.php"
                        class="nav-link nav-evaluate <?php echo basename($_SERVER['PHP_SELF']) == 'report.php' ? 'active' : ''; ?>"
                        style="<?php echo basename($_SERVER['PHP_SELF']) == 'report.php' ? 'background-color: rgb(51, 128, 64); color: #fff; border: 1px solid #343a40;' : 'background-color: #343a40; color: #fff; border: 1px solid #343a40;'; ?>">
                        <i class="nav-icon fas fa-th-list"></i>
                        <p>Result</p>
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
</script>