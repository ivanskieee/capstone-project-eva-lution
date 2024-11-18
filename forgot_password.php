<?php
include "forgotpass_handler.php";
if (isset($_GET['access']) && $_GET['access'] === 'allowed') {
    $_SESSION['allow_forgot_password'] = true;
    header('Location: forgot_password.php');
    exit();
}

if (!isset($_SESSION['allow_forgot_password']) || $_SESSION['allow_forgot_password'] !== true) {
    header('Location: index.php');
    exit();
}

unset($_SESSION['allow_forgot_password']);
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 mt-4">
            <div class="login-box">
                <div class="card">
                    <div class="d-flex justify-content-start p-3">
                        <a href="index.php" class="btn btn-outline-secondary btn-sm" style="margin: 0;">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                    <div class="card-body login-card-body">
                        <div class="login-logo">
                            <a href="forgot_password.php">Forgot Password</a>
                        </div>
                        <form action="forgot_password.php" method="POST" id="forgot-password-form">
                            <div class="input-group mb-2">
                                <input type="email" class="form-control" name="email" required
                                    placeholder="Enter your email">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Send Reset Link</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['flash_messages'])): ?>
    <?php foreach ($_SESSION['flash_messages'] as $type => $messages): ?>
        <?php foreach ($messages as $message): ?>
            <div class="flash-message alert alert-<?php echo htmlspecialchars($type); ?> fixed-bottom mb-5 mx-auto"
                style="max-width: 300px; opacity: 1; transition: opacity 0.5s ease-in-out;">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
    <?php unset($_SESSION['flash_messages']); ?>
<?php endif; ?>

<script>
    
    document.addEventListener("DOMContentLoaded", function() {
       
        const flashMessages = document.querySelectorAll('.flash-message');
        
        setTimeout(function() {
            flashMessages.forEach(function(message) {
                message.style.opacity = '0'; 
            });
        }, 3000); 

        
        setTimeout(function() {
            flashMessages.forEach(function(message) {
                message.remove(); 
            });
        }, 3500); 
    });
</script>
