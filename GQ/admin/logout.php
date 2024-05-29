<?php

session_start();

if (isset($_POST['confirm_logout'])) {
    $_SESSION = array();
    session_destroy();

    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Confirmation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif !important;;
        }
        .swal2-popup {
            font-family: 'Poppins', sans-serif !important;
        }
        .swal2-styled.swal2-confirm {
            font-family: 'Poppins', sans-serif !important;
            background-color: red !important;
            border-color: red !important;
        }
        .swal2-styled.swal2-cancel {
            font-family: 'Poppins', sans-serif !important;
        }
    </style>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'swal2-popup',
                    confirmButton: 'swal2-styled swal2-confirm',
                    cancelButton: 'swal2-styled swal2-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                } else {
                    var currentUrl = document.referrer;
                    window.location.href = currentUrl;
                }
            });
        });
    </script>
    
    <form id="logoutForm" action="logout.php" method="post">
        <input type="hidden" name="confirm_logout" value="1">
    </form>
</body>
</html>
