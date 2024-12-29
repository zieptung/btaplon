<?php
$mm = $_GET['mamon'];
include_once('connectdb.php');
$sql = "delete from mon_hoc where mamon='$mm'";
$kq= mysqli_query($con, $sql);
if($kq)
    header('location:teacher_board.php');
else
    echo "<script>alert('Xóa thất bại')</script>";
?>