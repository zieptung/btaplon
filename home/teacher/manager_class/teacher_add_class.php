<?php
include_once "../connectdb.php";
$lop = "";
if (isset($_POST['btnLuu'])) {
    $lop = $_POST['txtlop'];
    $siso = $_POST['txtsiso'];
    $sql = "INSERT INTO lop_hoc (tenlop, siso) VALUES ('$lop', '$siso')";
    $kq = mysqli_query($con, $sql);
    if ($kq) {
        echo "<script> alert('Thêm lớp thành công!'); window.location.href='../manager_class/teacher_class.php'; </script>";
    }
}
if (isset($_POST['btnBack'])) {
    header("location:../manager_class/teacher_class.php");
}
?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="../teacher_info.css">
<link rel="stylesheet" href="../teacher_homepage.css">
<title>Quản lý điểm sinh viên đại học</title>

<body>
    <!-- header -->
    <div class="header">
        <span class="header-text">Quản lý sinh viên</span>
        <span class="header-icon"><i class="fa-solid fa-circle-user"></i></span>
        <?php
        session_start();
        include_once "../connectdb.php";
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $sql = "SELECT hoten FROM user WHERE ma = '$user_id'";
            $result = mysqli_query($con, $sql);
            if ($row = mysqli_fetch_assoc($result)) {
                echo "<span class='header-username'>" . $row['hoten'] . "</span>";
            }
        }
        ?>
    </div>
    <!-- sidebar -->
    <div class="sidebar">
        <ul>
            <li>
                <a href="../teacher_info.php">
                    <span class="icon"><i class="fa-solid fa-user"></i></span>
                    <span class="text">Thông tin cá nhân</span>
                </a>
            </li>
            <li>
                <a href="../manager_class/teacher_class.php">
                    <span class="icon"><i class="fa-solid fa-landmark"></i></span>
                    <span class="text">Quản lý lớp học</span>
                </a>
            </li>
            <li>
                <a href="../manager_course/teacher_course.php">
                    <span class="icon"><i class="fa-solid fa-pen-to-square"></i></span>
                    <span class="text">Quản lý môn học</span>
                </a>
            </li>
            <li>
                <a href="../teacher_fix.php">
                    <span class="icon"><i class="fa-solid fa-wrench"></i></span>
                    <span class="text">Thêm điểm sinh viên</span>
                </a>
            </li>
            <li>
                <a href="../teacher_board.php">
                    <span class="icon"><i class="fa-solid fa-table"></i></span>
                    <span class="text">Bảng điểm sinh viên</span>
                </a>
            </li>
            <li>
                <a href="../manager_scholarship/teacher_scholarship.php">
                    <span class="icon"><i class="fa-solid fa-user-graduate"></i></span>
                    <span class="text">Danh sách học bổng</span>
                </a>
            </li>
            <li>
                <a href="../teacher_logout.php">
                    <span class="icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                    <span class="text">Đăng xuất</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- content -->
    <article class="content">
        <form method="post" action="">
            <div class="form-group" style="width: 75%; margin-left: 150px; margin-top: 50px; margin-bottom: 10px;">
                <div class="input-group" style="margin-top: 20px;">
                    <i class="fa-solid fa-arrow-right"></i>
                    <div class="form-field">
                        <label>Tên lớp</label>
                        <input class="info1" type="text" name="txtlop" placeholder="Tên lớp">
                    </div>
                </div>
                <div class="input-group" style="margin-top: 20px;">
                    <i class="fa-solid fa-arrow-right"></i>
                    <div class="form-field">
                        <label>Sĩ số</label>
                        <input class="info1" type="text" name="txtsiso" placeholder="Sĩ số">
                    </div>
                </div>
                <button type="submit" class="btn btn-success" name="btnLuu"
                    style="margin-left:300px; margin-top:10px">Lưu</button>
                <button type="submit" class="btn btn-info" name="btnBack" style="margin-left:150px; margin-top:10px">Trở
                    về</button>
            </div>
        </form>
    </article>
</body>

</html>