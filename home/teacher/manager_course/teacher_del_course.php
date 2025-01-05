<?php
$mm = $_GET['mamon'];
include_once "../connectdb.php";
$sql = "DELETE from mon_hoc where mamon='$mm'";
$kq = mysqli_query($con, $sql);
if ($kq) {
    echo "<script> alert('Xoá thành công!'); window.location.href = './teacher_course.php'; </script>";
}
?>