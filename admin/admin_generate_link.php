<?php
include 'handlers/admin_link_handler.php';

$generated_link = isset($_SESSION['generated_token']) ? "http://localhost/Capstone-Eva-lution/student_signup.php?token=" . $_SESSION['generated_token'] : null;
?>

<nav class="main-header">
    <div class="col-lg-12 mt-5">
        <div class="card">
            <div class="card-body">
                <h2 class="text-center">Generate Registration Link</h2>
                <p class="text-center">Click the button below to generate a unique registration link for students:</p>

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
</nav>

<script>
    function copyLink() {
        var copyText = document.getElementById("generated-link");
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices
        document.execCommand("copy");
        alert("Link copied to clipboard!");
    }
</script>


<script>
    function copyLink() {
        var copyText = document.getElementById("generated-link");
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices
        document.execCommand("copy");
        alert("Link copied to clipboard!");

        // After copying, clear the link from session storage
        fetch('clear_link.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById("generated-link").value = ''; // Clear the input field
                    document.getElementById('link-container').style.display = 'none'; // Hide the link container
                }
            })
            .catch(error => console.error('Error:', error));
    }
</script>