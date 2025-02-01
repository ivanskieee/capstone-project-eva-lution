<?php
session_start();
include '../database/connection.php';

$head_id = $_SESSION['user']['head_id'];
$query = $conn->prepare("SELECT * FROM head_faculty_list WHERE head_id = :head_id");
$query->bindParam(':head_id', $head_id, PDO::PARAM_INT);
$query->execute();
$head = $query->fetch(PDO::FETCH_ASSOC);

if (!$head) {
    echo "head not found.";
    exit;
}

?>

<form id="accountForm" enctype="multipart/form-data">
    <!-- <div class="form-group">
        <label for="avatar" class="control-label">Avatar</label>
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="avatar" name="avatar" onchange="displayImg(this, $(this))">
            <label class="custom-file-label" for="avatar">Choose file</label>
        </div>
    </div>
    <div class="form-group d-flex justify-content-center align-items-center">
        <img src="<?php echo isset($head['avatar']) ? 'uploads/' . htmlspecialchars($head['avatar']) : ''; ?>"
            alt="Avatar" id="cimg" class="img-fluid img-thumbnail"
            style="height: 15vh; width: 15vh; object-fit: cover; border-radius: 100%;">
    </div> -->
    <div class="form-group">
        <label for="firstname">First Name</label>
        <input type="text" name="firstname" class="form-control"
            value="<?php echo htmlspecialchars($head['firstname']); ?>" required>
    </div>
    <div class="form-group">
        <label for="lastname">Last Name</label>
        <input type="text" name="lastname" class="form-control"
            value="<?php echo htmlspecialchars($head['lastname']); ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($head['email']); ?>"
            required>
    </div>
    <div class="error-message" id="password-error">Password must be at least 8 characters
        long,
        include an uppercase letter, a lowercase letter, and a special character.</div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" class="form-control" id="password" minlength="8">
        <small class="text-muted">Leave blank if you don't want to change the password.</small>
    </div>
    <div class="form-group">
        <label for="cpass">Confirm Password</label>
        <input type="password" name="cpass" class="form-control" id="cpass" minlength="8">
    </div>
</form>
<script>
    document.getElementById('password').addEventListener('input', function () {
        const password = this.value;
        const errorMessage = document.getElementById('password-error');

        // Regular expression for validation
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/;

        if (password.length === 0) {
            errorMessage.style.display = 'none'; // Hide error message when input is empty
        } else if (!regex.test(password)) {
            errorMessage.style.display = 'block'; // Show error message if password is invalid
        } else {
            errorMessage.style.display = 'none'; // Hide error message if valid
        }
    });
</script>
<style>
    .error-message {
        color: red;
        font-size: 12px;
        margin-bottom: 5px;
        display: none;
    }
</style>