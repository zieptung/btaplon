<?php
$ma = $_GET['ma'];
include_once "../connectdb.php";

$sql3 = "SELECT * from user Where ma='$ma'";
$data = mysqli_query($con, $sql3);
if (isset($_POST['btnLuu'])) {
    $ma = $_POST['txtma'];
    $ht = $_POST['txthoten'];
    $em = $_POST['txtemail'];
    $pa = $_POST['txtpassword'];
    $sql1 = "UPDATE user SET hoten='$ht', email='$em', password ='$pa' WHERE ma='$ma'";
    $sql2 = "UPDATE sinh_vien SET hoten='$ht', email='$em' WHERE ma='$ma'";

    $kq1 = mysqli_query($con, $sql1);
    $kq2 = mysqli_query($con, $sql2);
    if ($kq1 && $kq2) {
        echo "<script>alert('Sửa thành công!'); window.location.href='admin_control.php';</script>";
    }
}
if (isset($_POST['btnBack'])) {
    header("location: admin_control.php");
}
?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="../teacher/teacher_homepage.css">
<link rel="stylesheet" href="../teacher/teacher_info.css">
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
                <a href="admin_dashboard.php" class="logo">
                    <span class="icon"><i class="fa-solid fa-house"></i></span>
                    <span class="text">Trang chủ</span>
                </a>
            </li>
            <li>
                <a href="admin_control.php">
                    <span class="icon"><i class="fa-solid fa-table"></i></span>
                    <span class="text">Quản lý người dùng</span>
                </a>
            </li>
            <li>
                <a href="admin_logout.php">
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
                <?php
                if (isset($data) && mysqli_num_rows($data) > 0) {
                    while ($r = mysqli_fetch_array($data)) {
                        ?>
                        <div class="input-group" style="margin-bottom: 20px;">
                            <i class="fa-solid fa-arrow-right"></i>
                            <div class="form-field">
                                <label>Mã sinh viên</label>
                                <input class="info1" type="text" value="<?php echo $r['ma']; ?>" placeholder="Mã sinh viên"
                                    disabled>
                                <input type="hidden" name="txtma" value="<?php echo $r['ma']; ?>">
                            </div>
                        </div>
                        <div class="input-group" style="margin-bottom: 20px;">
                            <i class="fa-solid fa-arrow-right"></i>
                            <div class="form-field">
                                <label>Tên sinh viên</label>
                                <input class="info1" type="text" name="txthoten" value="<?php echo $r['hoten']; ?>"
                                    placeholder="Họ tên">
                            </div>
                        </div>
                        <div class="input-group" style="margin-bottom: 20px;">
                            <i class="fa-solid fa-arrow-right"></i>
                            <div class="form-field">
                                <label>Tên lớp</label>
                                <input class="info1" type="text" value="<?php echo $r['tenlop']; ?>" placeholder="Tên lớp"
                                    disabled>
                                <input type="hidden" name="txttenlop" value="<?php echo $r['tenlop']; ?>">
                            </div>
                        </div>
                        <div class="input-group" style="margin-bottom: 20px;">
                            <i class="fa-solid fa-arrow-right"></i>
                            <div class="form-field">
                                <label>Email</label>
                                <input class="info1" type="text" name="txtemail" value="<?php echo $r['email']; ?>"
                                    placeholder="Email">
                            </div>
                        </div>
                        <div class="input-group" style="margin-bottom: 20px;">
                            <i class="fa-solid fa-arrow-right"></i>
                            <div class="form-field">
                                <label>Mật khẩu</label>
                                <input class="info1" type="text" name="txtpassword" value="<?php echo $r['password']; ?>"
                                    placeholder="Email">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success" name="btnLuu"
                            style="margin-left:300px; margin-top:10px">Lưu</button>
                        <button type="submit" class="btn btn-info" name="btnBack" style="margin-left:150px; margin-top:10px">Trở
                            về</button>
                        <?php
                    }
                }
                ?>
    </article>
</body>

</html>