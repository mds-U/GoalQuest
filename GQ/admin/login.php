<?php
session_start();

//To not go back to login page after a user/admin logged-in
if (isset($_SESSION["user_id"])) {
    if ($_SESSION["role"] == "admin") {
        header("Location: admin-dashboard.php");
    } else {
        header("Location: dashboard.php");
    }
    exit;
}

$page_title = "Login || Goal Quest";
include('includes_start/header.php');
include('includes_start/navbar.php');
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h5>Login Form</h5>
                    </div>
                    <div class="card-body common-form"> 
                        <form method="POST" id="loginForm">
                            <div class="form-group mb-3">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" class="form-control"
                                        placeholder="Enter your email address" required autocomplete="off">
                            </div>

                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" class="form-control"
                                        placeholder="Enter your password" required autocomplete="off">
                                <button type="button" id="btnToggle" class="toggle">
                                    <i id="eyeIcon" class="fa fa-eye-slash"></i>
                                </button>
                            </div><br>

                            <div class="form-group mb-4">
                                <button type="submit" class="btn btn-block btn-primary">Login</button>
                            </div>
                            
                        </form>

                        <div class="switch text-center">
                            New to Goal Quest? <a href="signup.php" class="form-group-link">Register Now!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('ajax_login.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.role === 'admin') {
                    window.location.href = 'admin-dashboard.php';
                } else {
                    window.location.href = 'dashboard.php';
                }
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    document.getElementById('email').value = '';
                    document.getElementById('password').value = '';
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred while processing your request.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    });

    const passwordInput = document.getElementById('password');
    const toggle = document.getElementById('btnToggle');
    const icon = document.getElementById('eyeIcon');

    function togglePassword() {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            passwordInput.type = 'password';
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    }

    toggle.addEventListener('click', togglePassword);
</script>

<style>
    .toggle {
        background: none;
        border: none;
        color: grey;
        font-weight: 600;
        position: absolute;
        right: 1.50rem;
        top: 11.52rem;
        z-index: 9;
    }

    .fa {
        font-size: 1.50rem;
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

<?php include('includes_start/footer.php'); ?>
