<?php
include 'db_con.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['editTaskId'];
    $task = $_POST['editTaskName'];
    $description = $_POST['editDescription'];
    $status = $_POST['editStatus'];
    $due_date = $_POST['editdueDate'];

    $stmt = $conn->prepare("UPDATE task_list SET task = ?, description = ?, status = ?, due_date = ? WHERE id = ?");
    $stmt->bind_param("ssisi", $task, $description, $status, $due_date, $taskId); 

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update task']);
    }
}
?>
