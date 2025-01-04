<?php
$ma = $_GET['ma'];
include_once "../connectdb.php";
$sql = "DELETE from user where ma='$ma'";
$kq = mysqli_query($con, $sql);
if ($kq) {
    echo "<script> alert('Xoá thành công!'); window.location.href = 'teacher_class.php'; </script>";
}
?>