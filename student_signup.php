<?php
include "student_signup_handler.php";
include "submit_registration.php";
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="login-box">
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
                                        <i class="fas fa-id-badge"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="firstname" required
                                    placeholder="Firstname">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fas fa-id-badge"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="lastname" required
                                    placeholder="Lastname">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fas fa-id-badge"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" name="email" required
                                    placeholder="Email">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="subject" required
                                    placeholder="Subject">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fas fa-book"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="section" required
                                    placeholder="Section">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-4">
                                <input type="password" class="form-control" name="password" required
                                    placeholder="Password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fas fa-lock"></i>
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