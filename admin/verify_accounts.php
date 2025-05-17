<?php
include "handlers/verify_accounts_handler.php";
include "handlers/verify_actions_handler.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Accounts</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: 'rgb(51, 128, 64)',
                            light: 'rgb(76, 153, 89)',
                            dark: 'rgb(40, 100, 50)',
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100">
    <div id="mainContent" class="content ml-64 pt-16 min-h-screen transition-all duration-300 bg-gray-100">
        <div class="px-6 py-4">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-700 pb-2 border-b-2 border-gray-300">
                    Pending Accounts
                </h2>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-primary px-6 py-4 flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-white">Pending Student Registrations</h3>
                </div>

                <div class="p-6">
                    <?php
                    if (isset($_GET['status'])) {
                        $status = $_GET['status'];
                        $alertClass = "";
                        $message = "";
                        $icon = "";

                        if ($status == 'confirmed') {
                            $alertClass = "bg-green-100 border-green-400 text-green-700";
                            $message = "Student has been confirmed and added to the system.";
                            $icon = "check-circle";
                        } elseif ($status == 'rejected') {
                            $alertClass = "bg-red-100 border-red-400 text-red-700";
                            $message = "Student has been rejected and removed from the system.";
                            $icon = "times-circle";
                        } elseif ($status == 'bulk_confirmed') {
                            $alertClass = "bg-green-100 border-green-400 text-green-700";
                            $message = "All selected students have been confirmed and added to the system.";
                            $icon = "check-circle";
                        } elseif ($status == 'bulk_removed') {
                            $alertClass = "bg-red-100 border-red-400 text-red-700";
                            $message = "All selected students have been removed from the system.";
                            $icon = "times-circle";
                        } elseif ($status == 'no_selection') {
                            $alertClass = "bg-yellow-100 border-yellow-400 text-yellow-700";
                            $message = "No students were selected for the action.";
                            $icon = "exclamation-circle";
                        }

                        if (!empty($alertClass)) {
                            echo "<div class='fade-alert px-4 py-3 mb-6 rounded border flex items-center $alertClass' role='alert'>
                                    <i class='fas fa-$icon mr-2'></i>
                                    <p>$message</p>
                                  </div>";
                        }
                    }
                    ?>

                    <div class="overflow-x-auto">
                        <form method="POST" action="verify_accounts.php">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <input type="checkbox" id="select-all"
                                                class="rounded text-primary focus:ring-primary">
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Student ID
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Subject
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Section
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($result as $row): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="checkbox" name="student_ids[]"
                                                    value="<?= htmlspecialchars($row['school_id']) ?>"
                                                    class="rounded text-primary focus:ring-primary">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= htmlspecialchars($row['school_id']) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= htmlspecialchars($row['email']) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars(ucwords($row['firstname'] . ' ' . $row['lastname'])) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= htmlspecialchars($row['subject']) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= htmlspecialchars($row['section']) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <div class="mt-6 flex space-x-4">
                                <button type="submit" name="action" value="bulk_confirm"
                                    class="h-12 w-12 flex items-center justify-center bg-primary hover:bg-primary-dark text-white rounded-full shadow-md transition duration-150 ease-in-out group relative"
                                    title="Confirm All Selected">
                                    <i class="fas fa-check text-lg"></i>
                                    <span
                                        class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        Confirm All
                                    </span>
                                </button>
                                <button type="submit" name="action" value="bulk_remove"
                                    class="h-12 w-12 flex items-center justify-center bg-red-600 hover:bg-red-700 text-white rounded-full shadow-md transition duration-150 ease-in-out group relative"
                                    title="Remove All Selected">
                                    <i class="fas fa-trash-alt text-lg"></i>
                                    <span
                                        class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        Remove All
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Add the 'select all' checkbox event listener
            document.getElementById('select-all')?.addEventListener('change', function () {
                document.querySelectorAll('input[name="student_ids[]"]').forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            // Fade-out alert after a delay
            var alertElement = document.querySelector('.fade-alert');
            if (alertElement) {
                setTimeout(function () {
                    alertElement.classList.add('opacity-0');

                    // Remove the alert from the DOM after fade-out completes
                    setTimeout(function () {
                        alertElement.remove();
                    }, 1000); // 1 second after fade-out
                }, 3000); // 3 seconds before fade-out
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mainContent = document.getElementById('mainContent');
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

            if (sidebarCollapsed) {
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-16');
            } else {
                mainContent.classList.remove('ml-16');
                mainContent.classList.add('ml-64');
            }

            // Listen for sidebar toggle events from parent document
            window.addEventListener('sidebarToggled', function (e) {
                mainContent.classList.toggle('ml-64');
                mainContent.classList.toggle('ml-16');
            });
        });
    </script>
    <style>
        .fade-alert {
            transition: opacity 1s ease-in-out;
        }
    </style>
</body>

</html>