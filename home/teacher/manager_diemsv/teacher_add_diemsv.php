<?php
include_once "connectdb.php";
$mm = '';
$tm = '';
$stc = '';
$ds = '';
$dc = '';
$loai = '';
$cc = '';
$gk = '';
$ck = '';

$sql = "SELECT * from mon_hoc";
$monhoc = mysqli_query($con, $sql);

if (isset($_POST["btnsubmit"])) {
    $mm = $_POST['txtmamon'];
    $ht = $_POST['txthoten'];
    $tm = $_POST['txttenmon'];
    $stc = $_POST['txtsotinchi'];
    $ds = $_POST['txtdiemso'];
    $dc = $_POST['txtdiemchu'];
    $loai = $_POST['txtloai'];
    $cc = $_POST['txtdiemcc'];
    $gk = $_POST['txtdiemgk'];
    $ck = $_POST['txtdiemck'];
    // Câu lệnh INSERT để thêm dữ liệu vào bảng monhoc
    $sql = "INSERT INTO mon_hoc (mamon, tenmon, sotinchi, bomon, diemso, diemchu, loai, diemcc, diemgk, diemck)
    VALUES ('$mm', '$tm', '$stc', '$bm', '$ds', '$dc', '$loai', '$cc', '$gk', '$ck')";
    //thuc thi cau lenh
    $kq = mysqli_query($con, $sql);
    if ($kq)
        echo "<script>alert('Thêm mới thành công <3')</script>";
    else
        echo "<script>alert('Thêm mới thất bại :<')</script>";
    //chuyen ve trang danh sach sinh vien
}
if (isset($_POST["btnback"])) {
    header('location:teacher_board.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
<link rel="stylesheet" href="teacher_homepage.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<title>Quản lý điểm sinh viên đại học</title>

<body>
    <!-- header -->
    <div class="header">
        <span class="header-text">Bảng điểm sinh viên</span>
        <span class="header-icon"><i class="fa-solid fa-circle-user"></i></span>
        <?php
        session_start();
        include_once "connectdb.php";
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
                <a href="teacher_info.php">
                    <span class="icon"><i class="fa-solid fa-user"></i></span>
                    <span class="text">Thông tin quản lý</span>
                </a>
            </li>
            <li>
                <a href="teacher_message.php">
                    <span class="icon"><i class="fa-solid fa-envelope"></i></span>
                    <span class="text">Tin nhắn</span>
                </a>
            </li>
            <li>
                <a href="teacher_infosv.php">
                    <span class="icon"><i class="fa-solid fa-circle-exclamation"></i></span>
                    <span class="text">Quản lý sinh viên</span>
                </a>
            </li>
            <li>
                <a href="teacher_fix.php">
                    <span class="icon"><i class="fa-solid fa-wrench"></i></span>
                    <span class="text">Sửa điểm</span>
                </a>
            </li>
            <li>
                <a href="teacher_board.php">
                    <span class="icon"><i class="fa-solid fa-table"></i></span>
                    <span class="text">Bảng điểm sinh viên</span>
                </a>
            </li>
            <li>
                <a href="teacher_logout.php">
                    <span class="icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                    <span class="text">Đăng xuất</span>
                </a>
            </li>
        </ul>
    </div>
    <article class="content">
        <form action="" method="POST">
            <div class="form-group">
                <label>Mã môn</label>
                <input type="text" class="form-control" id="mamon" name="txtmamon" value="<?php echo $mm ?>">
            </div>
            <div class="form-group">
                <label>Tên môn</label>
                <input type="text" class="form-control" id="tenmon" name="txttenmon" value="<?php echo $tm ?>">
            </div>
            <div class="form-group">
                <label for="sotinchi">Số tín chỉ</label>
                <input type="text" class="form-control" id="sotinchhi" name="txtsotinchi" value="<?php echo $stc ?>">
            </div>
            <div class="form-group">
                <label for="bomon">Bộ môn</label>
                <input type="text" class="form-control" id="bomon" name="txtbomon" value="<?php echo $bm ?>">
            </div>
            <div class="form-group">
                <label>Điểm số</label>
                <input type="text" class="form-control" id="diemso" name="txtdiemso" value="<?php echo $ds ?>">
            </div>
            <label for="">Điểm chữ</label>
            <input type="text" class="form-control" name="txtdiemchu" value="<?php echo $dc ?>">
            <div class="form-group">
                <label for="loai">Loại</label>
                <input type="text" class="form-control" name="txtloai" value="<?php echo $loai ?>">
                <label for="diemcc">Điểm chuyên cần</label>
                <input type="text" class="form-control" id="diemcc" name="txtdiemcc" value="<?php echo $cc ?>">
            </div>
            <div class="form-group">
                <label for="diemgk">Điểm giữa kỳ</label>
                <input type="text" class="form-control" id="diemgk" name="txtdiemgk" value="<?php echo $gk ?>">
            </div>
            <div class="form-group">
                <label for="diemck">Điểm cuối kỳ</label>
                <input type="text" class="form-control" id="diemck" name="txtdiemck" value="<?php echo $ck ?>">
            </div>
            <button type="submit" class="btn btn-primary" name="btnLuu"
                style="margin-left:300px; margin-top:10px">Lưu</button>
            <button type="submit" class="btn btn-primary" name="btnBack" style="margin-left:150px; margin-top:10px">Trở
                về</button>

        </form>
    </article>
</body>

</html>