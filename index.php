<?php
include "login_handler.php";
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 mt-4">
            <div class="login-box">
                <div class="login-logo">
                    <a href="./">Login</a>
                </div>
                <div class="card">
                    <div class="card-body login-card-body">
                        <form action="index.php" method="POST" id="login-form">
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" name="email" required placeholder="Email"
                                    value="<?php echo isset($_COOKIE['email']) ? htmlspecialchars($_COOKIE['email']) : ''; ?>">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-1">
                                <input type="password" class="form-control" name="password" id="password" required
                                    placeholder="Password"
                                    value="<?php echo isset($_COOKIE['password']) ? htmlspecialchars($_COOKIE['password']) : ''; ?>">
                                <div class="input-group-append">
                                    <span class="input-group-text fas fa-lock" id="lock-icon"></span>
                                </div>
                            </div>
                            <div class="mb-3 text-right">
                                <a href="forgot_password.php?access=allowed" class="text-decoration-none">Forgot Your
                                    Password?</a>
                            </div>
                            <div class="d-flex flex-column align-items-center mt-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="rememberMe" name="remember"
                                        <?php echo isset($_COOKIE['email']) ? 'checked' : ''; ?>>
                                    <label class="form-check-label ms-2" for="rememberMe">Remember me</label>
                                </div>
                            </div>
                            <div id="loading-spinner" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i> Logging in...
                            </div>
                            <br>
                            <button type="submit" class="btn btn-success btn-block">Sign In</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mt-4">
            <div class="card">
                <div class="">
                    <img src="static/css/assets/images/spcLogin.jpg" class="card-img-top" alt="Login Image">
                </div>
                <div class="card-body">
                    <h5 class="card-title fade-in fade-in-left">SPCian: Welcome to your Online Evaluation Platform!</h5>
                    <p class="card-text fade-in fade-in-left">
                        Log in to access evaluation forms, review feedback, and manage assessment tools for an enhanced
                        performance evaluation experience.
                    </p>
                </div>
            </div>
        </div>

        <?php if (isset($_SESSION['flash_messages'])): ?>
            <?php foreach ($_SESSION['flash_messages'] as $type => $messages): ?>
                <?php foreach ($messages as $message): ?>
                    <div class="flash-message alert alert-<?php echo htmlspecialchars($type); ?> fixed-bottom mb-5 mx-auto"
                        style="max-width: 300px;">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <?php unset($_SESSION['flash_messages']); ?>
        <?php endif; ?>
    </div>
</div>

<style>
    #loading-spinner {
        display: none;
        text-align: center;
        font-size: 18px;
        margin-top: 15px;
        color: black;
    }

    @media (max-width: 768px) {
        .login-box {
            width: 90%;
            margin: 0 auto;
        }

        .container {
            padding-left: 15px;
            padding-right: 15px;
        }
    }
</style>

<?php include 'includes/footer.php'; ?>