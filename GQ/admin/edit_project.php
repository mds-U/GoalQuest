<?php
session_start();

if (isset($_SESSION["user_id"])) {
    $conn = require __DIR__ . "/db_con.php";
    $sql = "SELECT * FROM users WHERE id = {$_SESSION["user_id"]}";
    $result = $conn->query($sql);
    $users = $result->fetch_assoc();
} else {
    header('Location: login.php');
    exit;
}

$conn = require __DIR__ . "/db_con.php";

$sql = "SELECT * FROM users WHERE id = " . intval($_SESSION["user_id"]);
$result = $conn->query($sql);
$user = $result->fetch_assoc();

$is_edit = false;
$project = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $project_id = intval($_GET['id']);
    $qry = $conn->query("SELECT * FROM project_list WHERE id = {$project_id}");
    if ($qry && $qry->num_rows > 0) {
        $project = $qry->fetch_assoc();
        $is_edit = true;
    } else {
        echo "Project not found.";
        exit;
    }
} else {
    echo "Invalid project ID.";
    exit;
}

$page_title = "Edit Project || Goal Quest";
include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');
?>


<div id="overlay">
    <div class="loader"></div>
</div>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid border-bottom border-info">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Project</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Edit Project</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="toast" id="alert_toast" style="position: fixed; top: 10px; right: 10px; width: 300px; z-index: 1050;">
                <div class="toast-header bg-success text-white">
                    <strong class="mr-auto" id="toast_title"></strong>
                    <small class="text-white">Just now</small>
                    <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body bg-light" id="toast_body"></div>
            </div>

            <div class="card card-primary">
                <div class="card-body">
                    <form action="process_edit_project.php" id="manage-project" method="POST">
                        <input type="hidden" name="id" value="<?php echo isset($project['id']) ? $project['id'] : '' ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="" class="control-label">Name</label>
                                    <input type="text" class="form-control form-control-sm" name="name" value="<?php echo isset($project['name']) ? $project['name'] : '' ?>" required autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <select name="status" id="status" class="custom-select custom-select-sm">
                                        <option value="0" <?php echo isset($project['status']) && $project['status'] == 0 ? 'selected' : '' ?>>Pending</option>
                                        <option value="1" <?php echo isset($project['status']) && $project['status'] == 1 ? 'selected' : '' ?>>On-Progress</option>
                                        <option value="2" <?php echo isset($project['status']) && $project['status'] == 2 ? 'selected' : '' ?>>Done</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="" class="control-label">Start Date</label>
                                    <input type="date" class="form-control form-control-sm" autocomplete="off" name="start_date" value="<?php echo isset($project['start_date']) ? $project['start_date'] : '' ?>" <?php echo $is_edit ? 'readonly' : '' ?> required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="" class="control-label">End Date</label>
                                    <input type="date" class="form-control form-control-sm" autocomplete="off" name="end_date" value="<?php echo isset($project['end_date']) ? $project['end_date'] : '' ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="" class="control-label">Description</label>
                                    <textarea name="description" id="" cols="30" rows="10" class="summernote form-control" required><?php echo isset($project['description']) ? $project['description'] : '' ?></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer border-top border-info">
                    <div class="d-flex w-100 justify-content-center align-items-center">
                        <button class="btn btn-flat  bg-gradient-primary mx-2" form="manage-project">Save</button>
                        <button class="btn btn-flat bg-gradient-secondary mx-2" type="button" onclick="location.href='project_list.php'">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('#manage-project').submit(function(e) {
            e.preventDefault();
            start_load();

            $.ajax({
                url: 'process_edit_project.php',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success: function(resp) {
                    end_load(); 
                    let response = JSON.parse(resp);

                    if (response.status == 'success') {
                        alert_toast(response.message, "success");
                    } else {
                        alert_toast(response.message, "error");
                    }
                },
                error: function(xhr, status, error) {
                    end_load(); 
                    alert_toast('An error occurred. Please try again.', "error");
                }
            });
        });

        function alert_toast(message, type) {
            $('#toast_title').html(type == 'success' ? 'Success' : 'Error');
            $('#toast_body').html(message);
            $('#alert_toast').toast({
                delay: 3000
            }).toast('show');

            $('#overlay').css('display', 'block');

            setTimeout(function() {
                $('#overlay').css('display', 'none');
                $('#manage-project')[0].reset(); 
                window.location.href = 'project_list.php'; 
            }, 3000);
        }

        function start_load() {
            $('#loading').show();
        }

        function end_load() {
            $('#loading').hide();
        }
    });
</script>

<style>
    .toast {
        position: fixed;
        top: 10px;
        right: 10px;
        min-width: 250px;
    }

    #overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 999; 
    background-color: rgba(0, 0, 0, 0.5);
    display: none; 
    }

    .loader {
        border: 6px solid #A9A9A9; 
        border-top: 6px solid #3498db; 
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 2s linear infinite;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -25px; 
        margin-left: -25px; 
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<?php
    include('includes/footer.php');
?>