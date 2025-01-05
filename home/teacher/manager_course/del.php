<?php
$mamon = $_GET['mamon'];
include_once "../connectdb.php";
$sql = "DELETE from monhoc where mamon='$mamon'";
$kq = mysqli_query($con, $sql);
if ($kq) {
    echo "<script> alert('Xoá thành công!'); window.location.href = '../teacher_listgv.php'; </script>";
}
?>