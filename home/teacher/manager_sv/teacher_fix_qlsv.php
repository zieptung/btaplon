<?php
$ma = $_GET['ma'];
include_once "../connectdb.php";

$sql1 = "SELECT * from user Where ma='$ma'";
$data = mysqli_query($con, $sql1);
if (isset($_POST['btnLuu'])) {
    $ma = $_POST['txtma'];
    $ht = $_POST['txthoten'];
    $em = $_POST['txtemail'];
    $pa = $_POST['txtpassword'];

    $sql1 = "UPDATE user SET hoten='$ht', email='$em', password ='$pa' WHERE ma='$ma'";

    $kq = mysqli_query($con, $sql1);
    if ($kq) {
        echo "<script>alert('Sửa thành công!'); window.location.href='../teacher_infosv.php';</script>";
    }
}
if (isset($_POST['btnBack'])) {
    header("location: ../teacher_infosv.php");
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
                <a href="../teacher_message.php">
                    <span class="icon"><i class="fa-solid fa-envelope"></i></span>
                    <span class="text">Tin nhắn</span>
                </a>
            </li>
            <li>
                <a href="../teacher_infosv.php">
                    <span class="icon"><i class="fa-solid fa-circle-exclamation"></i></span>
                    <span class="text">Quản lý sinh viên</span>
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
                <a href="../teacher_listgv.php">
                    <span class="icon"><i class="fa-solid fa-list"></i></span>
                    <span class="text">Danh sách quản lý</span>
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