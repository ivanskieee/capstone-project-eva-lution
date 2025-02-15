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
                                <input type="text" class="form-control" name="school_id" required
                                    placeholder="Student ID">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fas fa-id-card"></i>
                                    </div>
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
                                    <option value="ba_comm">BA Communication</option>
                                    <option value="abel">BA English Language</option>
                                    <option value="ab_polsci">BA Political Science</option>
                                    <option value="bs_math">BS Mathematics</option>
                                    <option value="bsba_hrm">BSBA - Human Resource Management</option>
                                    <option value="bsba_mm">BSBA - Marketing Management</option>
                                    <option value="bsba_ft">BSBA - Financial Technology</option>
                                    <option value="bs_entrep">BS Entrepreneurship</option>
                                    <option value="bs_pubad">BS Public Administration</option>
                                    <option value="bsrem">BS Real Estate Management</option>
                                    <option value="beed">BEEd - Generalist</option>
                                    <option value="bsed_eng">BSEd - English</option>
                                    <option value="bsed_filipino">BSEd - Filipino</option>
                                    <option value="bsed_science">BSEd - Science</option>
                                    <option value="bsed_math">BSEd - Mathematics</option>
                                    <option value="bsed_ss">BSEd - Social Studies</option>
                                    <option value="bsed_ve">BSEd - Values Education</option>
                                    <option value="btle_he">BTLE - Home Economics</option>
                                    <option value="btle_ict">BTLE - Information & Communication Technology</option>
                                    <option value="bped">BPED - Physical Education</option>
                                    <option value="bsned">BSNED - Special Needs Education</option>
                                    <option value="bs_psych">BS Psychology</option>
                                    <option value="bsa">BS Accountancy</option>
                                    <option value="beced">BECEd - Early Childhood Education</option>
                                    <option value="bsn">BS Nursing</option>
                                    <option value="bscs">BS Computer Science</option>
                                    <option value="bsit">BS Information Technology</option>
                                    <option value="bspt">BS Physical Therapy</option>
                                    <option value="bsrt">BS Radiologic Technology</option>
                                    <option value="bshm">BS Hospitality Management</option>
                                    <option value="aradtech">Associate in Radiologic Technology</option>
                                    <option value="act">Associate in Computer Technology</option>
                                    <option value="ctp_mapeh">Certificate in Teaching Program - MAPEH</option>
                                    <option value="ctp_pe">Certificate in Teaching Program - PE</option>
                                </select>
                            </div>

                            <div class="input-group mb-4">
                                <select class="form-control subject-dropdown" name="subjects[]" multiple required>
                                    <option value="" disabled>Select Subjects</option>
                                </select>
                            </div>

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
    const subjectsByCourse = {
        bs_math: [
            { value: "math101", text: "Mathematical Analysis" },
            { value: "math102", text: "Statistics and Probability" }
        ],
        bscs: [
            { value: "GEC1", text: "Purposive Communication" },
            { value: "GEC2", text: "Understanding the Self" },
            { value: "GEC3", text: "Reading in Philippine History" },
            { value: "GEC4", text: "Mathematics in the Modern World" },
            { value: "CC101", text: "Introduction to Computing" },
            { value: "CC102", text: "Fundamentals of Programming" },
            { value: "PATH-FIT", text: "Movement Competency Training" },
            { value: "NSTP", text: "ROTC11/CWTS11" },
            { value: "GEC5", text: "Art Appreciation" },
            { value: "GEC6", text: "The Contemporary World" },
            { value: "GEC7", text: "Science, Technology & Society" },
            { value: "GEC8", text: "Ethics" },
            { value: "CC103", text: "Intermediate Programming" },
            { value: "HCI101", text: "Introduction to Human Computer" },
            { value: "DS101", text: "Discrete Structures 1" },
            { value: "PATH-FIT2", text: "Exercise-Based Fitness Activities" },
            { value: "NSTP2", text: "ROTC12/CWTS12" },
            { value: "CC104", text: "Data Structure and Algorithms" },
            { value: "AC101", text: "Advance Calculus" },
            { value: "OOP101", text: "Object-Oriented Programming" },
            { value: "CC105", text: "Information Management" },
            { value: "CS ELEC1", text: "CS Elective 1" },
            { value: "FILI1", text: "Kontekstwalidong Komunikasyon sa Filipino" },
            { value: "LITT1", text: "Sosyedad at Literatura/Panitikang Panlipunan" },
            { value: "PATH-FIT3", text: "Popular Dance and Other Dance Forms" },
            { value: "RIZAL", text: "Life and Works of Rizal" },
            { value: "FILI2", text: "Filipino sa Iba't-Ibang Disiplina" },
            { value: "PL101", text: "Programming Languages" },
            { value: "AL101", text: "Algorithms and Complexity" },
            { value: "CC106", text: "Applications Development and Emerging Technologies" },
            { value: "CS PROF EL1", text: "CS Professional Elective 1" },
            { value: "DS102", text: "Discrete Structure 2" },
            { value: "PATH-FIT4", text: "Team Sport (Volleyball)" },
            { value: "AL102", text: "Automata Theory and Formal Languages" },
            { value: "AR101", text: "Architecture and Organization" },
            { value: "SP101", text: "Social Issues and Professional Practices" },
            { value: "CS ELEC2", text: "CS Elective 2" },
            { value: "OS101", text: "Operating System" },
            { value: "NC101", text: "Network and Communications" },
            { value: "SE101", text: "Software Engineering" },
            { value: "CS ELEC3", text: "CS Elective 3" },
            { value: "CS PROF EL2", text: "CS Professional Elective 2" },
            { value: "TH101", text: "Thesis Writing 1" },
            { value: "CS PROF EL3", text: "CS Professional Elective 3" },
            { value: "THS102", text: "Thesis Writing 2" },
            { value: "CS PROF EL4", text: "CS Professional Elective 4" },
            { value: "CS PROF EL5", text: "CS Professional Elective 5" },
            { value: "PRAC101", text: "Practicum (250 Hours)" },
            { value: "IAS101", text: "Information Assurance & Security" },
            { value: "CS PROF EL6", text: "CS Professional Elective 6" }
        ],
        bsit: [
            { value: "GEC1", text: "Purposive Communication" },
            { value: "GEC2", text: "Understanding The Self" },
            { value: "GEC3", text: "Reading in Philippine History" },
            { value: "GEC4", text: "Mathematics in the Modern World" },
            { value: "CC101", text: "Introduction to Computing" },
            { value: "CC102", text: "Fundamentals of Programming" },
            { value: "PATH-FIT", text: "Movement Competency Training" },
            { value: "NSTP", text: "ROTC11/CWTS11" },

            // Second Semester
            { value: "GEC5", text: "Art Appreciation" },
            { value: "GEC6", text: "The Contemporary World" },
            { value: "GEC7", text: "Science, Technology & Society" },
            { value: "GEC8", text: "Ethics" },
            { value: "CC103", text: "Intermediate Programming" },
            { value: "HCI101", text: "Introduction to Human Computer" },
            { value: "DS101", text: "Discrete Structures 1" },
            { value: "PATH-FIT2", text: "Exercise-Based Fitness Activities" },
            { value: "NSTP2", text: "ROTC12/CWTS12" },

            // Second Year - First Semester
            { value: "CC104", text: "Data Structure and Algorithms" },
            { value: "IT ELEC1", text: "IT Elective 1" },
            { value: "IT ELEC2", text: "IT Elective 2" },
            { value: "CC105", text: "Information Management" },
            { value: "CS ELEC1", text: "CS Elective 1" },
            { value: "FILI1", text: "Kontekstuwalisadong Komunikasyon sa Filipino" },
            { value: "LITT1", text: "Sosyedad at Literatura/Panitikang Panlipunan" },
            { value: "PATH-FIT3", text: "Popular Dance and Other Dance Forms" },

            // Second Year - Second Semester
            { value: "RIZAL", text: "Life and Works of Rizal" },
            { value: "FILI2", text: "Filipino sa Ibat-Ibang Disiplina" },
            { value: "MS101", text: "Quantitative Methods (Including Modeling and Simulation)" },
            { value: "NET101", text: "Networking 1" },
            { value: "IT PROF EL1", text: "IT Professional Elective 1" },
            { value: "IPT101", text: "Integrative Prog. & Technologies" },
            { value: "PATH-FIT4", text: "Team Sport (Volleyball)" },

            // Third Year - First Semester
            { value: "IM101", text: "Advance Database System" },
            { value: "NE102", text: "Networking 2" },
            { value: "SIA101", text: "Systems Integration and Architecture 1" },
            { value: "SP101", text: "Social and Professional Practices" },
            { value: "IT PROF EL2", text: "IT Professional Elective 2" },

            // Third Year - Second Semester
            { value: "IAS101", text: "Information Assurance & Security 1" },
            { value: "CC106", text: "Application Development and Emerging Technologies" },
            { value: "IT PROF EL3", text: "IT Professional Elective 3" },
            { value: "SAM101", text: "System Administration and Maintenance" },
            { value: "IT ELEC3", text: "IT Elective 3" },

            // Summer Class
            { value: "IAS102", text: "Information Assurance and Security 2" },
            { value: "CAP101", text: "Capstone Project and Research 1" },
            { value: "IT PROF EL4", text: "IT Professional Elective 4" },

            // Fourth Year - First Semester
            { value: "ES104", text: "Elective 4" },
            { value: "CAP102", text: "Capstone Project and Research 2" },
            { value: "IT PROF EL5", text: "IT Professional Elective 5" },
            { value: "IT PROF EL6", text: "IT Professional Elective 6" },

            // Fourth Year - Second Semester
            { value: "PRAC101", text: "Practicum (486 Hours)" }
        ],
        bsba_hrm: [
            { value: "hrm101", text: "Human Resource Fundamentals" }
        ],
        bsba_mm: [
            { value: "mm101", text: "Marketing Principles" }
        ],
        bsba_ft: [
            { value: "ft101", text: "Financial Tech Innovations" }
        ],
        beed: [
            { value: "ed102", text: "asdasdas" }
        ],
        act: [
            { value: "GEC1", text: "Purposive Communication" },
            { value: "GEC2", text: "Understanding the Self" },
            { value: "GEC3", text: "Reading in Philippine History" },
            { value: "GEC4", text: "Mathematics in the Modern World" },
            { value: "CC101", text: "Introduction to Computing" },
            { value: "CC102", text: "Fundamentals of Programming" },
            { value: "SOCSI ELECT1", text: "General Psychology" },
            { value: "PATH-FIT", text: "Movement Competency Training" },
            { value: "NSTP", text: "ROTC11/CWTS11" },
            { value: "GEC5", text: "Art Appreciation" },
            { value: "GEC6", text: "The Contemporary World" },
            { value: "GEC7", text: "Science, Technology & Society" },
            { value: "GEC8", text: "Ethics" },
            { value: "CC103", text: "Intermediate Programming" },
            { value: "HCI101", text: "Introduction to Human-Computer" },
            { value: "PATH-FIT2", text: "Exercise-Based Fitness Activities" },
            { value: "NSTP 2", text: "ROTC12/CWTS12" },
            { value: "CC104", text: "Data Structure and Algorithms" },
            { value: "ES101", text: "Elective 1" },
            { value: "ES102", text: "Elective 2" },
            { value: "CC105", text: "Information Management" },
            { value: "LITT1", text: "Sosyedad at Literatura/Panitikang Panlipunan" },
            { value: "PATH-FIT3", text: "Popular Dance and Other Dance Forms" },
            { value: "RIZAL", text: "Life and Works of Rizal" },
            { value: "MS101", text: "Quantitative Methods (Including Modeling and Simulation)" },
            { value: "NET101", text: "Networking 1" },
            { value: "FE101", text: "Free Elective 1" },
            { value: "IPT101", text: "Integrative Programming & Technologies" },
            { value: "PATH-FIT4", text: "Team Sport (Volleyball)" }
        ]
    };

    const courseSelect = document.getElementById("course");
    const subjectDropdown = document.querySelector(".subject-dropdown");

    function updateSubjects() {
        const selectedCourse = courseSelect.value;
        subjectDropdown.innerHTML = '<option value="" disabled>Select Subjects</option>';

        if (subjectsByCourse[selectedCourse]) {
            subjectsByCourse[selectedCourse].forEach(subject => {
                let option = document.createElement("option");
                option.value = subject.value;
                option.textContent = `${subject.value} - ${subject.text}`;
                subjectDropdown.appendChild(option);
            });
        }
    }

    courseSelect.addEventListener("change", updateSubjects);
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
    $(document).ready(function () {
        $('.subject-dropdown').select2({
            placeholder: "Select Subjects",
            width: "100%" // Ensures full width
        });
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
</style>