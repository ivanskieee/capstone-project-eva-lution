<?php
include 'handlers/eval_handler.php';
?>

<nav class="main-header">
	<div class="col-lg-12 mt-5">
		<div class="row">
			<div class="col-md-3">
				<div class="list-group">
					<?php
					// Your PDO query execution
					$query = "SELECT cf.faculty_id as fid, cf.firstname, cf.lastname, s.subject_id as sid, s.code, s.subject 
						FROM college_faculty_list cf
						JOIN subject_list s ON cf.faculty_id = s.subject_id";  // Adjust column names as needed
					
					$stmt = $conn->query($query); // Execute the query
					
					// Use fetch() instead of fetch_assoc()
					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
						if (empty($rid)) {
							$rid = $row['fid'];
							$faculty_id = $row['fid'];
							$subject_id = $row['sid'];
						}
						?>
						<a class="list-group-item list-group-item-action <?php echo isset($rid) && $rid == $row['fid'] ? 'active' : '' ?>"
							href="./index.php?page=evaluate&rid=<?php echo $row['fid'] ?>&sid=<?php echo $row['sid'] ?>">
							<?php echo ucwords($row['firstname'] . ' ' . $row['lastname']) . ' - (' . $row["code"] . ') ' . $row['subject'] ?>
						</a>
					<?php endwhile; ?>

				</div>
			</div>
			<div class="col-md-9">
				<div class="card card-outline card-success">
					<div class="card-header">
						<b>Evaluation Questionnaire for Academic: </b>
						<div class="card-tools">
							<button class="btn btn-sm btn-flat btn-success bg-gradient-success mx-1"
								form="manage-evaluation">Submit Evaluation</button>
						</div>
					</div>
					<div class="card-body">
						<fieldset class="border border-success p-2 w-100">
							<legend class="w-auto">Rating Legend</legend>
							<p>5 = Strongly Agree, 4 = Agree, 3 = Uncertain, 2 = Disagree, 1 = Strongly Disagree</p>
						</fieldset>
						<form id="manage-evaluation">
							<input type="hidden" name="class_id" value="">
							<input type="hidden" name="faculty_id" value="">
							<input type="hidden" name="restriction_id" value="">
							<input type="hidden" name="subject_id" value="">
							<input type="hidden" name="academic_id" value="">
							<div class="clear-fix mt-2"></div>
							<?php foreach ($criteriaList as $row): ?>
								<table class="table table-condensed">
									<thead>
										<tr class="bg-gradient-secondary">
											<th class=" p-1"><b><?php echo $row['criteria'] ?></b></th>
											<th class="text-center">1</th>
											<th class="text-center">2</th>
											<th class="text-center">3</th>
											<th class="text-center">4</th>
											<th class="text-center">5</th>
										</tr>
									</thead>
									<tbody class="tr-sortable">
										<?php
										$hasQuestions = false;

										if (is_array($questions)) {
											foreach ($questions as $qRow) {
												if (is_array($qRow) && $qRow['criteria_id'] == $row['criteria_id']) {
													$hasQuestions = true;
													?>
													<tr class="bg-white">
														<td class="p-1" width="40%">
															<?php echo htmlspecialchars($qRow['question']); // Display the question text ?>
															<input type="hidden" name="qid[]"
																value="<?php echo htmlspecialchars($qRow['question_id']); // Question ID ?>">
														</td>
														<?php for ($c = 0; $c < 5; $c++): ?>
															<td class="text-center">
																<div class="icheck-success d-inline">
																	<input type="radio" name="rate[<?= $qRow['question_id'] ?>][]"
																		id="qradio<?= $qRow['question_id'] . '_' . $c ?>"
																		value="<?= $c + 1 ?>">
																	<label for="qradio<?= $qRow['question_id'] . '_' . $c ?>"></label>
																</div>
															</td>
														<?php endfor; ?>
													</tr>
													<?php
												}
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
		if ('<?php echo $_SESSION['academic']['status'] ?>' == 0) {
			uni_modal("Information", "<?php echo $_SESSION['login_view_folder'] ?>not_started.php")
		} else if ('<?php echo $_SESSION['academic']['status'] ?>' == 2) {
			uni_modal("Information", "<?php echo $_SESSION['login_view_folder'] ?>closed.php")
		}
		else if ('<?php echo $_SESSION['academic']['status'] ?>' == 2) {
			uni_modal("Information", "<?php echo $_SESSION['login_view_folder'] ?>closed.php")
		}
		if (<?php echo empty($rid) ? 1 : 0 ?> == 1)
			uni_modal("Information", "<?php echo $_SESSION['login_view_folder'] ?>done.php")
	})
	$('#manage-evaluation').submit(function (e) {
		e.preventDefault();
		start_load()
		$.ajax({
			url: 'ajax.php?action=save_evaluation',
			method: 'POST',
			data: $(this).serialize(),
			success: function (resp) {
				if (resp == 1) {
					alert_toast("Data successfully saved.", "success");
					setTimeout(function () {
						location.reload()
					}, 1750)
				}
			}
		})
	})
</script>
<?php include 'footer.php'; ?>