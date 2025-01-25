<?php
include 'handlers/report_handler_faculty_faculty.php';
?>

<nav class="main-header">
	<div class="col-lg-12 mt-5">
		<div class="row">
			<div class="col-md-3">
				<div class="list-group">
					<?php
					$departments = $_GET['departments'] ?? ''; // Get the department from the URL parameter
					$departmentArray = explode(',', strtolower($departments));

					if (empty($departments)) {
						echo '<div class="alert alert-success">Successfully evaluated the faculty member.</div>';
					} else {
						$displayedFaculty = [];

						foreach ($departmentArray as $department) {
							$department = trim($department);

							$query = "
								SELECT cf.faculty_id AS fid, cf.firstname, cf.lastname
								FROM college_faculty_list cf
								WHERE cf.faculty_id != :current_faculty_id
								AND cf.department REGEXP :department
							";
							$stmt = $conn->prepare($query);
							$stmt->execute([
								'current_faculty_id' => $_SESSION['user']['faculty_id'], // Exclude the logged-in faculty
								'department' => '\\b' . strtolower($department) . '\\b',
							]);

							if ($stmt->rowCount() > 0) {
								while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									// Check if the logged-in faculty has already evaluated this co-faculty for the current academic_id
									$evaluationStmt = $conn->prepare('
										SELECT COUNT(*) 
										FROM evaluation_answers_faculty_faculty 
										WHERE faculty_id = ? 
										  AND evaluator_id = ? 
										  AND academic_id = (
											  SELECT academic_id 
											  FROM academic_list 
											  WHERE status = 1 -- Only include active academic years
											  LIMIT 1
										  )
									');
									$evaluationStmt->execute([
										$row['fid'], 
										$_SESSION['user']['faculty_id']
									]);
									$evaluationExists = $evaluationStmt->fetchColumn();
							
									// Skip faculty members already evaluated by the logged-in faculty for the current academic_id
									if ($evaluationExists > 0) {
										continue;
									}
							
									// Prevent duplicates
									if (in_array($row['fid'], $displayedFaculty)) {
										continue;
									}
							
									$displayedFaculty[] = $row['fid'];
									$is_active = (isset($_GET['rid']) && $_GET['rid'] == $row['fid']) ? 'list-group-item-success' : '';
									?>
									<a class="list-group-item list-group-item-action <?php echo $is_active; ?>"
										href="./evaluate_faculties.php?rid=<?php echo $row['fid']; ?>&departments=<?php echo urlencode($departments); ?>">
										<?php echo htmlspecialchars(ucwords($row['firstname'] . ' ' . $row['lastname']), ENT_QUOTES, 'UTF-8'); ?>
									</a>
									<?php
								}
							} else {
								echo '<div class="alert alert-warning">No faculty members found for ' . htmlspecialchars($department, ENT_QUOTES, 'UTF-8') . '.</div>';
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
							<form id="evaluation-form" method="POST" action="report_handler.php">
								<button type="submit"
									class="btn btn-sm btn-flat btn-success bg-gradient-success mx-1">Submit
									Evaluation</button>
						</div>
					</div>
					<div class="card-body">
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
									foreach ($questions_faculties as $qRow) {
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
																	required>
																<label for="qradio<?= $qRow['question_id'] . '_' . $c ?>"></label>
															</div>
														</td>
													<?php endfor; ?>
												<?php elseif ($qRow['question_type'] == 'text'): ?>
													<!-- Comment textarea in the same row -->
													<td colspan="4" class="text-center">
														<textarea name="comment[<?= $qRow['question_id'] ?>]" class="form-control"
															rows="3" placeholder="Enter your answer" required></textarea>
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
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</nav>
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
				url: 'evaluate_faculties.php',
				data: formData,
				success: function (response) {
					Swal.fire({
						icon: 'success',
						title: 'Success!',
						text: 'Your answers have been saved successfully.',
						showConfirmButton: false,
						timer: 2000
					}).then(() => {
						window.location.href = 'evaluate_faculties.php';
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