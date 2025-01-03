<?php
session_start();


include 'header.php';
include 'sidebar.php';
include '../database/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize and validate form inputs
    $skills = filter_var($_POST['skills'], FILTER_VALIDATE_INT);
    $performance = filter_var($_POST['performance'], FILTER_VALIDATE_INT);
    $comments = htmlspecialchars($_POST['comments']);

    // Fetch faculty_id from faculty_list table based on session user email or unique identifier
    $email = $_SESSION['user']['email']; // Assuming the session stores the faculty's email

    try {
        // Query to get the faculty_id
        $stmt = $conn->prepare("SELECT head_id FROM head_faculty_list WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        // Fetch the faculty_id
        $faculty = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$faculty) {
            die("Error: Faculty not found in the database.");
        }

        $facultyId = $faculty['head_id'];

        // Validate the ratings (should be between 1 and 5)
        if ($skills >= 1 && $skills <= 5 && $performance >= 1 && $performance <= 5) {

            // Calculate the average score
            $averageScore = ($skills + $performance) / 2;

            // Provide feedback based on the average score
            if ($averageScore >= 4) {
                $feedback = "Excellent performance!";
            } elseif ($averageScore >= 3) {
                $feedback = "Good performance, but there's room for improvement.";
            } else {
                $feedback = "Needs significant improvement.";
            }

            // Insert the evaluation data into the database
            $stmt = $conn->prepare("INSERT INTO self_head_eval (faculty_id, skills, performance, average_score, feedback, comments) 
                                    VALUES (:faculty_id, :skills, :performance, :average_score, :feedback, :comments)");

            // Bind parameters
            $stmt->bindParam(':faculty_id', $facultyId, PDO::PARAM_INT);
            $stmt->bindParam(':skills', $skills, PDO::PARAM_INT);
            $stmt->bindParam(':performance', $performance, PDO::PARAM_INT);
            $stmt->bindParam(':average_score', $averageScore, PDO::PARAM_STR);
            $stmt->bindParam(':feedback', $feedback, PDO::PARAM_STR);
            $stmt->bindParam(':comments', $comments, PDO::PARAM_STR);

            // Execute the statement
            if ($stmt->execute()) {
                echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Evaluation Submitted',
                            text: 'Your evaluation has been successfully saved!',
                            confirmButtonText: 'Go to Dashboard',
                            customClass: {
                                confirmButton: 'swal-success-btn'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'home.php';
                            }
                        });
                      </script>";
            } else {
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Submission Failed',
                            text: 'Unable to save your evaluation. Please try again.',
                            confirmButtonText: 'Retry'
                        });
                      </script>";
            }
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Ratings',
                        text: 'Please enter ratings between 1 and 5.',
                        confirmButtonText: 'Okay'
                    });
                  </script>";
        }
    } catch (PDOException $e) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '" . $e->getMessage() . "',
                    confirmButtonText: 'Okay'
                });
              </script>";
    }
}
?>

<nav class="main-header">


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header mt-5 text-white" style="background-color: rgb(51, 128, 64); >
                        <span class="navbar-text text-light">
                            Welcome, <?php echo $_SESSION['login_name']; ?>!
                        </span>
                        <h3 class="mb-0">Self Evaluation Form</h3>
                    </div>
                    <div class="card-body">
                        <form action="evaluate.php" method="POST">
                            <div class="mb-3">
                                <label for="skills" class="form-label">Rate your skills (1-5):</label>
                                <input type="number" id="skills" name="skills" class="form-control" min="1" max="5" required>
                            </div>

                            <div class="mb-3">
                                <label for="performance" class="form-label">Rate your performance (1-5):</label>
                                <input type="number" id="performance" name="performance" class="form-control" min="1" max="5" required>
                            </div>

                            <div class="mb-3">
                                <label for="comments" class="form-label">Additional Comments:</label>
                                <textarea id="comments" name="comments" class="form-control" rows="4" placeholder="Enter any additional comments..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-success w-100">Submit Evaluation</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
    .swal-success-btn {
        background-color: #28a745 !important; /* Success green */
        color: white !important;
        border: none !important;
        box-shadow: none !important;
    }
    .swal-success-btn:hover {
        background-color: #218838 !important; /* Darker green on hover */
    }
</style>

<?php include 'footer.php'; ?>