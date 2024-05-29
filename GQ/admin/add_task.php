<?php
include 'db_con.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_POST['project_id'];
    $task = $_POST['task'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $due_date = $_POST['dueDate'];

    //Debugging: Output received values
    error_log("Received dueDate: $due_date");

    $stmt = $conn->prepare("INSERT INTO task_list (project_id, task, description, status, due_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $project_id, $task, $description, $status, $due_date);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add task']);
    }
}
?>
