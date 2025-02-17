<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: ../index.php');
    exit;
}

if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: unauthorized.php');
    exit;
}

include 'header.php';
include 'sidebar.php';
include 'footer.php';
include '../database/connection.php';
include 'audit_log.php';

$id = isset($_GET['academic_id']) ? $_GET['academic_id'] : null;

$stmt = $conn->prepare('SELECT * FROM academic_list');
$stmt->execute();
$academics = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM academic_list WHERE academic_id = ?");
    $stmt->execute([$id]);
    $academics = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_id = $_SESSION['user']['id'] ?? 0;

    if (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];
        
        // Fetch year and semester before deleting
        $stmt = $conn->prepare('SELECT year, semester FROM academic_list WHERE academic_id = ?');
        $stmt->execute([$delete_id]);
        $academic = $stmt->fetch(PDO::FETCH_ASSOC);
        $year = $academic['year'] ?? 'Unknown';
        $semester = $academic['semester'] ?? 'Unknown';

        $stmt = $conn->prepare('DELETE FROM academic_list WHERE academic_id = :id');
        $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            log_action($conn, $admin_id, 'Deleted Academic Year', "Academic ID: $delete_id, Year: $year, Semester: $semester");
            $_SESSION['flash_message'] = 'Academic year deleted successfully.';
            $_SESSION['flash_type'] = 'success';
        } else {
            error_log('Error deleting academic year');
            $_SESSION['flash_message'] = 'Error deleting academic year. Please try again.';
            $_SESSION['flash_type'] = 'error';
        }

        echo "<script>window.location.replace('academic_list.php');</script>";
    } elseif (isset($_POST['year'], $_POST['semester'])) {
        $year = $_POST['year'];
        $semester = $_POST['semester'];
        $id = $_POST['academic_id'] ?? null;

        if ($id) {
            $query = "UPDATE academic_list SET year = ?, semester = ? WHERE academic_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$year, $semester, $id]);

            log_action($conn, $admin_id, 'Updated Academic Year', "Academic ID: $id, Year: $year, Semester: $semester");
        } else {
            $query = "INSERT INTO academic_list (year, semester) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$year, $semester]);

            $new_id = $conn->lastInsertId();
            log_action($conn, $admin_id, 'Added New Academic Year', "Academic ID: $new_id, Year: $year, Semester: $semester");
        }

        $_SESSION['flash_message'] = 'Data successfully saved.';
        $_SESSION['flash_type'] = 'success';

        echo "<script>window.location.replace('academic_list.php');</script>";
        exit;
    } elseif (isset($_POST['update_status'])) {
        $academic_id = $_POST['academic_id'];
        $status = $_POST['status'];

        // Fetch year and semester for logging
        $stmt = $conn->prepare('SELECT year, semester FROM academic_list WHERE academic_id = ?');
        $stmt->execute([$academic_id]);
        $academic = $stmt->fetch(PDO::FETCH_ASSOC);
        $year = $academic['year'] ?? 'Unknown';
        $semester = $academic['semester'] ?? 'Unknown';

        try {
            $conn->beginTransaction();

            if ($status == 1) {
                $start_date = date('Y-m-d');
                $end_date = date('Y-m-d', strtotime('+1 year'));

                $conn->query('UPDATE academic_list SET status = 0, is_default = 0, start_date = NULL, end_date = NULL WHERE status != 2');

                $stmt = $conn->prepare('UPDATE academic_list SET status = ?, is_default = 1, start_date = ?, end_date = ? WHERE academic_id = ?');
                $stmt->execute([$status, $start_date, $end_date, $academic_id]);

                $stmt = $conn->prepare('UPDATE users SET academic_id = ?');
                $stmt->execute([$academic_id]);

                $stmt = $conn->prepare('UPDATE student_list SET account_status = 1 WHERE academic_id = ?');
                $stmt->execute([$academic_id]);

                $stmt = $conn->prepare('UPDATE college_faculty_list SET academic_id = ?');
                $stmt->execute([$academic_id]);

                $stmt = $conn->prepare('UPDATE head_faculty_list SET academic_id = ?');
                $stmt->execute([$academic_id]);

                log_action($conn, $admin_id, 'Activated Academic Year', "Academic ID: $academic_id, Year: $year, Semester: $semester, Start Date: $start_date, End Date: $end_date");

            } elseif ($status == 2) {
                $stmt = $conn->prepare('INSERT INTO archives_student_list (student_id, email, archive_reason, archive_date)
                                        SELECT student_id, email, ?, NOW() FROM student_list
                                        WHERE academic_id = ?');
                $stmt->execute(['Academic period closed', $academic_id]);

                log_action($conn, $admin_id, 'Archived Students', "Academic ID: $academic_id, Year: $year, Semester: $semester");

                $stmt = $conn->prepare('DELETE FROM student_list WHERE academic_id = ?');
                $stmt->execute([$academic_id]);

                log_action($conn, $admin_id, 'Deleted Student Records', "Academic ID: $academic_id, Year: $year, Semester: $semester");

                $stmt = $conn->prepare('UPDATE academic_list SET status = ?, is_default = 0, start_date = NULL, end_date = NULL WHERE academic_id = ?');
                $stmt->execute([$status, $academic_id]);

                log_action($conn, $admin_id, 'Closed Academic Year', "Academic ID: $academic_id, Year: $year, Semester: $semester");
            }

            $conn->commit();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $conn->rollBack();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }

        exit;
    }
}
?>
