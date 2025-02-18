<?php
include "handlers/faculty_handler.php";
?>

<div class="content">
    <nav class="main-header">
        <div class="col-lg-12 mt-3">
            <div class="col-12 mb-3">
                <h2 class="text-start"
                    style="font-size: 1.8rem; font-weight: bold; color: #4a4a4a; border-bottom: 2px solid #ccc; padding-bottom: 5px;">
                    Add New Faculty Member</h2>
            </div>
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="new_faculty">
                        <input type="hidden" name="faculty_id"
                            value="<?php echo isset($faculty['faculty_id']) ? $faculty['faculty_id'] : ''; ?>">
                        <div class="row">
                            <div class="col-md-6 border-right">
                                <div class="form-group">
                                    <label for="school_id" class="control-label">Employee ID</label>
                                    <input type="text" name="school_id" class="form-control form-control-sm" required
                                        value="<?php echo isset($faculty['school_id']) ? $faculty['school_id'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="firstname" class="control-label">First Name</label>
                                    <input type="text" name="firstname" class="form-control form-control-sm" required
                                        value="<?php echo isset($faculty['firstname']) ? $faculty['firstname'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="lastname" class="control-label">Last Name</label>
                                    <input type="text" name="lastname" class="form-control form-control-sm" required
                                        value="<?php echo isset($faculty['lastname']) ? $faculty['lastname'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="email" class="control-label">Email</label>
                                    <input type="email" class="form-control form-control-sm" name="email" required
                                        value="<?php echo isset($faculty['email']) ? $faculty['email'] : ''; ?>">
                                    <small id="msg" class="form-text text-muted">Please enter a valid email
                                        address.</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- <div class="form-group">
                                    <label for="img" class="control-label">Avatar</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFile" name="img"
                                            onchange="displayImg(this)">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                                <div class="form-group d-flex justify-content-center align-items-center">
                                    <img src="<?php echo isset($faculty['avatar']) ? 'uploads/' . $faculty['avatar'] : ''; ?>"
                                        alt="Avatar" id="cimg" class="img-fluid img-thumbnail">
                                </div> -->
                                <div class="form-group">
                                    <label for="department" class="control-label">Department</label>
                                    <select id="department" class="form-control form-control-sm" name="department"
                                        required>
                                        <option value="" disabled>Select Department</option>
                                        <?php
                                        // Get the faculty's assigned department (if editing)
                                        $faculty_department = isset($faculty['department']) ? $faculty['department'] : '';

                                        // Fetch all departments from the database
                                        $stmt = $conn->query("SELECT * FROM departments ORDER BY department_name ASC");
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            $formatted_name = ucwords(strtolower($row['department_name'])); // Capitalize first letter of each word
                                            $formatted_name = preg_replace('/\bOf\b/', 'of', $formatted_name); // Ensure "of" stays lowercase
                                        
                                            // Check if the department matches the faculty's department
                                            $selected = ($faculty_department == $row['department_code']) ? 'selected' : '';

                                            echo "<option value='{$row['department_code']}' {$selected}>{$formatted_name}</option>";
                                        }
                                        ?>
                                        <option value="add_new">Add New Department</option>
                                    </select>

                                    <!-- Input fields for new department (Hidden by default) -->
                                    <div id="new_department_container" style="display: none; margin-top: 5px;">
                                        <input type="text" id="new_department_code" class="form-control mt-2"
                                            placeholder="Enter Department Code">
                                        <input type="text" id="new_department_name" class="form-control mt-2"
                                            placeholder="Enter Department Name">
                                        <button type="button" id="add_department"
                                            class="btn btn-success btn-sm mt-2">Add</button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="subjects" class="control-label">Subjects</label>
                                    <select id="subjects" class="form-control subject-dropdown" name="subjects[]"
                                        multiple required>
                                        <option value="" disabled>Select Subjects</option>
                                        <?php foreach ($department_subjects as $subject): ?>
                                            <?php
                                            // Check if the subject is selected
                                            $is_selected = in_array(trim($subject['subject_code']), $selected_subjects) ? 'selected' : '';
                                            ?>
                                            <option value="<?= htmlspecialchars($subject['subject_code']) ?>"
                                                <?= $is_selected ?>>
                                                <?= htmlspecialchars($subject['subject_code'] . ' - ' . $subject['subject_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <div id="new_subject_container" style="display: none; margin-top: 10px;">
                                        <input type="text" id="new_subject_code" class="form-control mt-2"
                                            placeholder="Enter Subject Code">
                                        <input type="text" id="new_subject_name" class="form-control mt-2"
                                            placeholder="Enter Subject Name">
                                        <button type="button" id="add_subject"
                                            class="btn btn-success btn-sm mt-2">Add</button>
                                    </div>
                                </div>

                                <div class="error-message" id="password-error">Password must be at least 8 characters
                                    long,
                                    include an uppercase letter, a lowercase letter, and a special character.</div>

                                <div class="form-group">
                                    <label for="password" class="control-label">Password</label>
                                    <input type="password" class="form-control form-control-sm" name="password"
                                        id="password" minlength="8" <?php echo isset($faculty) ? '' : 'required'; ?>>
                                    <small><i><?php echo isset($faculty) ? 'Leave this blank if you do not want to change your password' : 'Choose a strong password.'; ?></i></small>
                                </div>
                                <div class="form-group">
                                    <label for="cpass" class="control-label">Confirm Password</label>
                                    <input type="password" class="form-control form-control-sm" name="cpass" id="cpass"
                                        minlength="8" <?php echo isset($faculty) ? '' : 'required'; ?>>
                                    <small id="pass_match" data-status=''></small>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-lg-12 text-right justify-content-center d-flex">
                            <button type="submit" class="btn btn-success btn-secondary-blue mr-3">
                                <?php echo isset($faculty) ? 'Update' : 'Submit'; ?>
                            </button>
                            <button type="button" class="btn btn-secondary"
                                onclick="window.location.href = './tertiary_faculty_list.php';">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</div>

<script>
    window.onload = function () {
        window.history.pushState({}, '', window.location.href);
        window.onpopstate = function () {
            window.history.pushState({}, '', window.location.href);
        };
    };
</script>
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

    .custom-selection {
        color: black !important;
    }

    .error-message {
        color: red;
        font-size: 12px;
        margin-bottom: 5px;
        display: none;
    }

    .select2-selection__choice {
        color: black !important;
        font-weight: bold;
        background-color: #f8f9fa;
        /* Light gray for visibility */
        border: 1px solid #ced4da;
    }

    /* Style dropdown options */
    .select2-results__option {
        text-transform: uppercase;
        /* Ensure dropdown items are uppercase */
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
    $('#manage_faculty').submit(function (e) {
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
            url: 'ajax.php?action=save_faculty',
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
                        location.replace('index.php?page=faculty_list')
                    }, 750)
                } else if (resp == 2) {
                    $('#msg').html("<div class='alert alert-danger'>Email already exist.</div>");
                    $('[name="email"]').addClass("border-danger")
                    end_load()
                } else if (resp == 3) {
                    $('#msg').html("<div class='alert alert-danger'>School ID already exist.</div>");
                    $('[name="school_id"]').addClass("border-danger")
                    end_load()
                }
            }
        })
    })
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('subjects-container');

        // Add subject field
        container.addEventListener('click', function (e) {
            if (e.target.closest('.add-subject')) {
                const newField = document.createElement('div');
                newField.classList.add('input-group', 'mb-3', 'subject-item');
                newField.innerHTML = `
                    <input type="text" class="form-control" name="subjects[]" required placeholder="Subject">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-danger remove-subject">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                `;
                container.appendChild(newField);
            }
        });

        // Remove subject field
        container.addEventListener('click', function (e) {
            if (e.target.closest('.remove-subject')) {
                const fieldToRemove = e.target.closest('.subject-item');
                if (fieldToRemove) {
                    container.removeChild(fieldToRemove);
                }
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
        // Initialize Select2
        $("#subjects").select2({
            placeholder: "Select Subjects",
            allowClear: true,
            width: "100%",
            templateSelection: function (data) {
                // Show only the subject code when selected
                return data.id ? data.id.toUpperCase() : data.text;
            }
        });

        const departmentSelect = $("#department");
        const subjectDropdown = $("#subjects");
        const newSubjectContainer = $("#new_subject_container");
        const newSubjectCode = $("#new_subject_code");
        const newSubjectName = $("#new_subject_name");
        const addSubjectBtn = $("#add_subject");

        // Fetch subjects when department changes
        departmentSelect.change(function () {
            let departmentCode = $(this).val();

            subjectDropdown.empty().append('<option value="" disabled>Select Subjects</option>'); // Clear previous options

            if (!departmentCode) return;

            $.post("get_subjects.php", { department_code: departmentCode }, function (data) {
                data.forEach(subject => {
                    let subjectCode = subject.subject_code.toUpperCase();
                    let subjectName = subject.subject_name;
                    let displayText = `${subjectCode} - ${subjectName}`;
                    subjectDropdown.append(new Option(displayText, subjectCode, false, false));
                });
                subjectDropdown.append(new Option("ADD NEW SUBJECT", "add_new", false, false));
            }, "json");
        });

        // Handle subject selection
        subjectDropdown.on("select2:select", function (e) {
            if (e.params.data.id === "add_new") {
                newSubjectContainer.show();
                subjectDropdown.val([]).trigger("change"); // Remove "Add New Subject" from selection
            } else {
                newSubjectContainer.hide();
            }
        });

        // Add new subject
        addSubjectBtn.click(function () {
            let departmentCode = departmentSelect.val();
            let subjectCode = newSubjectCode.val().trim().toUpperCase();
            let subjectName = newSubjectName.val().trim();

            if (!departmentCode) {
                Swal.fire({
                    icon: "warning",
                    title: "Missing Department",
                    text: "Please select a department first.",
                    timer: 1000,  // Auto-close after 1 second
                    showConfirmButton: false,
                    timerProgressBar: true, // Smooth fade effect
                    didOpen: (toast) => {
                        toast.style.transition = "opacity 0.5s ease"; // Smooth fade-out
                    }
                });
                return;
            }

            if (!subjectCode || !subjectName) {
                Swal.fire({
                    icon: "warning",
                    title: "Incomplete Fields",
                    text: "Please enter both Subject Code and Subject Name.",
                    timer: 1000,
                    showConfirmButton: false,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.style.transition = "opacity 0.5s ease";
                    }
                });
                return;
            }

            $.post("add_subject.php", { subject_code: subjectCode, subject_name: subjectName, department_code: departmentCode }, function (data) {
                if (data.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Success!",
                        text: "Subject added successfully!",
                        timer: 1000,
                        showConfirmButton: false,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.style.transition = "opacity 0.5s ease";
                        }
                    });

                    setTimeout(() => {
                        newSubjectContainer.hide();

                        // Add the new subject to the dropdown
                        let displayText = `${subjectCode} - ${subjectName}`;
                        let newOption = new Option(displayText, subjectCode, true, true);
                        subjectDropdown.append(newOption).trigger("change");

                        // Clear input fields
                        newSubjectCode.val("");
                        newSubjectName.val("");
                    }, 1000);  // Ensures the success message is displayed before updating UI
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: data.status === "exists" ? "Subject already exists!" : "Error adding subject.",
                        timer: 1000,
                        showConfirmButton: false,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.style.transition = "opacity 0.5s ease";
                        }
                    });
                }
            }, "json");
        });
    });
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

