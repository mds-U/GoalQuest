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

$page_title = "All Tasks || Goal Quest";
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
          <h1 class="m-0">All Tasks</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
            <li class="breadcrumb-item active">All Tasks</li>
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
          <div class="card-body">
            <table class="table table-hover table-condensed" id="myTable">
              <colgroup>
                <col width="5%">
                <col width="15%">
                <col width="20%">
                <col width="15%">
                <col width="15%">
                <col width="15%">
                <col width="15%">
                <col width="12%">
              </colgroup>
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th>Project</th>
                  <th>Task</th>
                  <th>Project Started</th>
                  <th>Project Due Date</th>
                  <th>Task Due Date</th>
                  <th>Project Status</th>
                  <th>Task Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $i = 1;
                  $stat = array("Pending", "On-Progress", "Done", "Overdue");

                  $qry = $conn->query("SELECT t.*, p.name as pname, p.start_date, p.status as pstatus, p.end_date, p.id as pid 
                                        FROM task_list t 
                                        INNER JOIN project_list p ON p.id = t.project_id 
                                        WHERE p.user_id = {$_SESSION['user_id']}
                                        ORDER BY p.name ASC");

while($row = $qry->fetch_assoc()):
  $trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
  unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
  $desc = strtr(html_entity_decode($row['description']), $trans);
  $desc = str_replace(array("<li>", "</li>"), array("", ", "), $desc);
  $tprog = $conn->query("SELECT * FROM task_list WHERE project_id = {$row['pid']}")->num_rows;
  $cprog = $conn->query("SELECT * FROM task_list WHERE project_id = {$row['pid']} AND status = 4")->num_rows;
  $prog = $tprog > 0 ? ($cprog / $tprog) * 100 : 0;
  $prog = $prog > 0 ? number_format($prog, 2) : $prog;
  $prod = $conn->query("SELECT * FROM task_list WHERE project_id = {$row['pid']}")->num_rows;

  if ($row['pstatus'] == 0) {  //if status is 'Pending'
      if (strtotime(date('Y-m-d')) >= strtotime($row['start_date'])) {
          //if the project has started
          if ($prod > 0 || $cprog > 0) {
              $row['pstatus'] = 1;  //set status to 'On-Progress' if there are tasks or completed tasks
          } else {
              $row['pstatus'] = 2;  //set status to 'Done' if no tasks are pending or in progress
          }
      }
      if (strtotime(date('Y-m-d')) > strtotime($row['end_date'])) {
          // If the current date is past the end date
          $row['pstatus'] = 3;  //set status to 'Overdue'
      }
  }
?>
<tr>
<td class="text-center"><?php echo $i++ ?></td>
<td>
  <p><b><?php echo ucwords($row['pname']) ?></b></p>
</td>
<td>
  <p><b><?php echo ucwords($row['task']) ?></b></p>
  <p class="truncate"><?php echo strip_tags($desc) ?></p>
</td>
<td><b><?php echo date("M d, Y", strtotime($row['start_date'])) ?></b></td>
<td><b><?php echo date("M d, Y", strtotime($row['end_date'])) ?></b></td>
<td><b><?php echo date("M d, Y", strtotime($row['due_date'])) ?></b></td> <!-- Add this line -->
<td class="text-center">
  <?php
      if ($stat[$row['pstatus']] == 'Pending') {
          echo "<span class='badge badge-secondary'>{$stat[$row['pstatus']]}</span>";
      } elseif ($stat[$row['pstatus']] == 'On-Progress') {
          echo "<span class='badge badge-warning'>{$stat[$row['pstatus']]}</span>";
      } elseif ($stat[$row['pstatus']] == 'Done') {
          echo "<span class='badge badge-success'>{$stat[$row['pstatus']]}</span>";
      } elseif ($stat[$row['pstatus']] == 'Overdue') {
          echo "<span class='badge badge-danger'>{$stat[$row['pstatus']]}</span>";
      }
  ?>
</td>
<td>
  <?php 
      if ($row['status'] == 0) {
          echo "<span class='badge badge-secondary'>Pending</span>";
      } elseif ($row['status'] == 1) {
          echo "<span class='badge badge-warning'>On-Progress</span>";
      } elseif ($row['status'] == 2) {
          echo "<span class='badge badge-success'>Done</span>";
      } elseif ($row['status'] == 3) {
          echo "<span class='badge badge-danger'>Overdue</span>";
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

    </div><!-- /.container-fluid -->
  </section>

</div>

<style>
    body, input, select, textarea, button, .card-header, .card-body, .card-footer, .breadcrumb-item, .btn, .dropdown-menu, .dropdown-item, .badge {
        font-family: 'Poppins', sans-serif !important;
    }
</style>

<?php
include('includes/footer.php');
?>
