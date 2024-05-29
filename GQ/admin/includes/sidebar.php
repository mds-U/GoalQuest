<?php
//Set default image path
$default_image_url = "assets/dist/img/noprofile.jpg";
$user_image_filename = !empty($users['image']) ? htmlspecialchars($users['image']) : '';
$user_image_path = 'assets/dist/img/' . $user_image_filename;
$user_image_url = $user_image_path;

//Check if the user image file exists
if (empty($user_image_filename) || !file_exists($user_image_path)) {
    $user_image_url = $default_image_url;
}
?>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard.php" class="brand-link">
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
            <?= htmlspecialchars($users["name"]) ?>
          </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="dashboard.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-layer-group"></i>
              <p>
                My Projects
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="new_project.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="project_list.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Project List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="project_pending.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pending Projects</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="project_on_progress.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>On-Progress Projects</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="project_completed.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Completed Projects</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="project_overdue.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Overdue Projects</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="task_list.php" class="nav-link">
              <i class="fas fa-tasks nav-icon"></i>
              <p>
                All Tasks
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="reminder.php" class="nav-link">
              <i class="fas fa-bell nav-icon"></i>
              <p>
                Reminder
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="report.php" class="nav-link">
              <i class="fas fa-th-list nav-icon"></i>
              <p>
                Report
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="archive_tbl.php" class="nav-link">
              <i class="nav-icon fas fa-trash"></i>
              <p>
                Archive
              </p>
            </a>
          </li>
          <li class="nav-header">SETTINGS</li>
          <li class="nav-item">
            <a href="profile.php" class="nav-link">
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

<script>
    $(document).ready(function () {
        //When a navigation link is clicked
        $('.nav-item a').click(function () {
            //Remove the 'active' class from all navigation links
            $('.nav-item a').removeClass('active');
            //Add the 'active' class to the clicked link
            $(this).addClass('active');
        });
    });
</script>

<style>
.image-container {
    width: 35px;
    height: 35px;
    overflow: hidden;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.user-image {
    width: 100%;
    height: auto;
}

.sidebar-collapsed .user-panel .image-container {
    width: 25px; /* Adjust the size for collapsed state if necessary */
    height: 25px;
}
</style>
