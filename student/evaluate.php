<?php
include 'handlers/eval_handler.php';
?>

<nav class="main-header">
	<div class="col-lg-12 mt-5">
		<div class="row">
			<div class="col-md-3">
				<div class="list-group">
					<?php
					$query = "SELECT cf.faculty_id as fid, cf.firstname, cf.lastname, s.subject_id as sid, s.code, s.subject 
                        FROM college_faculty_list cf
                        JOIN subject_list s ON s.subject_id = s.subject_id";

					$stmt = $conn->prepare($query);
					$stmt->execute();

					$active_rid = isset($_GET['rid']) ? $_GET['rid'] : null;
					$active_sid = isset($_GET['sid']) ? $_GET['sid'] : null;

					while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
						if (empty($rid)) {
							$rid = $row['fid'];
							$faculty_id = $row['fid'];
							$subject_id = $row['sid'];
						}

						$is_active = ($active_rid == $row['fid'] && $active_sid == $row['sid']) ? 'list-group-item-success' : '';
						?>

						<a class="list-group-item list-group-item-action <?php echo $is_active; ?>"
							href="./evaluate.php?rid=<?php echo $row['fid'] ?>&sid=<?php echo $row['sid'] ?>">
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
						<form id="evaluation-form" method="POST" action="evaluate.php">
							<button type="submit" class="btn btn-sm btn-flat btn-success bg-gradient-success mx-1">Submit Evaluation</button>
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
													<td class="p-1" width="40%">
														<?php echo htmlspecialchars($qRow['question']); ?>
														<input type="hidden" name="question_id[]"
															value="<?php echo htmlspecialchars($qRow['question_id']); ?>">
													</td>
													<?php for ($c = 1; $c <= 4; $c++): ?>
														<td class="text-center">
															<div class="icheck-success d-inline">
																<input type="radio" name="rate[<?= $qRow['question_id'] ?>]"
																	id="qradio<?= $qRow['question_id'] . '_' . $c ?>" value="<?= $c ?>">
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