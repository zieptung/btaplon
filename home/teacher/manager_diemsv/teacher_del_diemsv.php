<?php
$msv = $_GET['ma'];
include_once "../connectdb.php";
$sql = "DELETE from diem where ma='$msv'";
$kq = mysqli_query($con, $sql);
if ($kq) {
    echo "<script> alert('Xoá thành công!'); window.location.href = '../teacher_board.php'; </script>";
}
?>