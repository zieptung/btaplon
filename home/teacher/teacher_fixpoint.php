<?php
include_once("connectdb.php");
// Initialize variables
$mm = '';
$tm = '';
$stc = '';
$bm = '';
$ds = '';
$dc = '';
$loai = '';
$cc = '';
$gk = '';
$ck = '';
$diemtong = '';

// Get student data if ID is provided
if (isset($_GET['mamon'])) {
    $mm = $_GET['mamon'];
    $sql = "SELECT * FROM mon_hoc WHERE mamon='$mm'";
    $result = mysqli_query($con, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $mm = $row['mamon'];
        $tm = $row['tenmon'];
        $stc = $row['sotinchi'];
        $bm = $row['bomon'];
        $ds = $row['diemso'];
        $dc = $row['diemchu'];
        $loai = $row['loai'];
        $cc = $row['diemcc'];
        $gk = $row['diemgk'];
        $ck = $row['diemck'];
        $diemtong = $row['diemtong'];
    } else {
        echo "<script>alert('Không tìm môn học!'); window.location='teacher_board.php';</script>";
    }
}

// Cập nhật dữ liệu khi bấm "Xác nhận"
if (isset($_POST["btnsubmit"])) {
    // Get form data
    $mm = $_POST["txtmamon"];
    $tm = $_POST["txttenmon"];
    $stc = $_POST["txtsotinchi"];
    $bm = $_POST["txtbomon"];
    $ds = $_POST["txtdiemso"];
    $dc = $_POST["txtdiemchu"];
    $loai = $_POST["txtloai"];
    $cc = $_POST["txtdiemcc"];
    $gk = $_POST["txtdiemgk"];
    $ck = $_POST["txtdiemck"];
    $diemtong = $_POST["txtdiemtong"];

    // Update query
    $sql = "UPDATE mon_hoc SET 
        tenmon='$tm', 
        sotinchi='$stc', 
        bomon='$bm', 
        diemso='$ds', 
        diemchu='$dc', 
        loai='$loai', 
        diemcc='$cc', 
        diemgk='$gk', 
        diemck='$ck', 
        diemtong='$diemtong'
        WHERE mamon='$mm'";

    $kq = mysqli_query($con, $sql);
    if ($kq) {
        echo "<script>alert('Cập nhật thành công!'); window.location='teacher_board.php';</script>";
    } else {
        echo "<script>alert('Cập nhật thất bại!');</script>";
    }
}

// Trở lại bảng điểm khi bấm "Trở lại"
if (isset($_POST["btnback"])) {
    header('location:teacher_board.php');
}
?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
<link rel="stylesheet" href="teacher_homepage.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<title>Sửa Điểm</title>
<body>
   <!-- header -->
   <div class="header">
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
                    <span class="text">Thông tin cá nhân</span>
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
    <!-- content -->
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
            <button type="submit" class="btn btn-secondary mr-2" name="btnback">Trở lại</button>
            &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
            <button type="submit" class="btn btn-primary" name="btnsubmit">Xác nhận</button>
    </article>

</body>

</html>