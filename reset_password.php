<?php
include "resetpass_handler.php";
// if (isset($_GET['access']) && $_GET['access'] === 'allowed') {
//     $stmt = $conn->prepare("SELECT token FROM password_resets");
//     $stmt->execute();

//     $result = $stmt->fetch(PDO::FETCH_ASSOC);

//     if ($result && isset($result['token'])) {
//         $_SESSION['allow_reset_password'] = true;
//         $token = $result['token'];
//         header('Location: reset_password.php?token=' . $token);
//         exit();
//     }
// }

// if (!isset($_SESSION['allow_reset_password']) || $_SESSION['allow_reset_password'] !== true) {
//     header('Location: index.php');
//     exit();
// }

// unset($_SESSION['allow_reset_password']);
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 mt-4">
            <div class="login-box">
                <div class="login-logo">
                    <a href="./">Reset Password</a>
                </div>
                <div class="card">
                    <div class="card-body login-card-body">
                        <?php if (isset($_SESSION['flash_messages'])): ?>
                            <div class="flash-message alert <?php echo isset($_SESSION['flash_messages']['danger']) ? 'alert-danger' : 'alert-success'; ?> fade show"
                                role="alert" style="opacity: 1; transition: opacity 0.5s ease-in-out;">
                                <?php
                                if (!empty($_SESSION['flash_messages']['danger'])) {
                                    foreach ($_SESSION['flash_messages']['danger'] as $message) {
                                        echo htmlspecialchars($message) . "<br>";
                                    }
                                    unset($_SESSION['flash_messages']['danger']);
                                } elseif (!empty($_SESSION['flash_messages']['success'])) {
                                    foreach ($_SESSION['flash_messages']['success'] as $message) {
                                        echo htmlspecialchars($message) . "<br>";
                                    }
                                    unset($_SESSION['flash_messages']['success']);
                                }
                                ?>
                            </div>
                        <?php endif; ?>

                        <form action="reset_password.php" method="POST">
                            <input type="hidden" name="token"
                                value="<?php echo isset($_GET['token']) ? htmlspecialchars($_GET['token']) : ''; ?>">
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" name="password" required
                                    placeholder="New Password">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const flashMessages = document.querySelectorAll('.flash-message');
        setTimeout(function () {
            flashMessages.forEach(function (message) {
                message.style.opacity = '0';
            });
        }, 3000);
        setTimeout(function () {
            flashMessages.forEach(function (message) {
                message.remove();
            });
        }, 3500);
    });
</script>