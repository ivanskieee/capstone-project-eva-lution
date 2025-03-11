<?php
include 'handlers/eval_handler.php';

$query = "SELECT LOWER(subject) AS subject FROM student_list WHERE student_id = :student_id";
$stmt = $conn->prepare($query);
$stmt->execute(['student_id' => $student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

$subjects = $student['subject'] ?? '';

// Normalize the subjects
$normalizedSubjects = array_map('trim', explode(',', strtolower($subjects)));
$normalizedSubjectsString = implode(',', $normalizedSubjects);
?>

<div class="content">
	<nav class="main-header">
		<div class="col-lg-12 mt-5">
			<div class="row">
				<div class="col-md-3">
					<h4 class="mb-3 mx-5" style="color: #333; font-weight: bold;">Faculty Members</h4>
					<div class="list-group">
						<?php
						$subjects = $_GET['subjects'] ?? '';
						$subjectArray = explode(',', strtolower($subjects));

						if (empty($subjects)) {
							echo '<div class="alert alert-success" id="success-message">Successfully evaluated the faculty member.</div>';
						} else {
							$displayedFaculty = [];

							foreach ($subjectArray as $subject) {
								$subject = trim($subject);

								$query = "
										SELECT cf.faculty_id AS fid, cf.firstname, cf.lastname
										FROM college_faculty_list cf
										WHERE EXISTS (
											SELECT 1
											FROM student_list sl
											WHERE sl.student_id = :student_id
										)
										AND cf.subject REGEXP :subject
									";
								$stmt = $conn->prepare($query);
								$stmt->execute([
									'student_id' => $_SESSION['user']['student_id'],
									'subject' => '\\b' . strtolower($subject) . '\\b',
								]);

								if ($stmt->rowCount() > 0) {
									while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
										// Check if the student has already evaluated this faculty
										$evaluationStmt = $conn->prepare('SELECT COUNT(*) FROM evaluation_answers WHERE faculty_id = ? AND student_id = ?');
										$evaluationStmt->execute([$row['fid'], $_SESSION['user']['student_id']]);
										$evaluationExists = $evaluationStmt->fetchColumn();

										// If the student has already evaluated this faculty, skip this faculty
										if ($evaluationExists > 0) {
											continue;
										}

										if (in_array($row['fid'], $displayedFaculty)) {
											continue;
										}

										$displayedFaculty[] = $row['fid'];
										$is_active = (isset($_GET['rid']) && $_GET['rid'] == $row['fid']) ? 'list-group-item-success' : '';
										?>
										<a class="list-group-item list-group-item-action <?php echo $is_active; ?>"
											href="./evaluate.php?rid=<?php echo $row['fid']; ?>&subjects=<?php echo urlencode($subjects); ?>">
											<?php echo htmlspecialchars(ucwords($row['firstname'] . ' ' . $row['lastname']), ENT_QUOTES, 'UTF-8'); ?>
										</a>
										<?php
									}
								} else {
									echo '<div class="alert alert-warning">No faculty members found for ' . htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') . '.</div>';
								}
							}
						}
						?>
					</div>
				</div>
				<?php
				// Retrieve faculty_id from URL
				$faculty_id = $_GET['rid'] ?? null;
				?>

				<div class="col-md-9">
					<div class="card card-outline card-success">
						<div class="card-header">
							<b>Evaluation Questionnaires</b>
							<div class="card-tools">
								<form id="evaluation-form" method="POST" action="eval_handler.php">
									<button type="submit" id="submit-btn"
										class="btn btn-sm btn-flat btn-success bg-gradient-success mx-1">Submit
										Evaluation</button>
							</div>
						</div>
						<!-- Add a wrapper div with a class for grayed-out state -->
						<div class="card-body" id="evaluation-questions" class="disabled-section">
							<fieldset class="border border-success p-2 w-100">
								<legend class="w-auto">Rating Legend</legend>
								<p>4 = Strongly Agree, 3 = Agree, 2 = Disagree, 1 = Strongly Disagree</p>
							</fieldset>
							<input type="hidden" name="faculty_id"
								value="<?= htmlspecialchars($faculty_id, ENT_QUOTES, 'UTF-8') ?>">
							<input type="hidden" name="subject_id"
								value="<?= htmlspecialchars($subject_id ?? '', ENT_QUOTES, 'UTF-8') ?>">
							<input type="hidden" name="evaluation_id"
								value="<?= htmlspecialchars($evaluation_id ?? '', ENT_QUOTES, 'UTF-8') ?>">
							<div class="clear-fix mt-2"></div>

							<?php foreach ($criteriaList as $row): ?>
								<table class="table table-condensed">
									<thead>
										<tr class="bg-gradient-secondary">
											<th class="p-1"><b><?php echo $row['criteria'] ?></b></th>
											<th class="text-center">1</th>
											<th class="text-center">2</th>
											<th class="text-center">3</th>
											<th class="text-center">4</th>
										</tr>
									</thead>
									<tbody class="tr-sortable">
										<?php
										foreach ($questions as $qRow) {
											if ($qRow['criteria_id'] == $row['criteria_id']) {
												?>
												<tr class="bg-white">
													<td class="p-1" width="40%">
														<?php echo htmlspecialchars($qRow['question']); ?>
														<input type="hidden" name="question_id[]"
															value="<?php echo htmlspecialchars($qRow['question_id']); ?>">
													</td>
													<?php if ($qRow['question_type'] == 'mcq'): ?>
														<?php for ($c = 1; $c <= 4; $c++): ?>
															<td class="text-center">
																<div class="icheck-success d-inline">
																	<input type="radio" name="rate[<?= $qRow['question_id'] ?>]"
																		id="qradio<?= $qRow['question_id'] . '_' . $c ?>" value="<?= $c ?>"
																		required disabled>
																	<label for="qradio<?= $qRow['question_id'] . '_' . $c ?>"></label>
																</div>
															</td>
														<?php endfor; ?>
													<?php elseif ($qRow['question_type'] == 'text'): ?>
														<!-- Comment textarea in the same row -->
														<td colspan="4" class="text-center">
															<textarea name="comment[<?= $qRow['question_id'] ?>]" class="form-control"
																rows="3" placeholder="Enter your answer" required disabled></textarea>
														</td>
													<?php endif; ?>
												</tr>
												<?php
											}
										}
										?>
									</tbody>
								</table>
							<?php endforeach; ?>
							<div class="card-header mt-3">
							<b></b>
							<div class="card-tools">
								<form id="evaluation-form" method="POST" action="eval_handler.php">
									<button type="submit" id="submit-btn"
										class="btn btn-sm btn-flat btn-success bg-gradient-success mx-1">Submit
										Evaluation</button>
							</div>
						</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</nav>
</div>

<script>
    $(document).ready(function () {
        // Disable submit button initially
        $('#submit-btn').prop('disabled', true);

        // Handle faculty item click
        $('.list-group-item').click(function (e) {
            e.preventDefault(); // Prevent the default link action

            // Remove 'list-group-item-success' from all items
            $('.list-group-item').removeClass('list-group-item-success');

            // Add 'list-group-item-success' to the clicked item
            $(this).addClass('list-group-item-success');

            // Get the faculty ID from the link's href
            let facultyId = $(this).attr('href').split('=')[1]; // Get the faculty ID after 'rid='

            if (facultyId) {
                // Set the faculty ID in the hidden input field
                $('input[name="faculty_id"]').val(facultyId);

                // Enable the submit button
                $('#submit-btn').prop('disabled', false);

                // Remove the grayed-out state and enable interaction
                $('#evaluation-questions')
                    .addClass('show').show();

                // Enable all disabled input fields within the evaluation section
                $('#evaluation-questions input, #evaluation-questions textarea').prop('disabled', false);
            }
        });

        // Ensure submit button stays disabled if no faculty is selected
        $('#evaluation-form').on('submit', function (e) {
            let selectedFaculty = $('input[name="faculty_id"]').val();
            if (!selectedFaculty) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Faculty Not Selected',
                    text: 'Please select a faculty member before submitting your evaluation.',
                    confirmButtonText: 'Okay'
                });
            }
        });
    });
