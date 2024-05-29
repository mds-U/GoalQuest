<?php
include('../db_con.php');

//Initialize variables with default values
$name = "";
$email = "";
$status = "";
$role = "";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $email = $row['email'];
        $status = $row['status'];
        $role = $row['role'];
    } else {
        echo "User not found";
        exit();
    }

    $stmt->close();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_GET['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $status = $_POST['status'];
    $role = $_POST['role'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    $updateSql = "UPDATE users SET name = ?, email = ?, status = ?, role = ?";
    $params = array($name, $email, $status, $role);
    $types = "ssis"; //Types for the prepared statement

    if ($password) {
        $updateSql .= ", password_hash = ?";
        $params[] = $password;
        $types .= "s";
    }

    $updateSql .= " WHERE id = ?";
    $params[] = $id;
    $types .= "i";

    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param($types, ...$params);

    if ($updateStmt->execute()) {
        header("Location: ../manage-accounts.php");
        exit();
    } else {
        echo "Error updating record: " . $updateStmt->error;
    }

    $updateStmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Edit User</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo $id; ?>"
            class="row g-3" onsubmit="return validateForm()">
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
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">Show</button>
                </div>
            </div>
            <div class="col-6">
                <label class="form-label">Confirm Password:</label>
                <div class="input-group">
                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password" autocomplete="off">
                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">Show</button>
                </div>
                <span id="password_error" style="color: red;"></span>
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
                    <option value="admin" <?php echo ($role == 'admin') ? 'selected' : ''; ?>>admin</option>
                    <option value="user" <?php echo ($role == 'user') ? 'selected' : ''; ?>>user</option>
                </select>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-success" name="update" style="width: 100px;">Update</button>
                <a href="../manage-accounts.php" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        function validateForm() {
            var password = document.getElementById('password').value;
            var confirm_password = document.getElementById('confirm_password').value;
            var password_error = document.getElementById('password_error');

            if (password !== confirm_password) {
                password_error.textContent = 'Password and confirm password do not match!';
                return false;
            } else {
                password_error.textContent = '';
                return true;
            }
        }

        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            togglePassword.textContent = type === 'password' ? 'Show' : 'Hide';
        });

        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPassword = document.getElementById('confirm_password');

        toggleConfirmPassword.addEventListener('click', function () {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            toggleConfirmPassword.textContent = type === 'password' ? 'Show' : 'Hide';
        });
    </script>
</body>

</html>
