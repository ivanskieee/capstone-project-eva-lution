<?php
session_start();
include '../database/connection.php';

$student_id = $_SESSION['user']['student_id'];
$query = $conn->prepare("SELECT * FROM student_list WHERE student_id = :student_id");
$query->bindParam(':student_id', $student_id, PDO::PARAM_INT);
$query->execute();
$student = $query->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    echo "Student not found.";
    exit;
}
?>

<form id="accountForm" enctype="multipart/form-data">
    <div class="form-group">
        <label for="avatar" class="control-label">Avatar</label>
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="avatar" name="avatar" onchange="displayImg(this, $(this))">
            <label class="custom-file-label" for="avatar">Choose file</label>
        </div>
    </div>
    <div class="form-group d-flex justify-content-center align-items-center">
        <img src="<?php echo isset($student['avatar']) ? 'uploads/' . htmlspecialchars($student['avatar']) : ''; ?>"
            alt="Avatar" id="cimg" class="img-fluid img-thumbnail"
            style="height: 15vh; width: 15vh; object-fit: cover; border-radius: 100%;">
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($student['email']); ?>"
            required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" class="form-control">
        <small class="text-muted">Leave blank if you don't want to change the password.</small>
    </div>
    <div class="form-group">
        <label for="cpass">Confirm Password</label>
        <input type="password" name="cpass" class="form-control">
    </div>
</form>