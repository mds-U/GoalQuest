<?php
include('../db_con.php');

//Initialize variables with default values
$name = "";
$email = "";
$status = "";
$role = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $status = $_POST['status'];
    $role = $_POST['role'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    //Check if password matches confirm password
    if ($password !== $confirm_password) {
        echo '<script>alert("Password and confirm password do not match!");</script>';
        echo '<script>window.location.href="add-account.php";</script>';
        exit();
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $insertSql = "INSERT INTO users (name, email, status, role, password_hash) VALUES (?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("ssiss", $name, $email, $status, $role, $password_hash);

    if ($insertStmt->execute()) {
        echo '<script>alert("User added successfully!");</script>';
        echo '<script>window.location.href="../manage-accounts.php";</script>';
        exit();
    } else {
        echo '<script>alert("Error adding user: '.$insertStmt->error.'");</script>';
    }

    $insertStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Add User</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="row g-3">
            <div class="col-md-12">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" autocomplete="off" required>
            </div>

            <div class="col-12">
                <label class="form-label">Email:</label>
                <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>" autocomplete="off" required>
            </div>
            <div class="col-6">
                <label class="form-label">Password:</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                        <i class="fa fa-eye-slash"></i>
                    </button>
                </div>
            </div>
            <div class="col-6">
                <label class="form-label">Confirm Password:</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" autocomplete="off" required>
                    <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                        <i class="fa fa-eye-slash"></i>
                    </button>
                </div>
            </div>
            <div class="col-6">
                <label class="form-label">Status:</label>
                <select class="form-select" name="status">
                    <option value="1" <?php echo ($status == 1) ? 'selected' : ''; ?>>Active</option>
                    <option value="0" <?php echo ($status == 0) ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <div class="col-6">
                <label class="form-label">Role:</label>
                <select class="form-select" name="role">
                    <option value="user" <?php echo ($role == 'user') ? 'selected' : ''; ?>>user</option>
                    <option value="admin" <?php echo ($role == 'admin') ? 'selected' : ''; ?>>admin</option>
                </select>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-success" name="add" style="width: 100px;">Add</button>
                <a href="../manage-accounts.php" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>

<script>
    <?php if(isset($_POST['add'])) { ?>
        <?php if($insertStmt->execute()) { ?>
            swal("Success", "User added successfully!", "success")
                .then(() => {
                    window.location.href = "../manage-accounts.php";
                });
        <?php } else { ?>
            swal("Error", "Error adding user: <?php echo $insertStmt->error; ?>", "error")
        <?php } ?>
    <?php } ?>

    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
    });

    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPassword = document.getElementById('confirm_password');

    toggleConfirmPassword.addEventListener('click', function () {
        const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPassword.setAttribute('type', type);
    });
</script>

</body>
</html>