<script>
    document.getElementById('department').addEventListener('change', function () {
        let newDepartmentContainer = document.getElementById('new_department_container');

        if (this.value === 'add_new') {
            newDepartmentContainer.style.display = 'block';
            document.getElementById('new_department_code').focus();
        } else {
            newDepartmentContainer.style.display = 'none';
        }
    });

    document.getElementById('add_department').addEventListener('click', function () {
        let departmentCodeInput = document.getElementById('new_department_code');
        let departmentNameInput = document.getElementById('new_department_name');
        let departmentDropdown = document.getElementById('department');

        let departmentCode = departmentCodeInput.value.trim().toLowerCase(); // Convert to lowercase
        let departmentName = departmentNameInput.value.trim().toLowerCase().replace(/\b\w/g, c => c.toUpperCase()); // Capitalize first letter of each word
        departmentName = departmentName.replace(/\bOf\b/g, "of"); // Ensure "of" stays lowercase

        if (departmentCode === '' || departmentName === '') {
            alert('Please enter both Department Code and Department Name.');
            return;
        }

        // AJAX request to save the department
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "add_department.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    let newOption = document.createElement('option');
                    newOption.value = response.department_code;
                    newOption.text = response.department_name;

                    let addNewOption = departmentDropdown.querySelector("option[value='add_new']");
                    departmentDropdown.insertBefore(newOption, addNewOption);

                    departmentDropdown.value = response.department_code;
                    departmentCodeInput.value = '';
                    departmentNameInput.value = '';
                    document.getElementById('new_department_container').style.display = 'none';
                } else {
                    alert(response.message);
                }
            }
        };
        xhr.send("department_code=" + encodeURIComponent(departmentCode) + "&department_name=" + encodeURIComponent(departmentName));
    });
</script>