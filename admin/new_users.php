<?php
include "handlers/user_handler.php";

?>

<div class="content">
	<nav class="main-header">
		<div class="col-lg-12 mt-3">
			<div class="col-12 mb-3">
				<h2 class="text-start"
					style="font-size: 1.8rem; font-weight: bold; color: #4a4a4a; border-bottom: 2px solid #ccc; padding-bottom: 5px;">
					Add New User</h2>
			</div>
			<div class="card">
				<div class="card-body">
					<form method="POST" id="new_users" enctype="multipart/form-data">
						<input type="hidden" id="id" name="id"
							value="<?php echo isset($admin['id']) ? $admin['id'] : ''; ?>">
						<div class="row">
							<div class="col-md-6 border-right">

								<div class="form-group">
									<label for="" class="control-label">First Name</label>
									<input type="text" id="firstname" name="firstname"
										class="form-control form-control-sm" required
										value="<?php echo isset($admin['firstname']) ? $admin['firstname'] : ''; ?>">
								</div>
								<div class="form-group">
									<label for="" class="control-label">Last Name</label>
									<input type="text" id="lastname" name="lastname"
										class="form-control form-control-sm" required
										value="<?php echo isset($admin['lastname']) ? $admin['lastname'] : ''; ?>">
								</div>
								<div class="form-group">
									<label class="control-label">Email</label>
									<input type="email" class="form-control form-control-sm" name="email" id="email"
										required value="<?php echo isset($admin['email']) ? $admin['email'] : ''; ?>">
									<small id="msg" class="form-text text-muted">Please enter a valid email
										address.</small>
								</div>

								<!-- <div class="form-group">
									<label for="" class="control-label">Avatar</label>
									<div class="custom-file">
										<input type="file" class="custom-file-input" id="customFile" name="img"
											onchange="displayImg(this, $(this))">
										<label class="custom-file-label" for="customFile">Choose file</label>
									</div>
								</div>
								<div class="form-group d-flex justify-content-center align-items-center">
									<img src="<?php echo isset($admin['avatar']) ? 'uploads/' . $admin['avatar'] : ''; ?>"
										alt="Avatar" id="cimg" class="img-fluid img-thumbnail">
								</div> -->
							</div>
							<div class="col-md-6">
								<div class="error-message" id="password-error">Password must be at least 8 characters
									long,
									include an uppercase letter, a lowercase letter, and a special character.</div>
								<div class="form-group">
									<label for="password" class="control-label">Password</label>
									<input type="password" class="form-control form-control-sm" name="password"
										id="password" <?php echo isset($admin) ? '' : 'required'; ?>>
									<small><i><?php echo isset($id) ? 'Leave this blank if you do not want to change your password' : 'Choose a strong password.'; ?></i></small>
								</div>
								<div class="form-group">
									<label class="label control-label">Confirm Password</label>
									<input type="password" class="form-control form-control-sm" name="cpass" <?php echo isset($admin) ? '' : 'required'; ?>>
									<small id="pass_match" data-status=""></small>
								</div>
							</div>
						</div>
						<hr>
						<div class="col-lg-12 text-right justify-content-center d-flex">
							<button type="submit"
								class="btn btn-success btn-secondary-blue mr-3"><?php echo isset($id) ? 'Update' : 'Submit'; ?></button>
							<button type="button" class="btn btn-secondary"
								onclick="window.location.href = './user_list.php';">Cancel</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</nav>
</div>

<style>
	img#cimg {
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}

	.list-group-item:hover {
		color: black !important;
		font-weight: 700 !important;
	}

	.content .main-header {
		max-height: 90vh;
		overflow-y: auto;
		scroll-behavior: smooth;
	}
	.error-message {
        color: red;
        font-size: 12px;
        margin-bottom: 5px;
        display: none;
    }
</style>
<script>
	$('[name="password"],[name="cpass"]').keyup(function () {
		var pass = $('[name="password"]').val()
		var cpass = $('[name="cpass"]').val()
		if (cpass == '' || pass == '') {
			$('#pass_match').attr('data-status', '')
		} else {
			if (cpass == pass) {
				$('#pass_match').attr('data-status', '1').html('<i class="text-success">Password Matched.</i>')
			} else {
				$('#pass_match').attr('data-status', '2').html('<i class="text-danger">Password does not match.</i>')
			}
		}
	})
	function displayImg(input, _this) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#cimg').attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]);
		}
	}
	$('#manage_student').submit(function (e) {
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')
		if ($('[name="password"]').val() != '' && $('[name="cpass"]').val() != '') {
			if ($('#pass_match').attr('data-status') != 1) {
				if ($("[name='password']").val() != '') {
					$('[name="password"],[name="cpass"]').addClass("border-danger")
					end_load()
					return false;
				}
			}
		}
		$.ajax({
			url: 'ajax.php?action=save_student',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function (resp) {
				if (resp == 1) {
					alert_toast('Data successfully saved.', "success");
					setTimeout(function () {
						location.replace('index.php?page=student_list')
					}, 750)
				} else if (resp == 2) {
					$('#msg').html("<div class='alert alert-danger'>Email already exist.</div>");
					$('[name="email"]').addClass("border-danger")
					end_load()
				}
			}
		})
	})
</script>
<script>
    document.getElementById('password').addEventListener('input', function () {
        const password = this.value;
        const errorMessage = document.getElementById('password-error');

        // Regular expression for validation
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/;

        if (password.length === 0) {
            errorMessage.style.display = 'none'; // Hide error message when input is empty
        } else if (!regex.test(password)) {
            errorMessage.style.display = 'block'; // Show error message if password is invalid
        } else {
            errorMessage.style.display = 'none'; // Hide error message if valid
        }
    });
</script>