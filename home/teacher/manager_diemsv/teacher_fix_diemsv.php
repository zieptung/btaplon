<?php
$mm = $_GET['mamon'];
include_once "../connectdb.php";
$ht = '';
$tm = '';
$stc = '';
$ds = '';
$dc = '';
$cc = '';
$gk = '';
$ck = '';

$sql1 = "SELECT * from diem Where mamon='$mm'";
$data = mysqli_query($con, $sql1);

$sql_mon_hoc = "SELECT mamon FROM mon_hoc";
$mon_hoc = mysqli_query($con, $sql_mon_hoc);
if (isset($_POST["btnLuu"])) {
    $ht = $_POST['txthoten'];
    $tm = $_POST['txttenmon'];
    $stc = $_POST['txtstc'];
    $ds = $_POST['txtdiemso'];
    $dc = $_POST['txtdiemchu'];
    $cc = $_POST['txtdiemcc'];
    $gk = $_POST['txtdiemgk'];
    $ck = $_POST['txtdiemck'];
    $sql = "UPDATE diem SET hoten='$ht', tenmon='$tm', sotinchi='$stc', diemso='$ds', diemchu='$dc', diemcc='$cc', diemgk='$gk', diemck='$ck' WHERE mamon='$mm'";

    $kq = mysqli_query($con, $sql);
    if ($kq) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href='../teacher_board.php';</script>";
    }
}

?>

<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="../teacher_homepage.css">
<title>Quản lý điểm sinh viên đại học</title>

<body>
    <!-- header -->
    <div class="header">
        <span class="header-text">Thêm điểm sinh viên</span>
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
                    <span class="text">Thông tin quản lý</span>
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
                <a href="../teacher_add.php">
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
                <a href="../teacher_logout.php">
                    <span class="icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                    <span class="text">Đăng xuất</span>
                </a>
            </li>
        </ul>
    </div>
    <article class="content">
        <form method="POST">
            <?php
            if (isset($data) && mysqli_num_rows($data) > 0) {
                while ($r = mysqli_fetch_array($data)) {
                    ?>
                    <div class="form-group" style="width: 75%; margin-left: 150px; margin-top: 50px; margin-bottom: 10px;">
                        <label>Mã học phần</label>
                        <input type="text" class="form-control" placeholder="Tên sinh viên" name="txthoten"
                            value="<?php echo $r['mamon'] ?>" style="margin-bottom: 20px;" readonly>
                        <label>Tên sinh viên</label>
                        <input type="text" class="form-control" placeholder="Tên sinh viên" name="txthoten"
                            value="<?php echo $r['hoten'] ?>" style="margin-bottom: 20px;">
                        <label>Tên học phần</label>
                        <input type="text" class="form-control" placeholder="Tên học phần" name="txttenmon"
                            value="<?php echo $r['tenmon'] ?>" style="margin-bottom: 20px;" readonly>
                        <label>Số tín chỉ</label>
                        <input type="text" class="form-control" placeholder="Số tín chỉ" name="txtstc"
                            value="<?php echo $r['sotinchi'] ?>" style="margin-bottom: 20px;" readonly>
                        <label>Điểm số</label>
                        <input type="numeber" class="form-control" placeholder="Điểm số" name="txtdiemso"
                            value="<?php echo $r['diemso'] ?>" style="margin-bottom: 20px;">
                        <label>Điểm chữ</label>
                        <input type="text" class="form-control" placeholder="Điểm chữ" name="txtdiemchu"
                            value="<?php echo $r['diemchu'] ?>" style="margin-bottom: 20px;">
                        <label>Điểm chuyên cần</label>
                        <input type="number" class="form-control" placeholder="Điểm chuyên cần" name="txtdiemcc"
                            value="<?php echo $r['diemcc'] ?>" style="margin-bottom: 20px;">
                        <label>Điểm giữa kỳ</label>
                        <input type="number" class="form-control" placeholder="Điểm giữa kỳ" name="txtdiemgk"
                            value="<?php echo $r['diemgk'] ?>" style="margin-bottom: 20px;">
                        <label>Điểm cuối kỳ</label>
                        <input type="number" class="form-control" placeholder="Điểm cuối kỳ" name="txtdiemck"
                            value="<?php echo $r['diemck'] ?>" style="margin-bottom: 20px;">
                        <button type="submit" class="btn btn-primary" name="btnLuu"
                            style="margin-left: 38%; margin-top: 10px; width: 200px">Lưu</button>
                        <button type="submit" class="btn btn-primary" name="btnBack"
                            style="margin-left:150px; margin-top:10px">Trở
                            về</button>
                    </div>
                    <?php
                }
            }
            ?>
        </form>
    </article>
</body>

</html>