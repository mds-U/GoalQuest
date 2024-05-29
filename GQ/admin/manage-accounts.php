<?php

session_start();


$conn = require_once __DIR__ . '/db_con.php';

if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $user = $result->fetch_assoc();
    $stmt->close();

} else {
    header('Location: login.php');
    exit;
}

$page_title = "Manage Accounts || Goal Quest";
include(__DIR__ . '/includes/admin-header.php');
include(__DIR__ . '/includes/admin-topbar.php');
include(__DIR__ . '/includes/admin-sidebar.php');

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Manage Accounts</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="admin-dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Manage Accounts</li>
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
                    <div class="card-header">
                        <?php if ($_SESSION['user_id'] != 3): ?>
                            <div class="card-tools">
                                <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="admin-side/add-account.php"><i class="fa fa-plus"></i>Add New Account</a>
                            </div>
                        <?php endif; ?>
                    </div>
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
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = "SELECT * FROM users";
                                        $result = $conn->query($sql);
                                        $i = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>$i</td>";
                                            echo "<td>{$row['name']}</td>";
                                            echo "<td>{$row['role']}</td>";
                                            $status = $row['status'] == 1 ? 'Active' : 'Inactive';
                                            echo "<td>$status</td>";
                                            echo '<td>
                                                        <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="admin-side/edit_acc.php?id=' . $row['id'] . '">Edit</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item delete_account" href="admin-side/delete_acc.php?id=' . $row['id'] . '">Delete</a>
                                                        </div>
                                                    </td>';
                                            echo "</tr>";
                                            $i++;
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
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<style>
    body, h1, h2, h3, h4, h5, h6, p, a, span, button, input, select, option, table {
    font-family: 'Poppins', sans-serif;
}
</style>

<?php
include(__DIR__ . '/includes/admin-footer.php');  
?>
