<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION["user_id"];

$conn = require __DIR__ . "/db_con.php"; // Establish database connection

// Validate and sanitize the session user_id
$user_id = filter_var($_SESSION["user_id"], FILTER_SANITIZE_NUMBER_INT);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize form inputs
    $name = $_POST['name'];
    $status = $_POST['status'];
    $start_date = date('Y-m-d', strtotime($_POST['start_date']));
    $end_date = date('Y-m-d', strtotime($_POST['end_date']));
    $description = $_POST['description'];

    // Prepare the SQL statement for inserting data into the project_list table
    $sql_add = "INSERT INTO project_list (name, status, start_date, end_date, description, user_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_add = $conn->prepare($sql_add);

    // Check if the statement is prepared successfully
    if ($stmt_add) {
        // Bind parameters and execute the statement
        $stmt_add->bind_param("sisssi", $name, $status, $start_date, $end_date, $description, $user_id);
        $stmt_add->execute();

        // Check if the insertion was successful
        if ($stmt_add->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Project added successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add project.']);
        }
    } else {
        // Handle statement preparation error
        echo json_encode(['status' => 'error', 'message' => 'Statement preparation failed.']);
    }
} else {
    // Redirect if the form was not submitted
    header("Location: new_project.php");
    exit();
}
?>