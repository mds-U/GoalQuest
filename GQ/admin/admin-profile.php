<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header('Location: login.php');
    exit;
}

$conn = require __DIR__ . "/db_con.php";
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

ob_start();

$page_title = "Profile || Goal Quest";
include('includes/admin-header.php');
include('includes/admin-topbar.php');
include('includes/admin-sidebar.php');

//Alert messages
$alert_message = '';
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $alert_message = '<script>
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: "Profile updated successfully.",
                            timer: 3000,
                            showConfirmButton: false
                        });
                      </script>';
} elseif (isset($_GET['error']) && $_GET['error'] == 1) {
    $alert_message = '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "Error updating profile. Please try again.",
                            timer: 3000,
                            showConfirmButton: false
                        });
                      </script>';
}

//Image upload
if(isset($_FILES["fileImg"]["name"])){
  $id = $_POST["id"];
  $src = $_FILES["fileImg"]["tmp_name"];
  $imageName = uniqid() . $_FILES["fileImg"]["name"];
  $target = "assets/dist/img/" . $imageName;
  if(move_uploaded_file($src, $target)){
      $query = "UPDATE users SET image = '$imageName' WHERE id = $id";
      mysqli_query($conn, $query);
      // Set the image source directly
      $user['image'] = $imageName;
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    //Update the user data in the database
    $sql = "UPDATE users SET name=?, email=?";
    $params = array($name, $email);
    
    $sql .= " WHERE id=?";
    $params[] = $id;

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error: " . $conn->error);
    }

    $stmt->bind_param(str_repeat("s", count($params)), ...$params);
    $result = $stmt->execute();
    $stmt->close();

    if ($result) {
        header("Location: admin-profile.php?success=1");
        exit();
    } else {
        header("Location: admin-profile.php?error=1");
        exit();
    }
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Profile</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Profile</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card card-primary">
          <div class="card-body">
            <!-- Alert messages -->
            <?php echo $alert_message; ?>
            <form action="" method="post" id="manage-user" enctype="multipart/form-data">  
            <input type="hidden" name="id" value="<?php echo isset($user['id']) ? $user['id']: '' ?>">

              <!-- Profile Pic -->
              <div class="upload">
              <img src="<?php echo 'assets/dist/img/' . $user['image']; ?>" id="image">

                <div class="rightRound" id = "upload">
                  <input type="file" name="fileImg" id = "fileImg" accept=".jpg, .jpeg, .png">
                  <i class = "fa fa-camera"></i>
                </div>

                <div class="leftRound" id = "cancel" style = "display: none;">
                  <i class = "fa fa-times"></i>
                </div>
                <div class="rightRound" id = "confirm" style = "display: none;">
                  <input type="submit">
                  <i class = "fa fa-check"></i>
                </div>
              </div>
              <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="fname" class="form-control" value="<?php echo isset($user['name']) ? $user['name']: '' ?>" required  autocomplete="off">

              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" class="form-control" value="<?php echo isset($user['email']) ? $user['email']: '' ?>" required  autocomplete="off">
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-success">Update Profile Changes</button>
              </div>
              <div class="modal-footer">
                <a href="admin_change_password.php" class="btn btn-primary">Change Password</a>
              </div>
            </form>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>  
</div>

<style>
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>

<!-- IMAGE -->
<script type="text/javascript">
      document.getElementById("fileImg").onchange = function(){
        document.getElementById("image").src = URL.createObjectURL(fileImg.files[0]); 

        document.getElementById("cancel").style.display = "block";
        document.getElementById("confirm").style.display = "block";

        document.getElementById("upload").style.display = "none";
      }

      var userImage = document.getElementById('image').src;
      document.getElementById("cancel").onclick = function(){
        document.getElementById("image").src = userImage; 

        document.getElementById("cancel").style.display = "none";
        document.getElementById("confirm").style.display = "none";

        document.getElementById("upload").style.display = "block";
      }
</script>

<style media="screen">
    .upload{
      width: 140px;
      position: relative;
      margin: auto;
      text-align: center;
    }
    .upload img{
      border-radius: 50%;
      border: 8px solid #DCDCDC;
      width: 125px;
      height: 125px;
    }
    .upload .rightRound{
      position: absolute;
      bottom: 0;
      right: 0;
      background: #00B4FF;
      width: 32px;
      height: 32px;
      line-height: 33px;
      text-align: center;
      border-radius: 50%;
      overflow: hidden;
      cursor: pointer;
    }
    .upload .leftRound{
      position: absolute;
      bottom: 0;
      left: 0;
      background: red;
      width: 32px;
      height: 32px;
      line-height: 33px;
      text-align: center;
      border-radius: 50%;
      overflow: hidden;
      cursor: pointer;
    }
    .upload .fa{
      color: white;
    }
    .upload input{
      position: absolute;
      transform: scale(2);
      opacity: 0;
    }
    .upload input::-webkit-file-upload-button, .upload input[type=submit]{
      cursor: pointer;
    }

    body, input, select, textarea, button, .card-header, .card-body, .card-footer, .breadcrumb-item, .btn, .dropdown-menu, .dropdown-item, .badge, h1, h2, h3, h4, h5, h6, p, a, li, table {
      font-family: 'Poppins', sans-serif !important;
    }
</style>

<?php
  include('includes/admin-footer.php');
?>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
