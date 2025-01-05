<?php
include_once "../connectdb.php";
$ma = $ht = $em = $pa = "";
if (isset($_POST['btnLuu'])) {
    $ma = $_POST['txtma'];
    $ht = $_POST['txthoten'];
    $em = $_POST['txtemail'];
    $pa = $_POST['txtpassword'];

    $check_sql = "SELECT * FROM user WHERE ma = '$ma'";
    $check_result = mysqli_query($con, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script> alert('Mã giảng viên đã tồn tại!'); window.location.href = 'admin_add_gv.php'; </script>";
    } else {
        $sql1 = "INSERT INTO user (ma, hoten, email, password, is_admin) VALUES ('$ma', '$ht', '$em', '$pa', 1)";
        $sql2 = "INSERT INTO giang_vien (ma, hoten, email) VALUES ('$ma', '$ht', '$em')";
        $kq = mysqli_query($con, $sql1);
        $kq1 = mysqli_query($con, $sql2);
        if ($kq && $kq1) {
            echo "<script> alert('Thêm thành công!'); window.location.href = 'admin_control.php'; </script>";
        }
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
<link rel="stylesheet" href="../teacher/teacher_info.css">
<link rel="stylesheet" href="../teacher/teacher_homepage.css">
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
                <div class="input-group" style="margin-top: 20px;">
                    <i class="fa-solid fa-arrow-right"></i>
                    <div class="form-field">
                        <label>Mã giảng viên</label>
                        <input class="info1" type="text" name="txtma" value="<?php echo $ma; ?>"
                            placeholder="Mã giảng viên">
                    </div>
                </div>
                <div class="input-group" style="margin-top: 20px;">
                    <i class="fa-solid fa-arrow-right"></i>
                    <div class="form-field">
                        <label>Họ tên</label>
                        <input class="info1" type="text" name="txthoten" value="<?php echo $ht; ?>"
                            placeholder="Họ tên">
                    </div>
                </div>
                <div class="input-group" style="margin-top: 20px;">
                    <i class="fa-solid fa-arrow-right"></i>
                    <div class="form-field">
                        <label>Email</label>
                        <input class="info1" type="text" name="txtemail" value="<?php echo $em; ?>" placeholder="Email">
                    </div>
                </div>
                <div class="input-group" style="margin-top: 20px;">
                    <i class="fa-solid fa-arrow-right"></i>
                    <div class="form-field">
                        <label>Mật khẩu</label>
                        <input class="info1" type="text" name="txtpassword" value="<?php echo $pa; ?>"
                            placeholder="Mật khẩu">
                    </div>
                </div>
                <button type="submit" class="btn btn-success" name="btnLuu"
                    style="margin-left:300px; margin-top:10px">Lưu</button>
                <button type="submit" class="btn btn-info" name="btnBack" style="margin-left:150px; margin-top:10px">Trở
                    về</button>
            </div>
    </article>
</body>

</html>