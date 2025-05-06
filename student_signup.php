<?php
include "student_signup_handler.php";
include "submit_registration.php";
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="login-box mb-5">
                <div class="card">
                    <div class="card-body login-card-body">
                        <div class="login-logo mb-4">
                            <h4 class="text-center">Student Registration</h4>
                        </div>
                        <form action="submit_registration.php" method="POST" id="registration-form">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="school_id" id="school_id" required
                                    placeholder="Student ID">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                </div>
                                <!-- Custom feedback message -->
                                <div class="invalid-feedback" id="school_id_feedback">
                                    Student ID must contain only numbers (no special characters).
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="firstname" required
                                    placeholder="Firstname">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="lastname" required placeholder="Lastname">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fas fa-user-tag"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" name="email" required placeholder="Email">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <select id="course" class="form-control" name="course" required>
                                    <option value="" disabled selected>Select Course</option>
                                    <?php
                                    $stmt = $conn->query("SELECT * FROM courses ORDER BY course_name ASC");
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $formatted_name = strtoupper($row['course_name']);
                                        echo "<option value='{$row['course_code']}'>{$formatted_name}</option>";
                                    }
                                    ?>
                                </select>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- New Course Input (Hidden Initially) -->
                            <!-- <div id="new_course_container" style="display: none; margin-top: 5px;">
                                <input type="text" id="new_course_code" class="form-control mt-2"
                                    placeholder="Enter Course Code">
                                <input type="text" id="new_course_name" class="form-control mt-2"
                                    placeholder="Enter Course Name">
                                <button type="button" id="add_course"
                                    class="btn btn-success btn-sm mt-2 mb-3">Add</button>
                            </div> -->

                            <div class="input-group mb-3">
                                <select id="subjects" class="form-control" name="subjects[]" multiple required>
                                    <option value="" disabled>Select Subjects</option>
                                </select>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fas fa-book"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- New Subject Input (Hidden Initially) -->
                            <!-- <div id="new_subject_container" style="display: none; margin-top: 5px;">
                                <input type="text" id="new_subject_code" class="form-control mt-2"
                                    placeholder="Enter Subject Code">
                                <input type="text" id="new_subject_name" class="form-control mt-2"
                                    placeholder="Enter Subject Name">
                                <button type="button" id="add_subject"
                                    class="btn btn-success btn-sm mt-2 mb-3">Add</button>
                            </div> -->

                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="section" required placeholder="Section">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="error-message" id="password-error">Password must be at least 8 characters long,
                                include an uppercase letter, a lowercase letter, and a special character.</div>

                            <div class="input-group mb-4">
                                <input type="password" class="form-control" name="password" id="password" required
                                    placeholder="Password" minlength="8">
                                <div class="input-group-append">
                                    <div class="input-group-text" id="toggle-password">
                                        <i class="fas fa-lock" id="lock-icon"></i>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-block">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('registration-form');
        const schoolIdInput = document.getElementById('school_id');
        const schoolIdFeedback = document.getElementById('school_id_feedback');
        const invalidChars = /[^a-zA-Z0-9]/;

        // Realtime validation while typing
        schoolIdInput.addEventListener('input', function () {
            if (invalidChars.test(this.value)) {
                this.classList.add('is-invalid');
                schoolIdFeedback.style.display = 'block';
            } else {
                this.classList.remove('is-invalid');
                schoolIdFeedback.style.display = 'none';
            }
        });

        // On form submission
        form.addEventListener('submit', function (e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }

            if (invalidChars.test(schoolIdInput.value)) {
                e.preventDefault();
                schoolIdInput.classList.add('is-invalid');
                schoolIdFeedback.style.display = 'block';
            }

            form.classList.add('was-validated');
        });
    });
</script>

