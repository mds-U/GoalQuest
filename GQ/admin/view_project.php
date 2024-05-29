<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header('Location: login.php');
    exit;
}

$conn = require __DIR__ . "/db_con.php";

//Fetch user details
$sql = "SELECT * FROM users WHERE id = ?"; 
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_assoc();

//Fetch project details
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$sql = "SELECT * FROM project_list WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$project_list = $result->fetch_assoc();

$statusNames = array(
    0 => 'Pending',
    1 => 'On-Progress',
    2 => 'Done',
    3 => 'Overdue'
);

$page_title = "View Project || Goal Quest";
include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">View Project</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">View Project</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="callout callout-info">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <dl>
                                <dt><b class="border-bottom border-primary">Project Name</b></dt>
                                <dd><?php echo isset($project_list['name']) ? ucwords($project_list['name']) : ''; ?></dd>
                                <dt><b class="border-bottom border-primary">Description</b></dt>
                                <dd><?php echo isset($project_list['description']) ? html_entity_decode($project_list['description']) : ''; ?></dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl>
                                <dt><b class="border-bottom border-primary">Start Date</b></dt>
                                <dd><?php echo isset($project_list['start_date']) ? date("M d, Y", strtotime($project_list['start_date'])) : ''; ?></dd>
                            </dl>
                            <dl>
                                <dt><b class="border-bottom border-primary">End Date</b></dt>
                                <dd><?php echo isset($project_list['end_date']) ? date("M d, Y", strtotime($project_list['end_date'])) : ''; ?></dd>
                            </dl>
                            <dl>
                                <dt><b class="border-bottom border-primary">Status</b></dt>
                                <dd>
                                    <?php
                                    $status = isset($project_list['status']) ? $project_list['status'] : '';
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
                    </div>
                </div>
            </div>
            
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <span><b>Task List:</b></span>
                    <?php if ($_SESSION['user_id'] != 3): ?>
                        <div class="card-tools">
                            <button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="new_task"><i class="fa fa-plus"></i> New Task</button>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-condensed m-0 table-hover">
                            <!-- <colgroup>
                                <col width="5%">
                                <col width="25%">
                                <col width="30%">
                                <col width="15%">
                                <col width="15%">
                            </colgroup> -->
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Task</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                $tasks = $conn->query("SELECT * FROM task_list WHERE project_id = {$id} ORDER BY task ASC");
                                if ($tasks && $tasks->num_rows > 0) {
                                    while ($row = $tasks->fetch_assoc()):
                                        $trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
                                        unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
                                        $asc = strtr(html_entity_decode($row['description']), $trans);
                                        $asc = str_replace(array("<li>", "</li>"), array("", ", "), $asc);
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++ ?></td>
                                    <td class=""><b><?php echo ucwords($row['task']) ?></b></td>
                                    <td class=""><p class="truncate"><?php echo strip_tags($asc) ?></p></td>
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            </div>
        </div>
    </section>
</div>


<!-- View Task Modal -->
<div class="modal fade" id="viewTaskModal" tabindex="-1" role="dialog" aria-labelledby="viewTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTaskModalLabel">View Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="viewTaskModalBody">
                <!-- Content from view_task.php will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTaskModalLabel">Add New Task For <?php echo isset($project_list['name']) ? ucwords($project_list['name']) : ''; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addTaskForm">
                    <input type="hidden" name="project_id" value="<?php echo $id; ?>"> <!-- Add this line -->
                    <div class="form-group">
                        <label for="taskName">Task Name</label>
                        <input type="text" class="form-control" id="task" name="task" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="8" required autocomplete="off"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="dueDate">Due Date</label>
                        <input type="date" class="form-control" id="dueDate" name="dueDate" required
                               min="<?php echo date('Y-m-d', strtotime($project_list['start_date'])); ?>"
                               max="<?php echo date('Y-m-d', strtotime($project_list['end_date'])); ?>">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="0">Pending</option>
                            <option value="1">On-Progress</option>
                            <option value="2">Done</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Task</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Task Confirmation Modal -->
<div class="modal fade" id="deleteTaskModal" tabindex="-1" role="dialog" aria-labelledby="deleteTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTaskModalLabel">Delete Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this task?</p>
                <input type="hidden" id="deleteTaskId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteTask">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" role="dialog" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editTaskForm">
                    <input type="hidden" id="editTaskId" name="editTaskId">
                    <div class="form-group">
                        <label for="editTaskName">Task Name</label>
                        <input type="text" class="form-control" id="editTaskName" name="editTaskName" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="editDescription">Description</label>
                        <textarea class="form-control" id="editDescription" name="editDescription" rows="8" autocomplete="off"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editdueDate">Due Date</label>
                        <input type="date" class="form-control" id="editdueDate" name="editdueDate" autocomplete="off"
                               min="<?php echo date('Y-m-d', strtotime($project_list['start_date'])); ?>"
                               max="<?php echo date('Y-m-d', strtotime($project_list['end_date'])); ?>">
                    </div>
                    <div class="form-group">
                        <label for="editStatus">Status</label>
                        <select class="form-control" id="editStatus" name="editStatus" required>
                            <option value="0">Pending</option>
                            <option value="1">On-Progress</option>
                            <option value="2">Done</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Update Task</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function(){
    $('#new_task').click(function(){
        $('#addTaskModal').modal('show');
    });

    //VIEW TASK
    $('.view_task').click(function(){
        var taskId = $(this).data('id');
        $.ajax({
            url: 'view_task.php',
            method: 'GET',
            data: {id: taskId},
            success: function(response){
                $('#viewTaskModalBody').html(response);
                $('#viewTaskModal').modal('show');
            },
            error: function(xhr, status, error){
                console.error('Error:', xhr.responseText);
            }
        });
    });

    //ADD TASK
    $('#addTaskForm').submit(function(e){
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: 'add_task.php',
                method: 'POST',
                data: formData,
                success: function(response){
                    console.log('Response:', response);
                    $('#addTaskModal').modal('hide');
                    swal("Success!", "Task added successfully.", "success").then(()=>{
                        location.reload();
                    });
                },
                error: function(xhr, status, error){
                    console.error('Error:', xhr.responseText);
                    showAlert('error', 'Failed to add task. Please try again.');
                }
            });
        });

    //Delete task
    $('.delete_task').click(function(){
        var taskId = $(this).data('id');
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this task!",
            icon: "warning",
            buttons: ["Cancel", "Delete"],
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: 'delete_task.php',
                    method: 'POST',
                    data: { taskId: taskId },
                    success: function(response){
                        swal("Poof! Your task has been deleted!", {
                            icon: "success",
                        }).then(()=>{
                        location.reload();
                    });
                    },
                    error: function(xhr, status, error){
                        console.error('Error:', xhr.responseText);
                        showAlert('error', 'Failed to delete task. Please try again.');
                    }
                });
            } else {
                swal("Your task is safe!");
            }
        });
    });

    //Edit Task
    $('.edit_task').click(function(){
        var taskId = $(this).data('id');
        $.ajax({
            url: 'get_task.php',
            method: 'POST',
            data: {taskId: taskId},
            dataType: 'json',
            success: function(response){
                $('#editTaskId').val(response.id);
                $('#editTaskName').val(response.task);
                $('#editDescription').val(response.description);
                $('#editStatus').val(response.status);
                $('#editdueDate').val(response.due_date); 
                $('#editTaskModal').modal('show');
            },
            error: function(xhr, status, error){
                console.error('Error:', xhr.responseText);
                showAlert('error', 'Failed to fetch task details. Please try again.');
            }
        });
    });

    //Handle form submission for editing the task
    $('#editTaskForm').submit(function(e){
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: 'edit_task.php',
            method: 'POST',
            data: formData,
            success: function(response){
                console.log('Response:', response);
                $('#editTaskModal').modal('hide');
                swal("Success!", "Task updated successfully.", "success").then(()=>{
                    location.reload();
                });
            },
            error: function(xhr, status, error){
                console.error('Error:', xhr.responseText);
                showAlert('error', 'Failed to update task. Please try again.');
            }
        });
    });
});
</script>


<style>
    body, h1, h2, h3, h4, h5, h6, p, a, span, button, input, select, option, textarea {
        font-family: 'Poppins', sans-serif;
    }
    .users-list>li img {
        border-radius: 50%;
        height: 67px;
        width: 67px;
        object-fit: cover;
    }
    .users-list>li {
        width: 33.33% !important;
    }
    .truncate {
        -webkit-line-clamp: 1 !important;
    }
    th, td {
    text-align: center;
    }
</style>

<?php
    include('includes/footer.php');
?>
