<?php
include 'handlers/criteria_handler.php';

?>
<nav class="main-header">
	<div class="col-lg-12">
		<div class="card card-outline card-primary">
			<div class="card-header">
			</div>
			<div class="card-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-4">
							<div class="card card-outline card-info">
								<div class="card-header"><b>Criteria Form</b></div>
								<div class="card-body">
									<form action="" id="manage-criteria" method="POST">
										<input type="hidden" name="id"
											value="<?php echo isset($criterias['criteria_id']) ? $criterias['criteria_id'] : ''; ?>">
										<div class="form-group">
											<label for="criteria">Criteria</label>
											<input type="text" name="criteria" class="form-control form-control-sm"
												value="<?php echo isset($criterias['criteria']) ? $criterias['criteria'] : ''; ?>"
												required>
										</div>
									</form>
								</div>
								<div class="card-footer">
									<div class="d-flex justify-content-end w-100">
										<button class="btn btn-sm btn-success btn-flat bg-gradient-success mx-1"
											form="manage-criteria" type="submit">Save</button>
										<button class="btn btn-sm btn-flat btn-secondary bg-gradient-secondary mx-1"
											form="manage-criteria" type="reset">Cancel</button>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-8">
							<div class="callout callout-info">
								<div class="d-flex justify-content-between w-100">
									<label for=""><b>Criteria List</b></label>
									<button class="btn btn-sm btn-success btn-flat bg-gradient-success mx-1"
										form="order-criteria">Save Order</button>
								</div>
								<hr>
								<form action="" id="order-criteria">
									<ul class="list-group btn col-md-8" id="ui-sortable-list">
										<?php
										$i = 1;
										foreach ($criterias as $row): ?>
											<li class="list-group-item text-left">
												<span class="btn-group dropright float-right">
													<span type="button" class="btn" data-toggle="dropdown"
														aria-haspopup="true" aria-expanded="false">
														<i class="fa fa-ellipsis-v"></i>
													</span>
													<div class="dropdown-menu">
														<a class="dropdown-item edit_criteria" href="javascript:void(0)"
															data-id="<?php echo isset($row['id']) ?>">Edit</a>
														<div class="dropdown-divider"></div>
														<form method="post" action="criteria_list.php"
															style="display: inline;">
															<input type="hidden" name="delete_id"
																value="<?php echo isset($row['criteria_id']) ? $row['criteria_id'] : ''; ?>">
															<button type="submit" class="dropdown-item"
																onclick="return confirm('Are you sure you want to delete this criteria?');">Delete</button>
														</form>
													</div>
												</span>
												<i class="fa fa-bars"></i> <?php echo ucwords($row['criteria']) ?>
												<input type="hidden" name="criteria_id[]"
													value="<?php echo isset($row['id']) ?>">
											</li>
										<?php endforeach; ?>
								</form>
								</ul>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</nav>
<style>
	.dropright a:hover {
		color: black !important;
	}
</style>
<script>
	$(document).ready(function () {
		$('#ui-sortable-list').sortable()
		$('.edit_criteria').click(function () {
			var id = $(this).attr('data-id')
			var criteria = <?php echo json_encode($criteria) ?>;
			$('#manage-criteria').find("[name='id']").val(criteria[id].id)
			$('#manage-criteria').find("[name='criteria']").val(criteria[id].criteria)

		})
		$('#manage-criteria').on('reset', function () {
			$(this).find('input:hidden').val('')
		})
		$('.delete_criteria').click(function () {
			_conf("Are you sure to delete this criteria?", "delete_criteria", [$(this).attr('data-id')])
		})
		$('.make_default').click(function () {
			_conf("Are you sure to make this criteria year as the system default?", "make_default", [$(this).attr('data-id')])
		})

		$('#manage-criteria').submit(function (e) {
			e.preventDefault();
			start_load()
			$('#msg').html('')
			$.ajax({
				url: 'ajax.php?action=save_criteria',
				method: 'POST',
				data: $(this).serialize(),
				success: function (resp) {
					if (resp == 1) {
						alert_toast("Data successfully saved.", "success");
						setTimeout(function () {
							location.reload()
						}, 1750)
					} else if (resp == 2) {
						$('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Criteria already exist.</div>')
						end_load()
					}
				}
			})
		})
		$('#order-criteria').submit(function (e) {
			e.preventDefault();
			start_load()
			$.ajax({
				url: 'ajax.php?action=save_criteria_order',
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

	})
	function delete_criteria($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_criteria',
			method: 'POST',
			data: { id: $id },
			success: function (resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function () {
						location.reload()
					}, 1500)

				}
			}
		})
	}
	function make_default($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=make_default',
			method: 'POST',
			data: { id: $id },
			success: function (resp) {
				if (resp == 1) {
					alert_toast("Dafaut criteria Year Updated", 'success')
					setTimeout(function () {
						location.reload()
					}, 1500)
				}
			}
		})
	}
</script>