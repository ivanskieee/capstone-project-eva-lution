<?php
include "handlers/faculty_handler.php";

?>

<div class="content">
    <nav class="main-header">
        <div class="col-lg-12 mt-3">
            <div class="col-12 mb-3">
                <h2 class="text-start"
                    style="font-size: 1.8rem; font-weight: bold; color: #4a4a4a; border-bottom: 2px solid #ccc; padding-bottom: 5px;">
                    Add New</h2>
            </div>
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="new_faculty">
                        <input type="hidden" name="faculty_id"
                            value="<?php echo isset($faculty['faculty_id']) ? $faculty['faculty_id'] : ''; ?>">
                        <div class="row">
                            <div class="col-md-6 border-right">
                                <div class="form-group">
                                    <label for="school_id" class="control-label">School ID</label>
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
                                    <small id="msg"></small>
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
                                        <option value="" disabled selected>Select Department</option>
                                        <option value="ccs">College of Computer Studies</option>
                                        <option value="educ">College of Education</option>
                                        <option value="cas">College of Arts & Sciences</option>
                                        <option value="cba">College of Business Administration</option>
                                        <option value="cas">College of Nursing</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="subjects" class="control-label">Subjects</label>
                                    <select class="form-control subject-dropdown" name="subjects[]" multiple required>
                                        <option value="" disabled>Select Subjects</option>
                                    </select>
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
    const subjectsByDepartment = {
        ccs: [
            { "value": "GEC1", "text": "Purposive Communication" },
            { "value": "GEC2", "text": "Understanding the Self" },
            { "value": "GEC3", "text": "Reading in Philippine History" },
            { "value": "GEC4", "text": "Mathematics in the Modern World" },
            { "value": "CC101", "text": "Introduction to Computing" },
            { "value": "CC102", "text": "Fundamentals of Programming" },
            { "value": "PATH-FIT", "text": "Movement Competency Training" },
            { "value": "NSTP", "text": "ROTC11/CWTS11" },
            { "value": "GEC5", "text": "Art Appreciation" },
            { "value": "GEC6", "text": "The Contemporary World" },
            { "value": "GEC7", "text": "Science, Technology & Society" },
            { "value": "GEC8", "text": "Ethics" },
            { "value": "CC103", "text": "Intermediate Programming" },
            { "value": "HCI101", "text": "Introduction to Human-Computer" },
            { "value": "DS101", "text": "Discrete Structures 1" },
            { "value": "PATH-FIT2", "text": "Exercise-Based Fitness Activities" },
            { "value": "NSTP2", "text": "ROTC12/CWTS12" },
            { "value": "CC104", "text": "Data Structure and Algorithms" },
            { "value": "AC101", "text": "Advance Calculus" },
            { "value": "OOP101", "text": "Object-Oriented Programming" },
            { "value": "CC105", "text": "Information Management" },
            { "value": "CS ELEC1", "text": "CS Elective 1" },
            { "value": "FILI1", "text": "Kontekstwalidong Komunikasyon sa Filipino" },
            { "value": "LITT1", "text": "Sosyedad at Literatura/Panitikang Panlipunan" },
            { "value": "PATH-FIT3", "text": "Popular Dance and Other Dance Forms" },
            { "value": "RIZAL", "text": "Life and Works of Rizal" },
            { "value": "FILI2", "text": "Filipino sa Iba't-Ibang Disiplina" },
            { "value": "PL101", "text": "Programming Languages" },
            { "value": "AL101", "text": "Algorithms and Complexity" },
            { "value": "CC106", "text": "Applications Development and Emerging Technologies" },
            { "value": "CS PROF EL1", "text": "CS Professional Elective 1" },
            { "value": "DS102", "text": "Discrete Structure 2" },
            { "value": "PATH-FIT4", "text": "Team Sport (Volleyball)" },
            { "value": "AL102", "text": "Automata Theory and Formal Languages" },
            { "value": "AR101", "text": "Architecture and Organization" },
            { "value": "SP101", "text": "Social Issues and Professional Practices" },
            { "value": "CS ELEC2", "text": "CS Elective 2" },
            { "value": "OS101", "text": "Operating System" },
            { "value": "NC101", "text": "Network and Communications" },
            { "value": "SE101", "text": "Software Engineering" },
            { "value": "CS ELEC3", "text": "CS Elective 3" },
            { "value": "CS PROF EL2", "text": "CS Professional Elective 2" },
            { "value": "TH101", "text": "Thesis Writing 1" },
            { "value": "CS PROF EL3", "text": "CS Professional Elective 3" },
            { "value": "THS102", "text": "Thesis Writing 2" },
            { "value": "CS PROF EL4", "text": "CS Professional Elective 4" },
            { "value": "CS PROF EL5", "text": "CS Professional Elective 5" },
            { "value": "PRAC101", "text": "Practicum (250-486 Hours)" },
            { "value": "IAS101", "text": "Information Assurance & Security 1" },
            { "value": "CS PROF EL6", "text": "CS Professional Elective 6" },
            { "value": "IT ELEC1", "text": "IT Elective 1" },
            { "value": "IT ELEC2", "text": "IT Elective 2" },
            { "value": "IT PROF EL1", "text": "IT Professional Elective 1" },
            { "value": "MS101", "text": "Quantitative Methods (Including Modeling and Simulation)" },
            { "value": "NET101", "text": "Networking 1" },
            { "value": "IPT101", "text": "Integrative Programming & Technologies" },
            { "value": "IM101", "text": "Advance Database System" },
            { "value": "NE102", "text": "Networking 2" },
            { "value": "SIA101", "text": "Systems Integration and Architecture 1" },
            { "value": "IT PROF EL2", "text": "IT Professional Elective 2" },
            { "value": "IAS102", "text": "Information Assurance and Security 2" },
            { "value": "CAP101", "text": "Capstone Project and Research 1" },
            { "value": "IT PROF EL3", "text": "IT Professional Elective 3" },
            { "value": "SAM101", "text": "System Administration and Maintenance" },
            { "value": "IT ELEC3", "text": "IT Elective 3" },
            { "value": "CAP102", "text": "Capstone Project and Research 2" },
            { "value": "IT PROF EL4", "text": "IT Professional Elective 4" },
            { "value": "ES104", "text": "Elective 4" },
            { "value": "IT PROF EL5", "text": "IT Professional Elective 5" },
            { "value": "IT PROF EL6", "text": "IT Professional Elective 6" },
            { "value": "SOCSI ELECT1", "text": "General Psychology" },
            { "value": "ES101", "text": "Elective 1" },
            { "value": "ES102", "text": "Elective 2" },
            { "value": "FE101", "text": "Free Elective 1" }
        ],
        educ: [
            { value: "ED101", text: "Foundations of Education" },
            { value: "ED102", text: "Educational Psychology" },
            { value: "ED103", text: "Curriculum Development" },
            { value: "ED104", text: "Teaching Strategies" }
        ],
        cas: [
            { value: "CAS101", text: "Introduction to Humanities" },
            { value: "CAS102", text: "Philippine Literature" },
            { value: "CAS103", text: "World History" },
            { value: "CAS104", text: "Ethics and Logic" }
        ],
        cba: [
            { value: "BA101", text: "Principles of Marketing" },
            { value: "BA102", text: "Financial Management" },
            { value: "BA103", text: "Business Law" },
            { value: "BA104", text: "Entrepreneurship" }
        ]
    };

    const departmentSelect = document.getElementById("department");
    const subjectDropdown = document.querySelector(".subject-dropdown");

    function updateSubjects() {
        const selectedDepartment = departmentSelect.value;
        subjectDropdown.innerHTML = '<option value="" disabled>Select Subjects</option>';

        if (subjectsByDepartment[selectedDepartment]) {
            subjectsByDepartment[selectedDepartment].forEach(subject => {
                let option = document.createElement("option");
                option.value = subject.value;
                option.textContent = `${subject.value} - ${subject.text}`;
                subjectDropdown.appendChild(option);
            });
        }
    }

    departmentSelect.addEventListener("change", updateSubjects);
</script>