<?php if (isset($_SESSION['hoten']))

    unset($_SESSION['hoten']);
header('location: ../login.php');
?>