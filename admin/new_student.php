<?php

include "handlers/student_handler.php";

?>

<nav class="main-header">
<div class="col-lg-12 mt-5">
    <div class="card">
        <div class="card-body">
            <form method="POST" id="new_student" enctype="multipart/form-data">
                <input type="hidden" id="id" name="id" value="<?php echo isset($student['id']) ? $student['id'] : ''; ?>">
                <div class="row">
                    <div class="col-md-6 border-right">
                        <div class="form-group">
                            <label for="" class="control-label">School ID</label>
                            <input type="text" id="school_id" name="school_id" class="form-control form-control-sm" required value="<?php echo isset($student['school_id']) ? $student['school_id'] : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">First Name</label>
                            <input type="text" id="firstname" name="firstname" class="form-control form-control-sm" required value="<?php echo isset($student['firstname']) ? $student['firstname'] : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Last Name</label>
                            <input type="text" id="lastname" name="lastname" class="form-control form-control-sm" required value="<?php echo isset($student['lastname']) ? $student['lastname'] : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Class</label>
                            <select name="class_id" id="class_id" class="form-control form-control-sm select2">
                                <option value=""></option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?php echo $class['id']; ?>" <?php echo isset($student['class_id']) && $student['class_id'] == $class['id'] ? 'selected' : ''; ?>>
                                        <?php echo $class['class']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Avatar</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="img" onchange="displayImg(this, $(this))">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-center align-items-center">
                            <img src="<?php echo isset($student['avatar']) ? 'uploads/' . $student['avatar'] : ''; ?>" alt="Avatar" id="cimg" class="img-fluid img-thumbnail">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Email</label>
                            <input type="email" class="form-control form-control-sm" name="email" id="email" required value="<?php echo isset($student['email']) ? $student['email'] : ''; ?>">
                            <small id="msg"></small>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Password</label>
                            <input type="password" class="form-control form-control-sm" name="password" id="password" <?php echo isset($student) ? '' : 'required'; ?>>
                            <small><i><?php echo isset($student) ? 'Leave this blank if you don\'t want to change your password' : ''; ?></i></small>
                        </div>
                        <div class="form-group">
                            <label class="label control-label">Confirm Password</label>
                            <input type="password" class="form-control form-control-sm" name="cpass" <?php echo isset($student) ? '' : 'required'; ?>>
                            <small id="pass_match" data-status=""></small>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="col-lg-12 text-right justify-content-center d-flex">
                    <button type="submit" class="btn btn-success btn-secondary-blue mr-3"><?php echo isset($student) ? 'Update' : 'Submit'; ?></button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href = './student_list.php';">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
</nav>
<style>
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<script>
	$('[name="password"],[name="cpass"]').keyup(function(){
		var pass = $('[name="password"]').val()
		var cpass = $('[name="cpass"]').val()
		if(cpass == '' ||pass == ''){
			$('#pass_match').attr('data-status','')
		}else{
			if(cpass == pass){
				$('#pass_match').attr('data-status','1').html('<i class="text-success">Password Matched.</i>')
			}else{
				$('#pass_match').attr('data-status','2').html('<i class="text-danger">Password does not match.</i>')
			}
		}
	})
	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
	$('#manage_student').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')
		if($('[name="password"]').val() != '' && $('[name="cpass"]').val() != ''){
			if($('#pass_match').attr('data-status') != 1){
				if($("[name='password']").val() !=''){
					$('[name="password"],[name="cpass"]').addClass("border-danger")
					end_load()
					return false;
				}
			}
		}
		$.ajax({
			url:'ajax.php?action=save_student',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Data successfully saved.',"success");
					setTimeout(function(){
						location.replace('index.php?page=student_list')
					},750)
				}else if(resp == 2){
					$('#msg').html("<div class='alert alert-danger'>Email already exist.</div>");
					$('[name="email"]').addClass("border-danger")
					end_load()
				}
			}
		})
	})
</script>

