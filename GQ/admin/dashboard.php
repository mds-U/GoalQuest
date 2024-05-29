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

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Two columns before the row of small boxes -->
        <div class="row">
            <div class="col-lg-6 col-12">
                <!-- Content for the first column -->

                <div class="row">

                    <!-- small box for Reminder-->
                    <div class="col-lg-12 col-6">
                        <a href="reminder.php" class="small-box bg-white">
                            <div class="inner">
                                <h3><sup style="font-size: 20px"></sup></h3>
                                    <?php
                                        $date_compare = date('Y-m-d', strtotime('+1 day'));
                                        $reminder_query = "SELECT * FROM project_list WHERE user_id = {$_SESSION["user_id"]} AND end_date = '$date_compare' AND status != 2";
                                        $reminder_query_run = mysqli_query($conn, $reminder_query);

                                        if ($reminder_query_run) {
                                            $reminder_total = mysqli_num_rows($reminder_query_run);
                                            echo '<h3 class="mb-0"> ' . $reminder_total . ' </h3>';
                                        } else {
                                            echo '<h3 class="mb-0"> 0 </h3>';
                                            echo "Error: " . mysqli_error($conn);
                                        }
                                    ?>
                                    <p>Today's Reminder</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-android-notifications"></i>
                            </div>
                        </a>
                    </div>
                    
                    <!-- small box for Total Projects-->
                    <div class="col-lg-6 col-6">
                        <a href="project_list.php" class="small-box bg-primary">
                            <div class="inner">
                                <h3><sup style="font-size: 20px"></sup></h3>
                                    <?php
                                        $dash_project_query = "SELECT * FROM project_list WHERE user_id = {$_SESSION["user_id"]}";
                                        $dash_project_query_run = mysqli_query($conn, $dash_project_query);

                                        if ($dash_project_query_run) {
                                            $project_total = mysqli_num_rows($dash_project_query_run);
                                            echo '<h3 class="mb-0"> ' . $project_total . ' </h3>';
                                        } else {
                                            echo '<h3 class="mb-0"> 0 </h3>';
                                            echo "Error: " . mysqli_error($conn);
                                        }
                                    ?>
                                    <p>Total Projects</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-folder"></i>
                            </div>
                        </a>
                    </div>
                    <!-- ./col -->

                    <!-- small box for Pending Projects-->
                    <div class="col-lg-6 col-6">
                        <a href="project_pending.php" class="small-box bg-warning">
                            <div class="inner">
                                <h3></h3>
                                <?php
                                    $dash_pending_project_query = "SELECT * FROM project_list WHERE status = '0' AND user_id = {$_SESSION["user_id"]}";
                                    $dash_pending_project_query_run = mysqli_query($conn, $dash_pending_project_query);

                                        if ($dash_pending_project_query_run) {
                                            $pending_project_total = mysqli_num_rows($dash_pending_project_query_run);
                                            echo '<h3 class="mb-0"> ' . $pending_project_total . ' </h3>';
                                        } else {
                                            echo '<h3 class="mb-0"> 0 </h3>';
                                            echo "Error: " . mysqli_error($conn);
                                        }
                                ?>
                                    <p>Pending Projects</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-android-warning"></i>
                            </div>
                        </a>
                    </div>
                    <!-- ./col -->
                    
                    <!-- small box for On-Progress Projects -->
                    <div class="col-lg-6 col-6">
                        <a href="project_on_progress.php" class="small-box bg-info">
                            <div class="inner">
                                <h3></h3>
                                <?php
                                    $dash_on_progress_project_query = "SELECT * FROM project_list WHERE status = '1' AND user_id = {$_SESSION["user_id"]}";
                                    $dash_on_progress_project_query_run = mysqli_query($conn, $dash_on_progress_project_query);

                                        if ($dash_on_progress_project_query_run) {
                                            $on_progress_project_total = mysqli_num_rows($dash_on_progress_project_query_run);
                                            echo '<h3 class="mb-0"> ' . $on_progress_project_total . ' </h3>';
                                        } else {
                                            echo '<h3 class="mb-0"> 0 </h3>';
                                            echo "Error: " . mysqli_error($conn);
                                        }
                                    ?>
                                    <p>On-Progress Projects</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-android-star-half"></i>
                            </div>
                        </a>
                    </div>
                    <!-- ./col -->

                    <!-- small box for Completed Projects-->
                    <div class="col-lg-6 col-6">
                        <a href="project_completed.php" class="small-box bg-success">
                            <div class="inner">
                                <h3><sup style="font-size: 20px"></sup></h3>
                                <?php
                                    $dash_completed_project_query = "SELECT * FROM project_list WHERE status = '2' AND user_id = {$_SESSION["user_id"]}";
                                    $dash_completed_project_query_run = mysqli_query($conn, $dash_completed_project_query);

                                        if ($dash_completed_project_query_run) {
                                            $completed_project_total = mysqli_num_rows($dash_completed_project_query_run);
                                            echo '<h3 class="mb-0"> ' . $completed_project_total . ' </h3>';
                                        } else {
                                            echo '<h3 class="mb-0"> 0 </h3>';
                                            echo "Error: " . mysqli_error($conn);
                                        }
                                ?>
                                    <p>Completed Projects</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-android-star"></i>
                            </div>
                        </a>
                    </div>
                    <!-- ./col -->

                    <!-- small box for Overdue Projects-->
                    <div class="col-lg-6 col-6">
                        <a href="project_overdue.php" class="small-box bg-danger">
                            <div class="inner">
                                <h3></h3>
                                <?php
                                    $dash_overdue_project_query = "SELECT * FROM project_list WHERE status = '3' AND user_id = {$_SESSION["user_id"]}";
                                    $dash_overdue_project_query_run = mysqli_query($conn, $dash_overdue_project_query);

                                        if ($dash_overdue_project_query_run) {
                                            $overdue_project_total = mysqli_num_rows($dash_overdue_project_query_run);
                                            echo '<h3 class="mb-0"> ' . $overdue_project_total . ' </h3>';
                                        } else {
                                            echo '<h3 class="mb-0"> 0 </h3>';
                                            echo "Error: " . mysqli_error($conn);
                                        }
                                    ?>
                                    <p>Overdue Projects</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-android-time"></i>
                            </div>
                        </a>
                    </div>
                    <!-- ./col -->

                    <!-- small box for Archive Projects-->
                    <div class="col-lg-6 col-6">
                        <a href="archive_tbl.php" class="small-box bg-secondary">
                            <div class="inner">
                                <h3></h3>
                                <?php
                                    $dash_archive_query = "SELECT * FROM archive_list WHERE user_id = {$_SESSION["user_id"]}";
                                    $dash_archive_query_run = mysqli_query($conn, $dash_archive_query);

                                        if ($dash_archive_query_run) {
                                            $archive_total = mysqli_num_rows($dash_archive_query_run);
                                            echo '<h3 class="mb-0"> ' . $archive_total . ' </h3>';
                                        } else {
                                            echo '<h3 class="mb-0"> 0 </h3>';
                                            echo "Error: " . mysqli_error($conn);
                                        }
                                    ?>
                                    <p>Archive</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-ios-trash"></i>
                            </div>
                        </a>
                    </div>
                    <!-- ./col -->
                </div>
            </div>

            <div class="col-lg-6 col-12">
                <!-- Content for the second column -->

                <div class="card" style="height: 500px;">
                    <div class="card-body" id="piechart" style="width: 528px; height: 528px;">
                        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                        
                        <script type="text/javascript">
                            google.charts.load('current', {'packages':['corechart']});
                            google.charts.setOnLoadCallback(drawChart);

                            function drawChart() {
                                var data = google.visualization.arrayToDataTable([
                                    ['Project Status', 'Number of Projects'],
                                    <?php
                                        $status_query = "SELECT status, COUNT(*) as count FROM project_list WHERE user_id = {$_SESSION["user_id"]} GROUP BY status";
                                        $status_query_run = mysqli_query($conn, $status_query);
                                        while($row = mysqli_fetch_assoc($status_query_run)) {
                                            $status = '';
                                            switch($row['status']) {
                                                case '0':
                                                    $status = 'Pending';
                                                    break;
                                                case '1':
                                                    $status = 'On-Progress';
                                                    break;
                                                case '2':
                                                    $status = 'Completed';
                                                    break;
                                                case '3':
                                                    $status = 'Overdue';
                                                    break;
                                                default:
                                                    $status = 'Unknown';
                                                    break;
                                            }
                                            echo "['".$status."', ".$row['count']."],";
                                        }
                                    ?>
                                ]);
                                    
                                var options = {
                                    title: 'Project Statistics',
                                    fontName: 'Poppins'
                                };

                                var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                                chart.draw(data, options);
                            }
                        </script>
                    </div>
                </div>
            </div>
            
        </div><!-- /.container-fluid -->
    </section>   
</div>

<style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .content-header h1, .breadcrumb-item a, .breadcrumb-item.active, .small-box p {
            font-family: 'Poppins', sans-serif;
        }
        .small-box h3 {
            font-family: 'Poppins', sans-serif;
        }
    </style>

<?php
    include('includes/footer.php');
?>
