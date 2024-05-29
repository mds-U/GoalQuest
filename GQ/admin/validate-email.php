<?php
    $conn = require __DIR__ . "/db_con.php";

    $email = $conn->real_escape_string($_GET["email"]);

    $sql = "SELECT * FROM users WHERE email = '$email'";
    
    $result = $conn->query($sql);

    $is_available = $result->num_rows === 0;

    header("Content-Type: application/json");

    echo json_encode(["available" => $is_available]);
?>