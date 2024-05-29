<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header('Location: login.php');
    exit;
}

$conn = require __DIR__ . "/db_con.php";
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

ob_start();
require __DIR__ . "/db_con.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Validation
    if (empty($_POST["current_password"])) {
        die("Current password is required");
    }
    if (empty($_POST["new_password"])) {
        die("New password is required");
    }
    if (strlen($_POST["new_password"]) < 8) {
        die("Password must be at least 8 characters");
    }
    if (!preg_match("/[a-z]/i", $_POST["new_password"])) {
        die("Password must contain at least one letter");
    }
    if (!preg_match("/[0-9]/", $_POST["new_password"])) {
        die("Password must contain at least one number");
    }
    if ($_POST["new_password"] !== $_POST["confirm_new_password"]) {
        die("New password and confirmation must match");
    }

    $user_id = $_SESSION["user_id"];
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];

    //Fetch the user's current password hash from the database
    $sql = "SELECT password_hash FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($current_password, $user['password_hash'])) {
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

        //Update the password in the database
        $sql = "UPDATE users SET password_hash = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_password_hash, $user_id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Password changed successfully!";
            header('Location: admin_change_password.php?success=true');
            exit;
        } else {
            $error = "An error occurred while updating the password.";
        }
    } else {
        $_SESSION['error'] = "Current password is incorrect.";
        header('Location: admin_change_password.php');
        exit;
    }
}

$page_title = "Change Password || Goal Quest";
include('includes/admin-header.php');
include('includes/admin-topbar.php');
include('includes/admin-sidebar.php');
?>

<!-- Include Bootstrap CSS and JS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Change Password</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="admin-dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="admin-profile.php">Profile</a></li>
                        <li class="breadcrumb-item active">Change Password</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger" id="error-alert">
                            <?php 
                            echo $_SESSION['error']; 
                            unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="post" id="change-password-form">
                        <div class="form-group mb-3">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Enter your current password" required>
                            <button type="button" id="btnToggleCurrent" class="toggle"><i id="eyeIconCurrent" class="fa fa-eye-slash"></i></button>
                        </div>

                        <div class="form-group mb-3">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter your new password" required>
                            <button type="button" id="btnToggleNew" class="toggle"><i id="eyeIconNew" class="fa fa-eye-slash"></i></button>
                        </div>

                        <div class="form-group mb-3">
                            <label for="confirm_new_password">Confirm New Password</label>
                            <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control" placeholder="Confirm your new password" required>
                            <button type="button" id="btnToggleConfirm" class="toggle"><i id="eyeIconConfirm" class="fa fa-eye-slash"></i></button>
                        </div>

                        <div class="modal-footer">
                            <a href="admin-profile.php" class="btn btn-secondary">Back</a>
                            <button type="submit" class="btn btn-success">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js"></script>

<script>
    function togglePasswordVisibility(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
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

    document.getElementById('btnToggleCurrent').addEventListener('click', () => {
        togglePasswordVisibility('current_password', 'eyeIconCurrent');
    });

    document.getElementById('btnToggleNew').addEventListener('click', () => {
        togglePasswordVisibility('new_password', 'eyeIconNew');
    });

    document.getElementById('btnToggleConfirm').addEventListener('click', () => {
        togglePasswordVisibility('confirm_new_password', 'eyeIconConfirm');
    });

    const validation = new JustValidate("#change-password-form");

    validation
        .addField("#current_password", [
            {
                rule: "required",
                errorMessage: "Current password is required"
            }
        ])
        .addField("#new_password", [
            {
                rule: "required",
                errorMessage: "New password is required"
            },
            {
                rule: "minLength",
                value: 8,
                errorMessage: "Password must be at least 8 characters"
            },
            {
                rule: "password",
                errorMessage: "Password must contain at least one letter and one number"
            }
        ])
        .addField("#confirm_new_password", [
            {
                rule: "required",
                errorMessage: "Confirming new password is required"
            },
            {
                validator: (value, fields) => {
                    return value === fields["#new_password"].elem.value;
                },
                errorMessage: "Passwords do not match"
            }
        ])
        .onSuccess((event) => {
            document.getElementById("change-password-form").submit();
        });

    //Error message disappears after 5 seconds
    $(document).ready(function() {
        setTimeout(function() {
            $("#error-alert").fadeOut('slow');
        }, 3000);
    });

    //Show SweetAlert if password was changed successfully
    <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
        $(document).ready(function() {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Password changed successfully!',
                timer: 3000,
                showConfirmButton: false
            }).then((result) => {
            window.location.href = 'admin-profile.php';
            });
        });
    <?php endif; ?>
</script>

<style>
    body, input, select, textarea, button, .card-header, .card-body, .card-footer, .breadcrumb-item, .btn, .dropdown-menu, .dropdown-item, .badge, h1, h2, h3, h4, h5, h6, p, a, li, table {
        font-family: 'Poppins', sans-serif !important;
    }

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

    #btnToggleCurrent, #btnToggleNew, #btnToggleConfirm {
        top: 2.4rem;
    }
</style>

<?php include('includes/admin-footer.php'); ?>
