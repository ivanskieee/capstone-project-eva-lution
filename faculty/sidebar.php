<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers Panel</title>
    <link rel="stylesheet" href="path/to/font-awesome/css/all.min.css">
    <style>
        .sidebar-hidden {
            display: none;
        }

        .sidebar-visible {
            display: block;
        }

        #toggle-sidebar {
            left: 10px;
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <aside id="sidebar" style="background-color: rgb(51, 128, 64);"
            class="main-sidebar sidebar-dark-primary elevation-4 sidebar-hidden">
            <div class="dropdown">
                <a href="./" class="brand-link">
                    <h3 class="text-center p-0 m-0"><b>Teachers Panel</b></h3>
                </a>
            </div>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item dropdown">
                            <a href="./" class="nav-link nav-home">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="./index.php?page=subject_list" class="nav-link nav-subject_list">
                                <i class="nav-icon fas fa-th-list"></i>
                                <p>Subjects</p>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="./index.php?page=class_list" class="nav-link nav-class_list">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>Classes</p>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="./index.php?page=academic_list" class="nav-link nav-academic_list">
                                <i class="nav-icon fas fa-calendar"></i>
                                <p>Academic Year</p>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="./index.php?page=questionnaire" class="nav-link nav-questionnaire">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Questionnaires</p>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="./index.php?page=criteria_list" class="nav-link nav-criteria_list">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>Evaluation Criteria</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link nav-edit_faculty">
                                <i class="nav-icon fas fa-user-friends"></i>
                                <p>Faculties<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./index.php?page=new_faculty" class="nav-link nav-new_faculty tree-item">
                                        <i class="fas fa-angle-right nav-icon"></i>
                                        <p>Add New</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./index.php?page=faculty_list" class="nav-link nav-faculty_list tree-item">
                                        <i class="fas fa-angle-right nav-icon"></i>
                                        <p>List</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link nav-edit_student">
                                <i class="nav-icon fa ion-ios-people-outline"></i>
                                <p>Students<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./index.php?page=new_student" class="nav-link nav-new_student tree-item">
                                        <i class="fas fa-angle-right nav-icon"></i>
                                        <p>Add New</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./index.php?page=student_list" class="nav-link nav-student_list tree-item">
                                        <i class="fas fa-angle-right nav-icon"></i>
                                        <p>List</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="./index.php?page=report" class="nav-link nav-report">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>Evaluation Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link nav-edit_user">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Users<i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./index.php?page=new_user" class="nav-link nav-new_user tree-item">
                                        <i class="fas fa-angle-right nav-icon"></i>
                                        <p>Add New</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./index.php?page=user_list" class="nav-link nav-user_list tree-item">
                                        <i class="fas fa-angle-right nav-icon"></i>
                                        <p>List</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
    </div>

    <script>
        document.getElementById('toggle-sidebar').addEventListener('click', function () {
            var sidebar = document.getElementById('sidebar');
            var contentWrapper = document.querySelector('.content-wrapper');
            var navbar = document.querySelector('.navbar');

            if (sidebar.classList.contains('sidebar-hidden')) {
                sidebar.classList.remove('sidebar-hidden');
                sidebar.classList.add('sidebar-visible');
                contentWrapper.classList.add('content-shifted');
                navbar.classList.add('navbar-shifted');  // Shift navbar to the right
            } else {
                sidebar.classList.remove('sidebar-visible');
                sidebar.classList.add('sidebar-hidden');
                contentWrapper.classList.remove('content-shifted');
                navbar.classList.remove('navbar-shifted');  // Move navbar back to its original position
            }
        });

    </script>
</body>

</html>