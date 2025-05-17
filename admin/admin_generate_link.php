<?php
include 'handlers/admin_link_handler.php';

$generated_link = isset($_SESSION['generated_token']) ? "http://localhost/Capstone-Eva-lution/student_signup.php?token=" . $_SESSION['generated_token'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPC Evaluation System - Account Links</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.3/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.3/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': 'rgb(51, 128, 64)',
                        'primary-dark': '#276833',
                        'primary-light': '#45a055',
                        'sidebar-dark': '#1e293b',
                        'sidebar-darker': '#0f172a',
                    }
                }
            }
        };
    </script>
</head>
<body>
    <!-- Main Content -->
    <!-- Check if sidebar is collapsed via localStorage and set initial class accordingly -->
    <div id="mainContent" class="content ml-64 pt-16 min-h-screen transition-all duration-300 bg-gray-100">
        <div class="p-4 md:p-6">
            <div class="max-w-5xl mx-auto">
                <!-- Header -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-800 pb-2 border-b-2 border-gray-200">
                        Account Links
                    </h2>
                </div>
                
                <!-- Main Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <div class="text-center">
                            <h2 class="text-xl font-semibold text-gray-800 mb-2">Generate Registration Link</h2>
                            <p class="text-gray-600 mb-8">
                                Click the button below to generate a unique registration link for students
                            </p>
                        </div>

                        <!-- Generated Link Display -->
                        <?php if ($generated_link): ?>
                        <div id="link-container" class="mb-8 bg-gray-50 p-5 rounded-lg border border-gray-200">
                            <label for="generated-link" class="block text-sm font-medium text-gray-700 mb-2">Generated Link:</label>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <input 
                                    type="text" 
                                    id="generated-link" 
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary text-sm" 
                                    value="<?php echo $generated_link; ?>" 
                                    readonly
                                >
                                <button 
                                    onclick="copyLink()" 
                                    class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md transition duration-150 ease-in-out flex items-center justify-center shadow-sm"
                                >
                                    <i class="fas fa-copy mr-2"></i>
                                    Copy Link
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Generate Button -->
                        <form method="POST" action="admin_generate_link.php" id="generate_link_form">
                            <div class="text-center">
                                <button 
                                    type="submit" 
                                    class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-md transition duration-150 ease-in-out font-medium text-sm shadow-md"
                                >
                                    <i class="fas fa-link mr-2"></i>
                                    Generate New Link
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Information Cards -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-primary">
                        <h3 class="font-semibold text-gray-800 mb-2">
                            <i class="fas fa-shield-alt mr-2 text-primary"></i>
                            Security Notice
                        </h3>
                        <p class="text-gray-600 text-sm">Registration links expire after 24 hours for security. Generate new links as needed.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-primary">
                        <h3 class="font-semibold text-gray-800 mb-2">
                            <i class="fas fa-chart-line mr-2 text-primary"></i>
                            Usage Tracking
                        </h3>
                        <p class="text-gray-600 text-sm">Each link can be used only once. Generate multiple links for multiple students.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-primary">
                        <h3 class="font-semibold text-gray-800 mb-2">
                            <i class="fas fa-question-circle mr-2 text-primary"></i>
                            Student Help
                        </h3>
                        <p class="text-gray-600 text-sm">Students will need to complete registration within 24 hours of receiving the link.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Check if sidebar state is stored in localStorage and apply it on page load
    document.addEventListener('DOMContentLoaded', function() {
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
        window.addEventListener('sidebarToggled', function(e) {
            mainContent.classList.toggle('ml-64');
            mainContent.classList.toggle('ml-16');
        });
    });

    function copyLink() {
        var copyText = document.getElementById("generated-link");
        copyText.select();
        copyText.setSelectionRange(0, 99999); 
        document.execCommand("copy");

        Swal.fire({
            icon: "success",
            title: "Copied!",
            text: "The link has been copied to your clipboard.",
            showConfirmButton: false,
            timer: 1500
        });

        fetch('clear_link.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById("generated-link").value = ''; // Clear input field
                    document.getElementById('link-container').style.display = 'none'; // Hide container
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "Failed to clear the link.",
                    showConfirmButton: true
                });
                console.error('Error:', error);
            });
    }
    </script>
</body>
</html>