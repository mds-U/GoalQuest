<?php
session_start();
require __DIR__ . "/db_con.php";

$taskId = isset($_GET['id']) ? (int)$_GET['id'] : null;
if ($taskId) {
    $sql = "SELECT * FROM task_list WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $taskId);
    $stmt->execute();
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();

    if ($task) {
        ?>
        <div class="container-fluid">
            <dl>
                <dt><b class="border-bottom border-primary">Task Name</b></dt>
                <dd><?php echo isset($task['task']) ? ucwords($task['task']) : ''; ?></dd>
                <dt><b class="border-bottom border-primary">Description</b></dt>
                <dd><?php echo isset($task['description']) ? html_entity_decode($task['description']) : ''; ?></dd>
                <dt><b class="border-bottom border-primary">Due Date</b></dt>
                <dd><?php echo isset($task['due_date']) ? date("M d, Y", strtotime($task['due_date'])) : ''; ?></dd>
                <dt><b class="border-bottom border-primary">Status</b></dt>
                <dd>
                    <?php
                    $statusNames = array(
                        0 => 'Pending',
                        1 => 'On-Progress',
                        2 => 'Done',
                        3 => 'Overdue'
                    );
                    $status = isset($task['status']) ? $task['status'] : '';
                    if (isset($statusNames[$status])) {
                        $statusText = $statusNames[$status];
                        $badgeClass = '';
                        switch ($status) {
                            case 0:
                                $badgeClass = 'badge-secondary';
                                break;
                            case 1:
                                $badgeClass = 'badge-warning';
                                break;
                            case 2:
                                $badgeClass = 'badge-success';
                                break;
                            case 3:
                                $badgeClass = 'badge-danger';
                                break;
                        }
                        echo "<span class='badge {$badgeClass}'>{$statusText}</span>";
                    } else {
                        echo "<span class='badge badge-warning'>Unknown</span>";
                    }
                    ?>
                </dd>
            </dl>
        </div>
        <?php
    } else {
        echo "Task not found.";
    }
} else {
    echo "Invalid task ID.";
}
?>
