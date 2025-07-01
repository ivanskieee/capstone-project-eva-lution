<?php
include 'handlers/admin_link_handler.php';

$generated_link = isset($_SESSION['generated_token']) ? "https://spcevalbeta.x10.mx/student_signup.php?token=" . $_SESSION['generated_token'] : null;
?>

<div class="content">
    <nav class="main-header">
        <div class="col-lg-12 mt-3">
            <div class="container mt-3">
                <div class="col-12 mb-5">
                    <h2 class="text-start"
                        style="font-size: 1.8rem; font-weight: bold; color: #4a4a4a; border-bottom: 2px solid #ccc; padding-bottom: 5px;">
                        Account Links</h2>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center">Generate Registration Link</h2>
                        <p class="text-center">Click the button below to generate a unique registration link for
                            students:
                        </p>

                        <!-- Display Generated Link Only After Button Click -->
                        <?php if ($generated_link): ?>
                            <div class="link-container mt-4 text-center" id="link-container">
                                <label for="generated-link" class="control-label">Generated Link:</label>
                                <div class="input-group justify-content-center mt-2">
                                    <input type="text" id="generated-link" class="form-control form-control-sm"
                                        value="<?php echo $generated_link; ?>" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary copy-btn" onclick="copyLink()">Copy Link</button>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Generate Button -->
                        <form method="POST" action="admin_generate_link.php" id="generate_link_form">
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success btn-secondary-blue">Generate Link</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>

<script>
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
<style>
    .content .main-header {
        max-height: 100vh;
        overflow-y: auto;
        scroll-behavior: smooth;
    }
</style>