<?php if (isset($_SESSION['Hoten']))

    unset($_SESSION['Hoten']);
header('location: ../login.php');
?>