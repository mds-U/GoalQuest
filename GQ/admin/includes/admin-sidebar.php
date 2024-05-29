<?php
//Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//Check if user data exists in the session
if(isset($_SESSION['user'])) {
    $user = $_SESSION['admin'];
} else {
    // Redirect to login page or handle the scenario where user data is not available
    //For example:
    //header("Location: login.php");
    //exit();
}

//Define a function to check if the current URL matches the link's href attribute
function isActive($path, $href) {
    //If the current URL path matches the link's href attribute
    return $path === $href ? 'active' : '';
}

//Set default image path
$default_image_url = "assets/dist/img/noprofile.jpg";

//Construct user image URL
if(isset($user['image']) && !empty($user['image'])) {
    $user_image_filename = htmlspecialchars($user['image']);
    $user_image_path = 'assets/dist/img/' . $user_image_filename;
    $user_image_url = file_exists($user_image_path) ? $user_image_path : $default_image_url;
} else {
    $user_image_url = $default_image_url;
}
?>

 <!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="admin-dashboard.php" class="brand-link">
      <img src="assets/dist/img/GQ-logo.png" alt="AdminLTE Logo" class="brand-image img-circle" style="opacity: .8">
      <span class="brand-text font-weight-light">GoalQuest</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo $user_image_url; ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">
            <?= isset($user['name']) ? htmlspecialchars($user['name']) : 'Guest' ?>
          </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="admin-dashboard.php" class="nav-link <?= isActive(basename($_SERVER['PHP_SELF']), 'admin-dashboard.php') ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="manage-accounts.php" class="nav-link <?= isActive(basename($_SERVER['PHP_SELF']), 'manage-accounts.php') ?>">
              <i class="fas fa-tasks nav-icon"></i>
              <p>
                Manage Accounts
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="manage-accounts.php" class="nav-link" class="nav-link <?= isActive(basename($_SERVER['PHP_SELF']), 'manage-accounts.php') ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Accounts</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="admin-active_accounts.php" class="nav-link <?= isActive(basename($_SERVER['PHP_SELF']), 'admin-active_accounts.php') ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Total Active Accounts</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="admin-inactive_accounts.php" class="nav-link <?= isActive(basename($_SERVER['PHP_SELF']), 'admin-inactive_accounts.php') ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Total Inactive Accounts</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-header">SETTINGS</li>
          <li class="nav-item">
            <a href="admin-profile.php" class="nav-link <?= isActive(basename($_SERVER['PHP_SELF']), 'admin-profile.php') ?>">
              <i class="nav-icon fas fa-ellipsis-h"></i>
              <p>Profile</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="logout.php" class="nav-link" name="logout-web">
              <i class="nav-icon fas fa-file"></i>
              <p>Logout</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<script>
    $(document).ready(function () {
        //Get the current URL path and split it
        var path = window.location.pathname.split("/").pop();

        //Get the navigation link elements
        var navLinks = $('.nav-item a');

        //Loop through each navigation link
        navLinks.each(function () {
            //Get the href attribute of the link
            var href = $(this).attr('href');

            //If the current URL path matches the link's href attribute
            if (path === href) {
                //Add the 'active' class to the parent nav-item
                $(this).parents('.nav-item').addClass('menu-open');
                $(this).addClass('active');
            }
        });
    });
</script>
