<?php

$conn = require_once __DIR__ . '/db_con.php';

session_start();
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

$page_title = "Active Users || Goal Quest";
include('includes/admin-header.php');
include('includes/admin-topbar.php');
include('includes/admin-sidebar.php');
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Inactive Accounts</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="admin-dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Inactive Accounts</li>
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
                                    <col width="30%">
                                    <col width="15%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                               </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $query = "SELECT * FROM users WHERE status = 0";
                                        $result = mysqli_query($conn, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . $row['name'] . "</td>";
                                            echo "<td>" . $row['email'] . "</td>";
                                            echo "<td>Inactive</td>";
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
    body, h1, h2, h3, h4, h5, h6, p, a, span, button, input, select, option, table, th, td, ol, li, .breadcrumb-item {
    font-family: 'Poppins', sans-serif;
}
</style>

<?php include('includes/admin-footer.php'); ?>