<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header('Location: login.php');
    exit;
}

$conn = require __DIR__ . "/db_con.php";

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_assoc();
$stmt->close();

//Fetch the projects for specific user logged-in
$sql_projects = "SELECT * FROM project_list WHERE user_id = ?";
$stmt_projects = $conn->prepare($sql_projects);
$stmt_projects->bind_param("i", $_SESSION["user_id"]);
$stmt_projects->execute();
$projects_result = $stmt_projects->get_result();

$current_date = date("Y-m-d");

//Update project status if overdue
while ($project = $projects_result->fetch_assoc()) {
    if ($project['status'] != 2 && $current_date > $project['end_date']) {
        $update_sql = "UPDATE project_list SET status = 3 WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $project['id']);
        $update_stmt->execute();
        $update_stmt->close();
    }
}

$stmt_projects->execute();
$projects_result = $stmt_projects->get_result();
$stmt_projects->close();

$page_title = "My Projects || Goal Quest";
include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');

?>

<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid border-bottom border-info">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Project List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Project List</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <div class="col-lg-12">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <?php if ($_SESSION['user_id'] != 3): ?>
                            <div class="card-tools">
                                <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="new_project.php"><i class="fa fa-plus"></i> Add New project</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div id="list_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">

                            <table class="table table-hover table-condensed" >
                                <colgroup>
                                    <col width="5%">
                                    <col width="35%">
                                    <col width="15%">
                                    <col width="15%">
                                    <col width="20%">
                                    <col width="10%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Project</th>
                                        <th>Date Started</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $stat = array("Pending", "On-Progress", "Done", "Over Due");

                                    while ($row = $projects_result->fetch_assoc()):
                                        $trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
                                        unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
                                        $desc = strtr(html_entity_decode($row['description']), $trans);
                                        $desc = str_replace(array("<li>", "</li>"), array("", ", "), $desc);
                                    ?>
                                        <tr>
                                            <td class="text-center"><?php echo $i++; ?></td>
                                            <td>
                                                <p><b><?php echo ucwords($row['name']); ?></b></p>
                                                <p class="truncate"><?php echo strip_tags($desc); ?></p>
                                            </td>
                                            <td><b><?php echo date("M d, Y", strtotime($row['start_date'])); ?></b></td>
                                            <td><b><?php echo date("M d, Y", strtotime($row['end_date'])); ?></b></td>
                                            <td class="text-center">
                                                <?php
                                                $status = $stat[$row['status']];
                                                $badge_class = '';
                                                switch ($status) {
                                                    case 'Pending':
                                                        $badge_class = 'badge-secondary';
                                                        break;
                                                    case 'On-Progress':
                                                        $badge_class = 'badge-warning';
                                                        break;
                                                    case 'Done':
                                                        $badge_class = 'badge-success';
                                                        break;
                                                    case 'Over Due':
                                                        $badge_class = 'badge-danger';
                                                        break;
                                                }
                                                echo "<span class='badge $badge_class'>$status</span>";
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item view_project" href="view_project.php?id=<?php echo $row['id']; ?>">View</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="edit_project.php?id=<?php echo $row['id']; ?>">Edit</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item delete_project" href="includes/archive.php?id=<?php echo $row['id']; ?>">Delete</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>
</div>

<script>
    $(document).ready(function() {
        var table = $('#list').DataTable();

        $('#list_filter').on('keyup', function() {
            table.search(this.value).draw();
        });
    });
</script>

<script>
    //DELETE PROJECT
    $(document).ready(function() {
        $('.delete_project').on('click', function(e) {
            e.preventDefault();
            var deleteUrl = $(this).attr('href');

            swal({
                title: "Are you sure?",
                text: "Once deleted, you will be able to recover this project in the archive!",
                icon: "warning",
                buttons: ["Cancel", "Delete"],
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: deleteUrl,
                        method: 'GET',
                        success: function(response) {
                            swal("Poof! Your project has been deleted!", {
                                icon: "success",
                            }).then(() => {

                                window.location.href = "archive_tbl.php";
                            });
                        },
                        error: function(xhr, status, error) {
                            swal("Error", "Failed to delete project. Please try again later.", "error");
                        }
                    });
                } else {
                    swal("Your project is safe!", {
                        icon: "info",
                    });
                }
            });
        });
    });
</script>


<style>
    body, input, select, textarea, button, .card-header, .card-body, .card-footer, .breadcrumb-item, .btn, .dropdown-menu, .dropdown-item {
        font-family: 'Poppins', sans-serif !important;
    }
    table p {
        margin: unset !important;
    }
    table td {
        vertical-align: middle !important;
    }
    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -7.5px;
        margin-left: -7.5px;
    }
    div.dataTables_wrapper div.dataTables_length select {
        width: auto;
        display: inline-block;
    }
    label:not(.form-check-label):not(.custom-file-label) {
        font-weight: 700;
    }
    *, ::after, ::before {
        box-sizing: border-box;
    }
    div.dataTables_wrapper div.dataTables_length label {
        font-weight: normal;
        text-align: left;
        white-space: nowrap;
    }
    div.dataTables_wrapper div.dataTables_length select {
        width: auto;
        display: inline-block;
    }
    @media (min-width: 768px) {
        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
    }
    @media (min-width: 768px) {
        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
    div.dataTables_wrapper div.dataTables_filter input {
        margin-left: 0.5em;
        display: inline-block;
        width: auto;
    }
    .form-control-sm {
        height: calc(1.8125rem + 2px);
        padding: .25rem .5rem;
        font-size: .875rem;
        line-height: 1.5;
        border-radius: .2rem;
    }
    div.dataTables_wrapper div.dataTables_filter label {
        font-weight: normal;
        white-space: nowrap;
        text-align: left;
    }
    div.dataTables_wrapper div.dataTables_length select {
        width: auto;
        display: inline-block;
    }
    select {
        transition: background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }
    .custom-select-sm {
        height: calc(1.8125rem + 2px);
        padding-top: .25rem;
        padding-bottom: .25rem;
        padding-left: .5rem;
        font-size: 75%;
    }
    div.dataTables_wrapper div.dataTables_paginate ul.pagination {
        margin: 2px 0;
        white-space: nowrap;
        justify-content: flex-end;
    }
    .pagination {
        display: flex;
        padding-left: 0;
        list-style: none;
        border-radius: .25rem;
    }
    div.dataTables_wrapper div.dataTables_info {
        padding-top: 0.85em;
    }
</style>

<?php
    include('includes/footer.php');
?>
