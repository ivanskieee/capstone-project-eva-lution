<?php
session_start();

function flash($message, $type = 'info')
{
    if (!isset($_SESSION['flash_messages'])) {
        $_SESSION['flash_messages'] = [];
    }
    $_SESSION['flash_messages'][$type][] = $message;
}

include 'includes/header.php';

if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'admin') {
        header('location: admin/home.php');
    } elseif ($_SESSION['user']['role'] === 'student') {
        header('location: student/home.php');
    }
    exit;
}

if ($_POST) {
    include('database/connection.php');

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verify Admin Login (without password verification)
    $query = 'SELECT * FROM users WHERE email = :email AND password = :password';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);  // Admin password not hashed
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetch();
        $user['role'] = 'admin';
        $_SESSION['user'] = $user;
        header('location: admin/home.php');
        exit;
    }

    // Verify Student Login (with password verification)
    $query = 'SELECT * FROM student_list WHERE email = :email';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetch();

        // Verify hashed password for student
        if (password_verify($password, $user['password'])) {
            $user['role'] = 'student';
            $_SESSION['user'] = $user;
            header('location: student/home.php');
            exit;
        }
    }

    // Verify Faculty Login (with password verification)
    $query = 'SELECT * FROM faculty_list WHERE email = :email';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetch();

        // Verify hashed password for faculty
        if (password_verify($password, $user['password'])) {
            $user['role'] = 'faculty';
            $_SESSION['user'] = $user;
            header('location: faculty/home.php');
            exit;
        }
    }

    // If login fails
    flash('Username or password is incorrect.', 'danger');
    header('location: index.php');
    exit;
}
?>


<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 mt-4">
            <div class="login-box">
                <div class="login-logo">
                    <a href="#">Login</a>
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
                                <a href="forgot_password.php" class="text-decoration-none">Forgot Your Password?</a>
                            </div>
                            <div class="icheck-primary mb-3 text-left">
                                <input type="checkbox" id="remember" name="remember" <?php echo isset($_COOKIE['email']) ? 'checked' : ''; ?>>
                                <label for="remember">Remember Me</label>
                            </div>
                            <div id="loading-spinner" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i> Logging in...
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
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


<?php include 'includes/footer.php'; ?>