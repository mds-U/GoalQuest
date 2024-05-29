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

// Initialize $where variable
$where = "";

$page_title = "Report || Goal Quest";
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
                  <h1 class="m-0">Report</h1>
              </div><!-- /.col -->
              <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                      <li class="breadcrumb-item active">Report</li>
                  </ol>
              </div><!-- /.col -->
          </div><!-- /.row -->
      </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
      <div class="container-fluid">

        <div class="col-md-12">
          <div class="card card-outline card-success">
            <div class="card-header">
              <b>Project Progress</b>
              <div class="card-tools">
                <button class="btn btn-flat btn-sm bg-gradient-success btn-success" id="print"><i class="fa fa-print"></i> Print</button>
              </div>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive" id="printable">
                <table class="table m-0 table-bordered" id="myTable">
                <!--  <colgroup>
                    <col width="5%">
                    <col width="30%">
                    <col width="35%">
                    <col width="15%">
                    <col width="15%">
                  </colgroup> -->
                  <thead>
                    <th>#</th>
                    <th>Project</th>
                    <th>Task</th>
                    <th>Completed Task</th>
                    <th>Progress</th>
                    <th>Status</th>
                  </thead>
                  <tbody>
                    <?php
                      $i = 1;
                      $stat = array("Pending", "On-Progress", "Done" , "Over Due");
                      $where = "";
                      $qry = $conn->query("SELECT * FROM project_list WHERE user_id = {$_SESSION["user_id"]} ORDER BY name");
                      while($row= $qry->fetch_assoc()):
                      $tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']}")->num_rows;
                      $cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['id']} and status = 2")->num_rows;
                      $prog = $tprog > 0 ? ($cprog/$tprog) * 100 : 0;
                      $prog = $prog > 0 ?  number_format($prog,2) : $prog;
                    ?>
                      <tr>
                          <td>
                            <?php echo $i++ ?>
                          </td>
                          <td>
                            <a>
                              <?php echo ucwords($row['name']) ?>
                            </a>
                            <br>
                            <small>
                              Due: <?php echo date("Y-m-d",strtotime($row['end_date'])) ?>
                            </small>
                          </td>
                          <td class="text-center">
                            <?php echo number_format($tprog) ?>
                          </td>
                          <td class="text-center">
                            <?php echo number_format($cprog) ?>
                          </td>
                          <td class="project_progress">
                              <div class="progress progress-sm">
                                <div class="progress-bar bg-green" role="progressbar" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $prog ?>%">
                                </div>
                              </div>
                              <small>
                                  <?php echo $prog ?>% Complete
                              </small>
                          </td>
                          <td class="project-state">
                            <?php
                              if($stat[$row['status']] =='Pending'){
                                echo "<span class='badge badge-secondary'>{$stat[$row['status']]}</span>";
                              }elseif($stat[$row['status']] =='On-Progress'){
                                echo "<span class='badge badge-info'>{$stat[$row['status']]}</span>";
                              }elseif($stat[$row['status']] =='Done'){
                                echo "<span class='badge badge-success'>{$stat[$row['status']]}</span>";
                              }elseif($stat[$row['status']] =='Over Due'){
                                  echo "<span class='badge badge-danger'>{$stat[$row['status']]}</span>";
                              }
                            ?>
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
  $('#print').click(function(){
    var _h = $('head').clone();
    var _p = $('#printable').clone();
    var _d = "<p class='text-center'><b>Project Progress Report as of (<?php echo date('F d, Y') ?>)</b></p>";
    _p.prepend(_d);
    
    var nw = window.open("", "", "width=900,height=600");
    nw.document.write("<html><head>" + _h.html() + "</head><body>" + _p.html() + "</body></html>");
    nw.document.close();
    
    nw.print();
    setTimeout(function(){
      nw.close();
    }, 750);
  });
</script>

<style>
    body, input, select, textarea, button, .card-header, .card-body, .card-footer, .breadcrumb-item, .btn, .dropdown-menu, .dropdown-item, .badge, h1, h2, h3, h4, h5, h6, p, a, li, table {
      font-family: 'Poppins', sans-serif !important;
    }
</style>

<?php 
  include('includes/footer.php');
?>

