<?php
$msv = $_GET['ma'];
include_once "../connectdb.php";

$sql = "DELETE FROM diem WHERE ma = '$msv' LIMIT 1";
$kq = mysqli_query($con, $sql);

if ($kq) {
    echo "<script> alert('Xoá thành công!'); window.location.href = '../teacher_board.php'; </script>";
}
$con->close();
?>