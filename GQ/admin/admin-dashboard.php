<?php

    session_start();
    
    if (isset($_SESSION["user_id"])) {
        $conn = require __DIR__ . "/db_con.php";
        $sql = "SELECT * FROM users WHERE id = {$_SESSION["user_id"]}";
        $result = $conn->query($sql);
        $user = $result->fetch_assoc();
    }
    else {
      header('Location: login.php');
      exit;
    }

    $page_title = "Admin Dashboard || Goal Quest";
    include('includes/admin-header.php');
    include('includes/admin-topbar.php');
    include('includes/admin-sidebar.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Admin</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="admin-dashboard.php">Home</a></li>
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
                 <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-12 col-6">
                <!-- small box -->
                <a href="manage-accounts.php" class="small-box bg-secondary">
                    <div class="inner">
                        <h3><sup style="font-size: 20px"></sup></h3>
                        <?php
                            $user_total_query = "SELECT COUNT(*) AS total_users FROM users";
                            $user_total_query_run = mysqli_query($conn, $user_total_query);
        
                            if ($user_total_query_run) {
                                $user_total = mysqli_fetch_assoc($user_total_query_run)['total_users'];
                                echo '<h3 class="mb-0"> ' . $user_total . ' </h3>';
                            } else {
                                echo '<h3 class="mb-0"> 0 </h3>';
                                echo "Error: " . mysqli_error($conn);
                            }
                        ?>
                        <p>Total Users</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-people"></i>
                    </div>
                </a>
            </div>
            <!-- ./col -->
            

            <div class="col-lg-6 col-6">
                <!-- small box -->
                <a href="admin-active_accounts.php" class="small-box bg-primary">
                    <div class="inner">
                        <h3></h3>
                        <?php
                            $active_user_query = "SELECT COUNT(*) AS total_active_users FROM users WHERE status = 1";
                            $active_user_query_run = mysqli_query($conn, $active_user_query);
        
                            if ($active_user_query_run) {
                                $active_user_total = mysqli_fetch_assoc($active_user_query_run)['total_active_users'];
                                echo '<h3 class="mb-0"> ' . $active_user_total . ' </h3>';
                            } else {
                                echo '<h3 class="mb-0"> 0 </h3>';
                                echo "Error: " . mysqli_error($conn);
                            }
                        ?>
                        <p>Total Active Accounts</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person"></i>
                    </div>
                </a>
            </div>
            <!-- ./col -->
            <div class="col-lg-6 col-6">
                <!-- small box -->
                <a href="admin-inactive_accounts.php" class="small-box bg-danger">
                    <div class="inner">
                        <h3></h3>
                        <?php
                            $inactive_user_query = "SELECT COUNT(*) AS total_inactive_users FROM users WHERE status = 0";
                            $inactive_user_query_run = mysqli_query($conn, $inactive_user_query);
        
                            if ($inactive_user_query_run) {
                                $inactive_user_total = mysqli_fetch_assoc($inactive_user_query_run)['total_inactive_users'];
                                echo '<h3 class="mb-0"> ' . $inactive_user_total . ' </h3>';
                            } else {
                                echo '<h3 class="mb-0"> 0 </h3>';
                                echo "Error: " . mysqli_error($conn);
                            }
                        ?>
                        <p>Total Inactive Accounts</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person"></i>
                    </div>
                </a>
            </div>
            <!-- ./col -->
          </div>  
          </div>

            <div class="col-lg-6 col-12">
                <!-- Content for the second column -->
                <div class="card">
                    <div class="card-body" id="account_piechart" style="width: 500px; height: 500px;">
                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                    <script type="text/javascript">
                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {

                        var data = google.visualization.arrayToDataTable([
                        ['User Account Status', 'Number of User Account Status'],
                        <?php
                            $account_status_query = "SELECT status, COUNT(*) as count FROM users GROUP BY status";
                            $account_status_query_run = mysqli_query($conn, $account_status_query);
                            while($row = mysqli_fetch_assoc($account_status_query_run)) {
                                $account_status = '';
                                switch($row['status']) {
                                    case '0':
                                        $account_status = 'Inactive User Accounts';
                                        break;
                                    case '1':
                                        $account_status = 'Active User Accounts';
                                        break;
                                    default:
                                        $status = 'Unknown';
                                        break;
                                }
                                echo "['".$account_status."', ".$row['count']."],";
                            }
                        ?>
                        ]);

                        var options = {
                        title: 'User Account Statistics',
                        //is3d: true
                        };

                    var chart = new google.visualization.PieChart(document.getElementById('account_piechart'));
                    chart.draw(data, options);
                }
                </script>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /.container-fluid -->
</section>
    
</div>


<?php
    include('includes/admin-footer.php');
?>
