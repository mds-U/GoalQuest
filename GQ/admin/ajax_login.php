<?php
session_start();
header('Content-Type: application/json');

$response = array('success' => false, 'message' => '');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = require __DIR__ . "/db_con.php";

    $sql = sprintf("SELECT * FROM users WHERE email = '%s'",
                   $conn->real_escape_string($_POST["email"]));

    $result = $conn->query($sql);

    $user = $result->fetch_assoc();

    if ($user) {
        if (password_verify($_POST["password"], $user["password_hash"])) {
            if ($user["status"] == 1) {
                session_regenerate_id();
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["role"] = $user["role"];
                
                $response['success'] = true;
                $response['role'] = $user["role"];
            } else {
                $response['message'] = "Account is not activated. Please contact the administrator.";
            }
        } else {
            $response['message'] = "Invalid password.";
        }
    } else {
        $response['message'] = "Email not found.";
    }
} else {
    $response['message'] = "Invalid request method.";
}

echo json_encode($response);
?>
