<?php
$page_title = "Goal Quest || Sign Up";
include('includes_start/header.php');
include('includes_start/navbar.php'); 
include('db_con.php');
?>

<!-- Include Bootstrap CSS and JS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-6">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h5>Registration Form</h5>
                    </div>
                    <div class="card-body common-form">
                        <form action="process-signup.php" method="POST" id="signup">
                            <div class="form-group mb-3">
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" required autocomplete="off">
                            </div>

                            <div class="form-group mb-3">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email address" required autocomplete="off">
                            </div>

                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                                <button type="button" id="btnToggle" class="toggle">
                                    <i id="eyeIcon" class="fa fa-eye-slash"></i>
                                </button>
                            </div>

                            <div class="form-group mb-3">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm your password" required>
                                <button type="button" id="btnToggleConfirm" class="toggle">
                                    <i id="eyeIconConfirm" class="fa fa-eye-slash"></i>
                                </button>
                            </div>

                            <div class="form-group mb-4">
                                <a href="#" data-toggle="modal" data-target="#termsModal">Terms of Agreement</a>
                            </div>

                            <div class="form-group mb-4">
                                <input type="hidden" name="agree" id="hiddenAgree" value="0">
                                <button type="submit" name="signup_btn" class="btn btn-block btn-primary" id="submitBtn">Submit</button>
                            </div>
                        </form>

                        <div class="switch text-center">
                            Already have an account? <a href="login.php" class="form-group-link">Login here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms of Agreement Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms of Agreement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Your Terms of Agreement content here -->
                <p>Welcome to GoalQuest. These Terms and Conditions govern your use 
                    of our task manager system. By accessing or using our Website, you agree 
                    to comply with and be bound by these Terms. If you do not agree with these Terms, please do not use 
                    our Website</p>
                <ol>
                    <b><li>Acceptance of Terms</li></b>
                    <p>By using our Service, you acknowledge that you have read, understood, and agree to be bound by 
                        these Terms, as well as our Privacy Policy.</p>
                    
                    <b><li>Changes to Terms</li></b>
                    <p>We reserve the right to modify these Terms at any time. Any changes will be effective immediately
                        upon posting on our Website. Your continued use of the Service following the posting of changes 
                        constitutes your acceptance of such changes.</p>
                    
                    <b><li>Use of the Service</li></b>
                    <ul>
                        <li><b>Account Registration:</b> You may need to register for an account to access certain features of 
                            the Service. You agree to provide accurate and complete information during registration and to keep your 
                            account information updated.
                        </li>
                        <li><b>Account Security:</b> You are responsible for maintaining the confidentiality of your account credentials 
                            and for all activities that occur under your account. You agree to notify us immediately of any unauthorized 
                            use of your account.
                        </li>
                        <li><b>Prohibited Conduct:</b> 
                            You agree not to:
                            <ul>
                                <li>Use the Service for any unlawful purpose.</li>
                                <li>Impersonate any person or entity or misrepresent your affiliation with any person or entity.</li>
                                <li>Interfere with or disrupt the operation of the Service.</li>
                                <li>Transmit any viruses or other harmful code.</li>
                            </ul>
                        </li>
                    </ul>

                    <b><li>Intellectual Property</li></b>
                    <ul>
                        <li><b>Our Rights: </b>All content and materials on the Website and Service, including but not limited to text, graphics, 
                            logos, and software, are the property of GoalQuest or its licensors and are protected by intellectual property laws.</li>
                        <li><b>Your Rights: </b>You are granted a limited, non-exclusive, non-transferable, and revocable license to access and 
                            use the Service for personal and internal business purposes.</li>
                    </ul>

                    <b><li>Third-Party Services</li></b>
                    <p>The Service may contain links to third-party websites or services that are not owned or controlled by us. We are not 
                        responsible for the content, privacy policies, or practices of any third-party websites or services.</p>
                    
                    <b><li>Usage Data</li></b>
                    <p>We may collect information about your use of the Service, including but not limited to the tasks you create, the time you spend on 
                        the Service, and the actions you take within the website. This data helps us improve the Service and provide you with a
                        better experience. By using the Service, you agree to our collection and use of Usage Data as described in our Privacy Policy.</p>
                    
                    <b><li>Termination</li></b>
                    <p>We may terminate or suspend your access to the Service at any time, without prior notice or liability, for any reason, 
                        including if you breach these Terms. Upon termination, your right to use the Service will immediately cease.</p>

                    <b><li>Severability</li></b>
                    <p>If any provision of these Terms is found to be invalid or unenforceable, the remaining provisions will continue in full force and effect.</p>
                    
                    <b><li>Compliance with Laws</li></b>
                    <p>You agree to comply with all applicable local, state, national, and international laws and regulations in connection with your use of the Service.</p>

                    <b><li>Contact Us</li></b>
                    <p>If you have any questions about these Terms, please contact us at info_goalquest@gmail.com</p>
                </ol>
                <br>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="agree" name="agree">
                    <label class="form-check-label" for="agree">I agree to the Terms of Agreement</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js"></script>
<script src="assets/dist/js/validation.js"></script>

<style>
    .toggle {
        background: none;
        border: none;
        color: grey;
        font-weight: 600;
        position: absolute;
        right: .50rem;
        top: 2.4rem;
        z-index: 9;
        cursor: pointer;
    }

    .fa {
        font-size: 1.5rem;
    }

    .form-group {
        position: relative;
    }

    #btnToggleConfirm {
        top: 2.4rem;
    }

    .btn-primary {
        background-color: #56AEFF;
        border-color: #56AEFF;
        color: black;
    }

    .btn-primary:hover {
        background-color: #469ad8;
        border-color: #469ad8;
        color: black;
    }
</style>

<script>
    //Display SweetAlert after successful form submission
    <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
        Swal.fire({
            title: "Success!",
            text: "You have successfully signed up. Go to login",
            icon: "success",
            confirmButtonText: "OK"
        });
    <?php endif; ?>

    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const togglePasswordButton = document.getElementById('btnToggle');
    const toggleConfirmButton = document.getElementById('btnToggleConfirm');
    const passwordIcon = document.getElementById('eyeIcon');
    const confirmIcon = document.getElementById('eyeIconConfirm');

    function togglePassword(input, icon) {
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            input.type = 'password';
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    }

    togglePasswordButton.addEventListener('click', () => {
        togglePassword(passwordInput, passwordIcon);
    });

    toggleConfirmButton.addEventListener('click', () => {
        togglePassword(confirmPasswordInput, confirmIcon);
    });

    //Ensure the user has agreed to the terms before submitting the form
    document.getElementById('submitBtn').addEventListener('click', function(event) {
        const agreeCheckbox = document.getElementById('agree');
        const hiddenAgree = document.getElementById('hiddenAgree');
        if (!agreeCheckbox.checked) {
            event.preventDefault();
            Swal.fire({
                title: "Error!",
                text: "You must agree to the terms of agreement before submitting.",
                icon: "error",
                confirmButtonText: "OK"
            });
        } else {
            hiddenAgree.value = "1";
        }
    });
</script>

<?php include('includes_start/footer.php'); ?>