<script>
    $(document).ready(function () {
        $("#subjects").select2({
            placeholder: "Select Subjects",
            allowClear: true,
            width: "100%",
            templateSelection: function (data) {
                return data.id ? data.id.toUpperCase() : data.text;
            }
        });

        const courseSelect = $("#course");
        const subjectDropdown = $("#subjects");
        const newSubjectContainer = $("#new_subject_container");
        const newSubjectCode = $("#new_subject_code");
        const newSubjectName = $("#new_subject_name");
        const addSubjectBtn = $("#add_subject");

        courseSelect.change(function () {
            let courseCode = $(this).val();
            subjectDropdown.empty().append('<option value="" disabled>Select Subjects</option>');

            if (!courseCode) return;

            $.post("get_course_subjects.php", { course_code: courseCode }, function (data) {
                data.forEach(subject => {
                    let subjectCode = subject.subject_code.toUpperCase();
                    let subjectName = subject.subject_name;
                    let displayText = `${subjectCode} - ${subjectName}`;
                    subjectDropdown.append(new Option(displayText, subjectCode, false, false));
                });
                // subjectDropdown.append(new Option("ADD NEW SUBJECT", "add_new", false, false));
            }, "json");
        });

        // subjectDropdown.on("select2:select", function (e) {
        //     if (e.params.data.id === "add_new") {
        //         newSubjectContainer.show();
        //         subjectDropdown.val([]).trigger("change");
        //     } else {
        //         newSubjectContainer.hide();
        //     }
        // });

        addSubjectBtn.click(function () {
            let courseCode = courseSelect.val();
            let subjectCode = newSubjectCode.val().trim().toUpperCase();
            let subjectName = newSubjectName.val().trim();

            if (!courseCode) return alert("Please select a course first.");
            if (!subjectCode || !subjectName) return alert("Please enter both Subject Code and Subject Name.");

            $.post("add_course_subject.php", { subject_code: subjectCode, subject_name: subjectName, course_code: courseCode }, function (data) {
                if (data.status === "success") {
                    alert("Subject added successfully!");
                    newSubjectContainer.hide();

                    let displayText = `${subjectCode} - ${subjectName}`;
                    let newOption = new Option(displayText, subjectCode, true, true);
                    subjectDropdown.append(newOption).trigger("change");

                    newSubjectCode.val("");
                    newSubjectName.val("");
                } else {
                    alert(data.status === "exists" ? "Subject already exists!" : "Error adding subject.");
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
    document.getElementById('course').addEventListener('change', function () {
        let newCourseContainer = document.getElementById('new_course_container');

        if (this.value === 'add_new') {
            newCourseContainer.style.display = 'block';
            document.getElementById('new_course_code').focus();
        } else {
            newCourseContainer.style.display = 'none';
        }
    });

    document.getElementById('add_course').addEventListener('click', function () {
        let courseCodeInput = document.getElementById('new_course_code');
        let courseNameInput = document.getElementById('new_course_name');
        let courseDropdown = document.getElementById('course');

        let courseCode = courseCodeInput.value.trim().toLowerCase();
        let courseName = courseNameInput.value.trim().toLowerCase().replace(/\b\w/g, c => c.toUpperCase());
        courseName = courseName.replace(/\bOf\b/g, "of"); // Ensure "of" stays lowercase

        if (courseCode === '' || courseName === '') {
            alert('Please enter both Course Code and Course Name.');
            return;
        }

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "add_course.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    let newOption = document.createElement('option');
                    newOption.value = response.course_code;
                    newOption.text = response.course_name;

                    let addNewOption = courseDropdown.querySelector("option[value='add_new']");
                    courseDropdown.insertBefore(newOption, addNewOption);

                    courseDropdown.value = response.course_code;
                    courseCodeInput.value = '';
                    courseNameInput.value = '';
                    document.getElementById('new_course_container').style.display = 'none';
                } else {
                    alert(response.message);
                }
            }
        };
        xhr.send("course_code=" + encodeURIComponent(courseCode) + "&course_name=" + encodeURIComponent(courseName));
    });
</script>

<style>
    .error-message {
        color: red;
        font-size: 12px;
        margin-bottom: 5px;
        display: none;
        /* Initially hidden */
    }

    @media (max-width: 768px) {
        .login-box {
            width: 90%;
            margin: 0 auto;
        }

        .container {
            padding-left: 15px;
            padding-right: 15px;
            padding-top: 30px;
        }
    }
</style>