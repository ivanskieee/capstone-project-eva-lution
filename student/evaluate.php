<?php
include 'handlers/eval_handler.php';

$query = "SELECT LOWER(subject) AS subject FROM student_list WHERE student_id = :student_id";
$stmt = $conn->prepare($query);
$stmt->execute(['student_id' => $student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

$subjects = $student['subject'] ?? '';

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
										$evaluationStmt = $conn->prepare('SELECT COUNT(*) FROM evaluation_answers WHERE faculty_id = ? AND student_id = ?');
										$evaluationStmt->execute([$row['fid'], $_SESSION['user']['student_id']]);
										$evaluationExists = $evaluationStmt->fetchColumn();


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
		$('#submit-btn').prop('disabled', true);

		$('.list-group-item').click(function (e) {
			e.preventDefault();


			$('.list-group-item').removeClass('list-group-item-success');


			$(this).addClass('list-group-item-success');

			let facultyId = $(this).attr('href').split('=')[1];

			if (facultyId) {

				$('input[name="faculty_id"]').val(facultyId);

				$('#submit-btn').prop('disabled', false);


				$('#evaluation-questions')
					.addClass('show').show();

				$('#evaluation-questions input, #evaluation-questions textarea').prop('disabled', false);
			}
		});

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
			e.preventDefault();

			const selectedFaculty = $('input[name="faculty_id"]').val();

			if (!selectedFaculty) {
				Swal.fire({
					icon: 'warning',
					title: 'Faculty Not Selected',
					text: 'Please select a faculty member before submitting your evaluation.',
					confirmButtonText: 'Okay'
				});
				return;
			}

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
<script>
	$(document).ready(function () {
		if ($('#success-message').length) {
			setTimeout(function () {
				$('#success-message').fadeOut('slow', function () {
					window.location.href = 'evaluate.php?subjects=<?php echo urlencode($normalizedSubjectsString); ?>';
				});
			}, 1000);
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
		pointer-events: none;
	}

	#evaluation-questions.show {
		opacity: 1;
		pointer-events: auto;
	}

	.disabled-section {
		opacity: 0.5;
		pointer-events: none;
	}

	.active-section {
		opacity: 1;
		pointer-events: auto;
	}
</style>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const tables = document.querySelectorAll('table');

		tables.forEach(table => {
			const rows = table.querySelectorAll('tbody tr');
			let hasTextQuestion = false;

			rows.forEach(row => {
				if (row.querySelector('textarea')) {
					hasTextQuestion = true;
				}
			});

			if (hasTextQuestion) {

				const headerCells = table.querySelectorAll('thead th');
				headerCells.forEach((cell, index) => {
					if (index > 0) {
						cell.style.display = 'none';
					}
				});
			}
		});
	});
</script>

<?php include 'footer.php'; ?>