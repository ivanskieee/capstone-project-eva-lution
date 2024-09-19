<?php
include "handlers/faculty_handler.php";

?>

<nav class="main-header">
<div class="col-lg-12 mt-5">
    <div class="card">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" id="new_faculty">
                <input type="hidden" name="id" value="<?php echo isset($faculty['id']) ? $faculty['id'] : ''; ?>">
                <div class="row">
                    <div class="col-md-6 border-right">
                        <div class="form-group">
                            <label for="school_id" class="control-label">School ID</label>
                            <input type="text" name="school_id" class="form-control form-control-sm" required value="<?php echo isset($faculty['school_id']) ? $faculty['school_id'] : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="firstname" class="control-label">First Name</label>
                            <input type="text" name="firstname" class="form-control form-control-sm" required value="<?php echo isset($faculty['firstname']) ? $faculty['firstname'] : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="lastname" class="control-label">Last Name</label>
                            <input type="text" name="lastname" class="form-control form-control-sm" required value="<?php echo isset($faculty['lastname']) ? $faculty['lastname'] : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="img" class="control-label">Avatar</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="img" onchange="displayImg(this)">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-center align-items-center">
                            <img src="<?php echo isset($faculty['avatar']) ? 'uploads/' . $faculty['avatar'] : ''; ?>" alt="Avatar" id="cimg" class="img-fluid img-thumbnail">
                        </div>
                        <div class="form-group">
                            <label for="email" class="control-label">Email</label>
                            <input type="email" class="form-control form-control-sm" name="email" required value="<?php echo isset($faculty['email']) ? $faculty['email'] : ''; ?>">
                            <small id="msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="password" class="control-label">Password</label>
                            <input type="password" class="form-control form-control-sm" name="password" <?php echo isset($faculty) ? '' : 'required'; ?>>
                            <small><i><?php echo isset($faculty) ? 'Leave this blank if you do not want to change your password' : ''; ?></i></small>
                        </div>
                        <div class="form-group">
                            <label for="cpass" class="control-label">Confirm Password</label>
                            <input type="password" class="form-control form-control-sm" name="cpass" <?php echo isset($faculty) ? '' : 'required'; ?>>
                            <small id="pass_match" data-status=''></small>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="col-lg-12 text-right justify-content-center d-flex">
                    <button type="submit" class="btn btn-secondary btn-secondary-blue mr-3">
                        <?php echo isset($faculty) ? 'Update' : 'Submit'; ?>
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href = '/faculty';">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
</nav>

<script>
    window.onload = function() {
        window.history.pushState({}, '', window.location.href);
        window.onpopstate = function() {
            window.history.pushState({}, '', window.location.href);
        };
    };
</script>
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
	$('#manage_faculty').submit(function(e){
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
			url:'ajax.php?action=save_faculty',
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
						location.replace('index.php?page=faculty_list')
					},750)
				}else if(resp == 2){
					$('#msg').html("<div class='alert alert-danger'>Email already exist.</div>");
					$('[name="email"]').addClass("border-danger")
					end_load()
				}else if(resp == 3){
					$('#msg').html("<div class='alert alert-danger'>School ID already exist.</div>");
					$('[name="school_id"]').addClass("border-danger")
					end_load()
				}
			}
		})
	})
</script>