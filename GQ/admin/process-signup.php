<?php
//Validation
if (empty($_POST["name"])) {
    die("Name is required");
}

if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Valid email is required");
}

if (strlen($_POST["password"]) < 8) {
    die("Password must be at least 8 characters");
}

if (!preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}

if (!preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}


//Hash password
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$conn = require __DIR__ . "/db_con.php";

//Check if the email already exists in the database
$email = $_POST['email'];
$sql_check_email = "SELECT COUNT(*) AS count FROM users WHERE email = ?";
$stmt_check_email = $conn->prepare($sql_check_email);
$stmt_check_email->bind_param("s", $email);
$stmt_check_email->execute();
$result = $stmt_check_email->get_result();
$row = $result->fetch_assoc();
if ($row['count'] > 0) {
    die("Email already exists. Please choose a different email.");
}

//Define role and status
$role = "user";
$status = 0;

$sql = "INSERT INTO users (name, email, password_hash, role, status) VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->stmt_init();

if (!$stmt->prepare($sql)) {
    die("SQL error: " . $conn->error);
}

$stmt->bind_param("ssssi", $_POST['name'], $_POST['email'], $password_hash, $role, $status);

if ($stmt->execute()) {
    //After successful database insertion
    header("Location: signup.php?success=true");
    exit();
} else {
    die("An error occurred during signup. Please try again later.");
}
?>