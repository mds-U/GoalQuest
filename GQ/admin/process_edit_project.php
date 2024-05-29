<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION["user_id"];
$conn = require __DIR__ . "/db_con.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
    $start_date = date('Y-m-d', strtotime($_POST['start_date']));
    $end_date = date('Y-m-d', strtotime($_POST['end_date']));
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

    $sql_update = "UPDATE project_list SET name = ?, status = ?, start_date = ?, end_date = ?, description = ?, user_id = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);

    if ($stmt_update) {
        $stmt_update->bind_param("sisssii", $name, $status, $start_date, $end_date, $description, $user_id, $id);
        $stmt_update->execute();

        if ($stmt_update->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Project updated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update project.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Statement preparation failed.']);
    }
} else {
    header("Location: edit_project.php?id={$id}");
    exit();
}
?>
