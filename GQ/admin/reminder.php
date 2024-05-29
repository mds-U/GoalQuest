<?php

    session_start();
    
    if (isset($_SESSION["user_id"])) {
        $conn = require __DIR__ . "/db_con.php";
        $sql = "SELECT * FROM users WHERE id = {$_SESSION["user_id"]}";
        $result = $conn->query($sql);
        $users = $result->fetch_assoc();
    }
    else {
      header('Location: login.php');
      exit;
    }

    $page_title = "Dashboard || Goal Quest";
    include('includes/header.php');
    include('includes/topbar.php');
    include('includes/sidebar.php');
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Reminders</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Reminders</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <div class="col-lg-12">
                <div class="card card-outline card-success">
                    <div class="card-body">
                        <div id="list_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">

                            <table class="table table-hover table-condensed" id="myTable">
                                <colgroup>
                                    <col width="5%">
                                    <col width="40%">
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $date_compare = date('Y-m-d', strtotime('+1 day'));
                                        $query = "SELECT * FROM project_list WHERE user_id = {$_SESSION["user_id"]} AND end_date = '$date_compare'";
                                        $result = mysqli_query($conn, $query);

                                        $status_mapping = [
                                            0 => 'Pending',
                                            1 => 'On-Progress',
                                            2 => 'Done',
                                            3 => 'Overdue'
                                        ];

                                        while ($row = mysqli_fetch_assoc($result)) {
                                            if ($row['status'] == 2) {
                                                continue;
                                            }

                                            $status_text = isset($status_mapping[$row['status']]) ? $status_mapping[$row['status']] : 'Unknown';

                                            echo "<tr>";
                                            echo "<td class='text-center'>" . $row['id'] . "</td>";
                                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['start_date']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['end_date']) . "</td>";
                                            echo "<td>" . htmlspecialchars($status_text) . "</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<style>
    body, input, select, textarea, button, .card-header, .card-body, .card-footer, .breadcrumb-item, .btn, .dropdown-menu, .dropdown-item, .badge, h1, h2, h3, h4, h5, h6, p, a, li, table {
        font-family: 'Poppins', sans-serif !important;
    }
</style>

<?php
    include('includes/footer.php');
?>
