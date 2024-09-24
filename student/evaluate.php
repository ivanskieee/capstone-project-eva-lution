
<?php 
include 'header.php';
include 'sidebar.php';
?>

<nav class="main-header">
<div class="col-lg-12 mt-5">
	<div class="row">
		<div class="col-md-3">
			<div class="list-group">
				<a class="list-group-item list-group-item-action" href="#"></a>
			</div>
		</div>	
		<div class="col-md-9">
			<div class="card card-outline card-info">
				<div class="card-header">
					<b>Evaluation Questionnaire for Academic: </b>
					<div class="card-tools">
						<button class="btn btn-sm btn-flat btn-primary bg-gradient-primary mx-1" form="manage-evaluation">Submit Evaluation</button>
					</div>
				</div>
				<div class="card-body">
					<fieldset class="border border-info p-2 w-100">
					   <legend  class="w-auto">Rating Legend</legend>
					   <p>5 = Strongly Agree, 4 = Agree, 3 = Uncertain, 2 = Disagree, 1 = Strongly Disagree</p>
					</fieldset>
					<form id="manage-evaluation">
						<input type="hidden" name="class_id" value="">
						<input type="hidden" name="faculty_id" value="">
						<input type="hidden" name="restriction_id" value="">
						<input type="hidden" name="subject_id" value="">
						<input type="hidden" name="academic_id" value="">
					<div class="clear-fix mt-2"></div>
					<table class="table table-condensed">
						<thead>
							<tr class="bg-gradient-secondary">
								<th class=" p-1"><b></b></th>
								<th class="text-center">1</th>
								<th class="text-center">2</th>
								<th class="text-center">3</th>
								<th class="text-center">4</th>
								<th class="text-center">5</th>
							</tr>
						</thead>
						<tbody class="tr-sortable">
							<tr class="bg-white">
								<td class="p-1" width="40%">
									
									<input type="hidden" name="qid[]" value="">
								</td>
								
								<td class="text-center">
									<div class="icheck-success d-inline">
				                        <input type="radio" name="rate[">
				                        <label for="qradio">
				                        </label>
			                      </div>
								</td>
								
							</tr>
							
						</tbody>
					</table>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
</nav>
<script>
	$(document).ready(function(){
		if('<?php echo $_SESSION['academic']['status'] ?>' == 0){
			uni_modal("Information","<?php echo $_SESSION['login_view_folder'] ?>not_started.php")
		}else if('<?php echo $_SESSION['academic']['status'] ?>' == 2){
			uni_modal("Information","<?php echo $_SESSION['login_view_folder'] ?>closed.php")
		}
		else if('<?php echo $_SESSION['academic']['status'] ?>' == 2){
			uni_modal("Information","<?php echo $_SESSION['login_view_folder'] ?>closed.php")
		}
		if(<?php echo empty($rid) ? 1 : 0 ?> == 1)
			uni_modal("Information","<?php echo $_SESSION['login_view_folder'] ?>done.php")
	})
	$('#manage-evaluation').submit(function(e){
		e.preventDefault();
		start_load()
		$.ajax({
			url:'ajax.php?action=save_evaluation',
			method:'POST',
			data:$(this).serialize(),
			success:function(resp){
				if(resp == 1){
					alert_toast("Data successfully saved.","success");
					setTimeout(function(){
						location.reload()	
					},1750)
				}
			}
		})
	})
</script>
<?php include 'footer.php'; ?>