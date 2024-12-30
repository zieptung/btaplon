<?php
include_once "connectdb.php";
if (isset($_POST['btnGui'])) {
    require "../Classes/PHPExcel.php";
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['file']['tmp_name'];

        $objReader = PHPExcel_IOFactory::createReaderForFile($file);
        $objReader->setLoadSheetsOnly('Sheet1');
        $objExcel = $objReader->load($file);
        $sheetData = $objExcel->getActiveSheet()->toArray(null, true, true, true);
        $highestRow = $objExcel->setActiveSheetIndex()->getHighestRow();
        $stmt = $con->prepare("INSERT INTO diem(mamon, hoten, tenmon, sotinchi, diemso, diemchu, diemcc, diemgk, diemck, loai) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        for ($row = 2; $row <= $highestRow; $row++) {
            $mamon = $sheetData[$row]['A'];
            $hoten = $sheetData[$row]['B'];
            $tenmon = $sheetData[$row]['C'];
            $sotinchi = $sheetData[$row]['D'];
            $diemso = $sheetData[$row]['E'];
            $diemchu = $sheetData[$row]['F'];
            $diemcc = $sheetData[$row]['G'];
            $diemgk = $sheetData[$row]['H'];
            $diemck = $sheetData[$row]['I'];
            $loai = $sheetData[$row]['J'];

            // Check for duplicate entry
            $checkSql = "SELECT * FROM diem WHERE mamon = ? AND hoten = ?";
            $stmtCheck = $con->prepare($checkSql);
            $stmtCheck->bind_param("ss", $mamon, $hoten);
            $stmtCheck->execute();
            $result = $stmtCheck->get_result();
            if ($result->num_rows == 0 && !empty($mamon)) {
                $stmt->bind_param("sssiisiiis", $mamon, $hoten, $tenmon, $sotinchi, $diemso, $diemchu, $diemcc, $diemgk, $diemck, $loai);
                $stmt->execute();
            }
            $stmtCheck->close();
        }
    }
    $stmt->close();
    echo "<script>alert('Thêm thành công')</script>";
}

$id = '';
$mm = '';
$ht = '';
$tm = '';
$stc = '';
$ds = '';
$dc = '';
$cc = '';
$gk = '';
$ck = '';

$sql = "SELECT * from diem";
$monhoc = mysqli_query($con, $sql);

$sql1 = "SELECT * from mon_hoc";
$mon_hoc = mysqli_query($con, $sql1);

if (isset($_POST["btnLuu"])) {
    $mm = $_POST['txtmamon'];
    $ht = $_POST['txthoten'];
    $tm = $_POST['txttenmon'];
    $stc = $_POST['txtstc'];
    $ds = $_POST['txtdiemso'];
    $dc = $_POST['txtdiemchu'];
    $cc = $_POST['txtdiemcc'];
    $gk = $_POST['txtdiemgk'];
    $ck = $_POST['txtdiemck'];
    $sql = "INSERT INTO diem (mamon, hoten, tenmon, sotinchi, diemso, diemchu, diemcc, diemgk, diemck)
    VALUES ('$mm', '$ht', '$tm', '$stc', '$ds', '$dc', '$cc', '$gk', '$ck')";

    $kq = mysqli_query($con, $sql);
    if ($kq)
        echo "<script>alert('Thêm mới thành công!')</script>";
    else
        echo "<script>alert('Thêm mới thất bại!')</script>";
}

?>

<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="teacher_homepage.css">
<title>Quản lý điểm sinh viên đại học</title>

<body>
    <!-- header -->
    <div class="header">
        <span class="header-text">Thêm điểm sinh viên</span>
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
                <a href="teacher_add.php">
                    <span class="icon"><i class="fa-solid fa-wrench"></i></span>
                    <span class="text">Thêm điểm sinh viên</span>
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
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="input-group mb-3" style="width: 300px; margin:10px; margin-left: 445px;">
                <input type="file" class="form-control" aria-label="Gửi" name="file">
                <button class="btn btn-outline-secondary" type="submit" name="btnGui">Gửi</button>
            </div>
            <div class="form-group" style="width: 75%; margin-left: 150px; margin-top: 0px; margin-bottom: 10px;">
                <label>Mã học phần</label>
                <select name="txtmamon" id="" class="form-control" style="margin-bottom: 20px;">
                    <option value="">---Chọn mã học phần---</option>
                    <?php
                    if (isset($mon_hoc) && mysqli_num_rows($mon_hoc) > 0) {
                        while ($row = mysqli_fetch_assoc($mon_hoc)) {
                            ?>
                            <option value="<?php echo $row['mamon'] ?>">
                                <?php echo $row['mamon'] ?>
                            </option>
                            <?php
                        }
                    }
                    ?>
                </select>
                <label>Tên sinh viên</label>
                <input type="text" class="form-control" placeholder="Tên sinh viên" name="txthoten"
                    value="<?php echo $ht ?>" style="margin-bottom: 20px;">
                <label>Tên học phần</label>
                <input type="text" class="form-control" placeholder="Tên học phần" name="txttenmon"
                    value="<?php echo $tm ?>" style="margin-bottom: 20px;">
                <label>Số tín chỉ</label>
                <input type="text" class="form-control" placeholder="Số tín chỉ" name="txtstc"
                    value="<?php echo $stc ?>" style="margin-bottom: 20px;">
                <label>Điểm số</label>
                <input type="numeber" class="form-control" placeholder="Điểm số" name="txtdiemso"
                    value="<?php echo $ds ?>" style="margin-bottom: 20px;">
                <label>Điểm chữ</label>
                <input type="text" class="form-control" placeholder="Điểm chữ" name="txtdiemchu"
                    value="<?php echo $dc ?>" style="margin-bottom: 20px;">
                <label>Điểm chuyên cần</label>
                <input type="number" class="form-control" placeholder="Điểm chuyên cần" name="txtdiemcc"
                    value="<?php echo $cc ?>" style="margin-bottom: 20px;">
                <label>Điểm giữa kỳ</label>
                <input type="number" class="form-control" placeholder="Điểm giữa kỳ" name="txtdiemgk"
                    value="<?php echo $gk ?>" style="margin-bottom: 20px;">
                <label>Điểm cuối kỳ</label>
                <input type="number" class="form-control" placeholder="Điểm cuối kỳ" name="txtdiemck"
                    value="<?php echo $ck ?>" style="margin-bottom: 20px;">
                <button type="submit" class="btn btn-primary" name="btnLuu"
                    style="margin-left: 38%; width: 200px">Lưu</button>
            </div>
        </form>
    </article>
</body>

</html>