<?php
include_once "connectdb.php";
session_start();
$ma = '';
$hoten = '';
$ns = '';
$diachi = '';
$sdt = '';
$email = '';
$gioitinh = '';
$tenlop = '';
$khoahoc = '';

if (isset($_SESSION['user_id'])) { // Kiểm tra xem biến 'user_id' có tồn tại trong session hay không
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM sinh_vien WHERE ma = '$user_id'";
    $result = mysqli_query($con, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $ma = $row['ma'];
        $hoten = $row['hoten'];
        $ns = $row['ns'];
        $diachi = $row['diachi'];
        $sdt = $row['sdt'];
        $email = $row['email'];
        $gioitinh = $row['gioitinh'];
        $tenlop = $row['tenlop'];
        $khoahoc = $row['khoahoc'];
    }
}

$sql = "SELECT sv.*, lh.tenlop FROM sinh_vien sv JOIN lop_hoc lh ON sv.tenlop = lh.tenlop WHERE sv.ma = '$user_id'";
$result = mysqli_query($con, $sql);
if ($row = mysqli_fetch_assoc($result)) {
    $ma = $row['ma'];
    $hoten = $row['hoten'];
    $ns = $row['ns'];
    $diachi = $row['diachi'];
    $sdt = $row['sdt'];
    $email = $row['email'];
    $gioitinh = $row['gioitinh'];
    $tenlop = $row['tenlop'];
    $khoahoc = $row['khoahoc'];
}
?>

<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
<link rel="stylesheet" href="student_info.css">
<link rel="stylesheet" href="student_homepage.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<title>Quản lý điểm sinh viên đại học</title>
<style>
    .sidebar {
        overflow: hidden;
    }
</style>

<body>
    <!-- header -->
    <div class="header">
        <span class="header-text">Thông tin sinh viên</span>
        <span class="header-icon"><i class="fa-solid fa-circle-user"></i></span>
        <?php
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
    <!-- content -->
    <article class="content">
        <div class="container">
            <form method="POST" action="">
                <div class="form-row" style="margin-top: 10px;">
                    <div class="input-group">
                        <i class="fas fa-id-card"></i>
                        <div class="form-field">
                            <label class="label">Mã sinh viên</label>
                            <input class="info1" type="text" name="txtma" value="<?php echo $ma; ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group">
                        <i class="fas fa-id-card"></i>
                        <div class="form-field">
                            <label class="label">Họ và tên</label>
                            <input class="info1" type="text" name="txthoten" value="<?php echo $hoten; ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <div class="form-field">
                            <label class="label">Lớp</label>

                            <input class="info1" type="email" name="txttenlop" value="<?php echo $tenlop; ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group">
                        <i class="fas fa-calendar"></i>
                        <div class="form-field">
                            <label class="label">Ngày sinh</label>
                            <input class="info1" type="date" name="txtns" value="<?php echo $ns; ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group">
                        <i class="fas fa-venus-mars"></i>
                        <div class="form-field">
                            <label class="label">Giới tính</label>
                            <input class="info1" type="text" name="txtgioitinh" value="<?php echo $gioitinh; ?>"
                                disabled>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group full-width">
                        <i class="fas fa-home"></i>
                        <div class="form-field">
                            <label class="label">Địa chỉ</label>
                            <input class="info1" type="text" name="txtdiachi" value="<?php echo $diachi; ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group">
                        <i class="fas fa-phone"></i>
                        <div class="form-field">
                            <label class="label">Số điện thoại</label>
                            <input class="info1" type="tel" name="txtsdt" value="<?php echo $sdt; ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <div class="form-field">
                            <label class="label">Email</label>
                            <input class="info1" type="email" name="txtemail" value="<?php echo $email; ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <div class="form-field">
                            <label class="label">Niên khóa</label>
                            <input class="info1" type="email" name="txtkhoahoc" value="<?php echo $khoahoc; ?>"
                                disabled>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </article>
    <!-- sidebar -->
    <div class="sidebar">
        <ul>
            <li>
                <a href="student_homepage.php" class="logo">
                    <span class="icon"><i class="fa-solid fa-house"></i></span>
                    <span class="text">Trang chủ</span>
                </a>
            </li>
            <li>
                <a href="student_message.php">
                    <span class="icon"><i class="fa-solid fa-envelope"></i></span>
                    <span class="text">Tin nhắn</span>
                </a>
            </li>
            <li>
                <a href="student_forum.php">
                    <span class="icon"><i class="fa-solid fa-bell"></i></span>
                    <span class="text">Diễn đàn</span>
                </a>
            </li>
            <li>
                <a href="student_board.php">
                    <span class="icon"><i class="fa-solid fa-table"></i></span>
                    <span class="text">Bảng điểm sinh viên</span>
                </a>
            </li>
            <li>
                <a href="student_info.php">
                    <span class="icon"><i class="fa-solid fa-user"></i></span>
                    <span class="text">Thông tin sinh viên</span>
                </a>
            </li>
            <li>
                <a href="student_logout.php">
                    <span class="icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                    <span class="text">Đăng xuất</span>
                </a>
            </li>
        </ul>
    </div>
</body>

</html>

</html>