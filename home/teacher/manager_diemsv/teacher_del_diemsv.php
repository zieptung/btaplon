<?php
$mm = $_GET['mamon'];
include_once "../connectdb.php";
$sql = "DELETE from diem where mamon='$mm'";
$kq = mysqli_query($con, $sql);
if ($kq) {
    echo "<script> alert('Xoá thành công!'); window.location.href = '../teacher_board.php'; </script>";
}
?>