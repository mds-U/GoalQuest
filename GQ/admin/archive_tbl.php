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

$where = "";

$page_title = "Archive || Goal Quest";
include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');
?>

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid border-bottom border-info">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Archive</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Archive</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="col-lg-12">
                <div class="card card-outline card-success">
                    <div class="card-body">
                        <div id="list_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">

                            <table class="table table-hover table-condensed" id="myTable">
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
                                    $stat = array("Pending", "On-Progress", "Done", "Over Due" );
                                    $qry = $conn->query("SELECT * FROM archive_list WHERE user_id = {$_SESSION["user_id"]} ORDER BY id DESC");
                                    while ($row = $qry->fetch_assoc()) :
                                        $trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
                                        unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
                                        $desc = strtr(html_entity_decode($row['description']), $trans);
                                        $desc = str_replace(array("<li>", "</li>"), array("", ", "), $desc);
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $i++ ?></td>
                                            <td>
                                                <p><b><?php echo ucwords($row['name']) ?></b></p>
                                                <p class="truncate"><?php echo strip_tags($desc) ?></p>
                                            </td>
                                            <td><b><?php echo date("M d, Y", strtotime($row['start_date'])) ?></b></td>
                                            <td><b><?php echo date("M d, Y", strtotime($row['end_date'])) ?></b></td>
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
                                                    case 'Over Due':
                                                        $badge_class = 'badge-danger';
                                                        break;
                                                    case 'Done':
                                                        $badge_class = 'badge-success';
                                                        break;
                                                }
                                                echo "<span class='badge $badge_class'>$status</span>";
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" style="">
                    
                                                    <a class="dropdown-item restore_project" href="includes/restore.php?id=<?php echo $row['id']; ?>">Restore</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item delete_project" href="includes/delete.php?id=<?php echo $row['id']; ?>">Permanently Delete</a>
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
        </div>
    </section>
</div>

<script>
    //PERMANENTLY DELETE PROJECT
    $(document).ready(function() {
        $('.delete_project').on('click', function(e) {
            e.preventDefault();
            var deleteUrl = $(this).attr('href');

            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this project!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: deleteUrl,
                        method: 'GET',
                        success: function(response) {
                            swal("Poof! Your project has been deleted!", {
                                icon: "success",
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            swal("Error occurred while deleting project: " + error, {
                                icon: "error",
                            });
                        }
                    });
                } else {
                    swal("Your project is safe!");
                }
            });
        });
    });
</script>

<script>
    // RESTORE PROJECT
    $(document).ready(function() {
        $('.restore_project').on('click', function(e) {
            e.preventDefault();
            var restoreUrl = $(this).attr('href');

            swal({
                title: "Are you sure?",
                text: "Do you want to restore this project?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willRestore) => {
                if (willRestore) {
                    $.ajax({
                        url: restoreUrl,
                        method: 'GET',
                        success: function(response) {
                            swal("Project restored successfully.", {
                                icon: "success",
                            }).then(() => {
                                window.location.href = 'project_list.php';
                            });
                        },
                        error: function(xhr, status, error) {
                            swal("Error occurred while restoring project: " + error, {
                                icon: "error",
                            });
                        }
                    });
                } else {
                    swal("Your project restoration was cancelled!");
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        var table = $('#list').DataTable();

        $('#list_filter').on('keyup', function() {
            table.search(this.value).draw();
        });
    });
</script>

<style>
    body, input, select, textarea, button, .card-header, .card-body, .card-footer, .breadcrumb-item, .btn, .dropdown-menu, .dropdown-item, .badge, h1, h2, h3, h4, h5, h6, p, a, li, table {
        font-family: 'Poppins', sans-serif !important;
    }
    
    table p {
        margin: unset !important;
    }

    table td {
        vertical-align: middle !important
    }

	.row {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
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
        -ms-flex: 0 0 50%;
        flex: 0 0 50%;
        max-width: 50%;
    }
	}

	div.dataTables_wrapper div.dataTables_filter {
    text-align: right;
	}

	@media (min-width: 768px) {
    .col-md-6 {
        -ms-flex: 0 0 50%;
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