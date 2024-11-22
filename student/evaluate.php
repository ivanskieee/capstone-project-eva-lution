<?php
include 'handlers/eval_handler.php';
?>

<nav class="main-header">
	<div class="col-lg-12 mt-5">
		<div class="row">
			<div class="col-md-3">
				<div class="list-group">
					<?php
					// Retrieve and process subjects from the query parameter
					$subjects = $_GET['subjects'] ?? ''; // Get the subjects string
					$subjectArray = explode(',', $subjects); // Convert to an array
					
					if (empty($subjects)) {
						echo '<div class="alert alert-warning">No subjects available for evaluation.</div>';
					} else {
						// Initialize an array to track the faculty members already displayed
						$displayedFaculty = [];

						foreach ($subjectArray as $subject) {
							$subject = trim($subject); // Clean up whitespace
					
							// Prepare the SQL query to join college_faculty_list and student_list based on the subject
							$query = "
            SELECT cf.faculty_id AS fid, cf.firstname, cf.lastname
            FROM college_faculty_list cf
            INNER JOIN student_list sl ON FIND_IN_SET(:subject, sl.subject) > 0
            WHERE FIND_IN_SET(:subject, cf.subject) > 0
        ";

							// Prepare and execute the query
							$stmt = $conn->prepare($query);
							$stmt->execute(['subject' => $subject]);

							// Check if results exist for the current subject
							if ($stmt->rowCount() === 0) {
								echo '<div class="alert alert-warning">No faculty members found for ' . htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') . '.</div>';
							} else {
								// Display results
								while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									// Skip faculty already displayed
									if (in_array($row['fid'], $displayedFaculty)) {
										continue;
									}

									// Mark the faculty as displayed
									$displayedFaculty[] = $row['fid'];

									// Determine if the current row is active
									$is_active = (isset($_GET['rid']) && $_GET['rid'] == $row['fid']) ? 'list-group-item-success' : '';
									?>
									<a class="list-group-item list-group-item-action <?php echo $is_active; ?>"
										href="./evaluate.php?rid=<?php echo $row['fid']; ?>&subjects=<?php echo urlencode($subjects); ?>">
										<?php echo htmlspecialchars(ucwords($row['firstname'] . ' ' . $row['lastname']), ENT_QUOTES, 'UTF-8'); ?>
									</a>
									<?php
								}
							}
						}
					}
					?>

				</div>
			</div>
			<div class="col-md-9">
				<div class="card card-outline card-success">
					<div class="card-header">
						<b>Evaluation Questionnaire for Academic: </b>
						<div class="card-tools">
							<form id="evaluation-form" method="POST" action="evaluate.php">
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
						<input type="hidden" name="faculty_id" value="<?= isset($faculty_id) ? $faculty_id : '' ?>">
						<input type="hidden" name="subject_id" value="<?= isset($subject_id) ? $subject_id : '' ?>">
						<input type="hidden" name="evaluation_id"
							value="<?= isset($evaluation_id) ? $evaluation_id : '' ?>">
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
												<td class="p-1" width="40%"><?php echo htmlspecialchars($qRow['question']); ?>
													<input type="hidden" name="question_id[]"
														value="<?php echo htmlspecialchars($qRow['question_id']); ?>">
												</td>
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
			e.preventDefault();

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

					$('#evaluation-form')[0].reset();
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
<?php include 'footer.php'; ?>