// Fetching tasks and displaying due date
<?php
$tasks = $conn->query("SELECT * FROM task_list WHERE project_id = {$id} ORDER BY task ASC");
if ($tasks && $tasks->num_rows > 0) {
    while ($row = $tasks->fetch_assoc()):
?>
<tr>
    <td class="text-center"><?php echo $i++ ?></td>
    <td class=""><b><?php echo ucwords($row['task']) ?></b></td>
    <td class=""><p class="truncate"><?php echo strip_tags($row['description']) ?></p></td>
    <td>
        <?php 
        $taskStatus = $row['status'];
        if (isset($statusNames[$taskStatus])) {
            $taskStatusText = $statusNames[$taskStatus];
            $taskBadgeClass = '';
            switch ($taskStatus) {
                case 0:
                    $taskBadgeClass = 'badge-secondary';
                    break;
                case 1:
                    $taskBadgeClass = 'badge-warning';
                    break;
                case 2:
                    $taskBadgeClass = 'badge-success';
                    break;
                case 3:
                    $taskBadgeClass = 'badge-danger';
                    break;
            }
            echo "<span class='badge {$taskBadgeClass}'>{$taskStatusText}</span>";
        } else {
            echo "<span class='badge badge-warning'>Unknown</span>";
        }
        ?>
    </td>
    <td class="text-center"><?php echo isset($row['due_date']) ? date("M d, Y", strtotime($row['due_date'])) : ''; ?></td> <!-- Display the due date -->
    <td class="text-center">
        <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
            Action
        </button>
        <div class="dropdown-menu" style="">
            <a class="dropdown-item view_task" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-task="<?php echo $row['task'] ?>">View</a>
            <div class="dropdown-divider"></div>
            <?php if ($_SESSION['user_id'] != 3): ?>
                <a class="dropdown-item edit_task" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-task="<?php echo $row['task'] ?>">Edit</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item delete_task" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
            <?php endif; ?>
        </div>
    </td>
</tr>    
<?php endwhile; } else {
    echo "<tr><td colspan='5' class='text-center'>No tasks found.</td></tr>";
} ?>