</script>

<script>
	$(document).ready(function () {
		$('#evaluation-form').on('submit', function (e) {
			// Prevent form submission to handle validation
			e.preventDefault();

			// Check if a faculty member is selected
			const selectedFaculty = $('input[name="faculty_id"]').val();

			// If no faculty is selected, show SweetAlert
			if (!selectedFaculty) {
				Swal.fire({
					icon: 'warning',
					title: 'Faculty Not Selected',
					text: 'Please select a faculty member before submitting your evaluation.',
					confirmButtonText: 'Okay'
				});
				return; // Exit the function if no faculty is selected
			}

			// If faculty is selected, proceed with form submission (AJAX)
			var formData = $(this).serialize();

			$.ajax({
				type: 'POST',
				url: 'evaluate.php',
				data: formData,
				success: function (response) {
					Swal.fire({
						icon: 'success',
						title: 'Success!',
						text: 'Your answers have been saved successfully.',
						showConfirmButton: false,
						timer: 2000
					}).then(() => {
						window.location.href = 'evaluate.php';
					});

					$('#evaluation-form')[0].reset();  // Optionally reset the form after success
				},
				error: function () {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: 'Something went wrong! Please try again.',
					});
				}
			});
		});
	});
</script>
<script>
	$(document).ready(function () {
		// Check if the success message is visible
		if ($('#success-message').length) {
			// Fade the message out after 1 second
			setTimeout(function () {
				$('#success-message').fadeOut('slow', function () {
					// Use the normalized subjects string in the URL
					window.location.href = 'evaluate.php?subjects=<?php echo urlencode($normalizedSubjectsString); ?>';
				});
			}, 1000); // 1000 ms = 1 second
		}
	});
</script>
<style>
	.content .main-header {
		max-height: 90vh;
		overflow-y: auto;
		scroll-behavior: smooth;
	}

	#evaluation-questions {
		opacity: 0.5;
		/* Gray out the section */
		pointer-events: none;
		/* Disable interaction */
	}

	#evaluation-questions.show {
		opacity: 1;
		/* Restore full opacity */
		pointer-events: auto;
		/* Enable interaction */
	}
	/* Grayed-out and non-interactive state */
    .disabled-section {
        opacity: 0.5; /* Make the section semi-transparent */
        pointer-events: none; /* Disable interaction */
    }

    /* Active state (when a faculty member is selected) */
    .active-section {
        opacity: 1; /* Restore full opacity */
        pointer-events: auto; /* Enable interaction */
    }
</style>
<script>
    // Check if the criteria contains any 'text' type questions
    document.addEventListener('DOMContentLoaded', function() {
        const tables = document.querySelectorAll('table');
        
        tables.forEach(table => {
            const rows = table.querySelectorAll('tbody tr');
            let hasTextQuestion = false;

            // Check if there's a text-type question in the current table
            rows.forEach(row => {
                if (row.querySelector('textarea')) {
                    hasTextQuestion = true;
                }
            });

            if (hasTextQuestion) {
                // Hide the rating columns (1 to 4)
                const headerCells = table.querySelectorAll('thead th');
                headerCells.forEach((cell, index) => {
                    if (index > 0) {
                        cell.style.display = 'none'; // Hide columns 1 to 4
                    }
                });
            }
        });
    });
</script>

<?php include 'footer.php'; ?>