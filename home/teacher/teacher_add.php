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
        $stmt = $con->prepare("INSERT INTO diem(mamon, hoten, ma, tenmon, sotinchi, diemso, diemchu, diemcc, diemgk, diemck, loai) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        for ($row = 2; $row <= $highestRow; $row++) {
            $mamon = $sheetData[$row]['A'];
            $hoten = $sheetData[$row]['B'];
            $msv = $sheetData[$row]['C'];
            $tenmon = $sheetData[$row]['D'];
            $sotinchi = $sheetData[$row]['E'];
            $diemso = $sheetData[$row]['F'];
            $diemchu = $sheetData[$row]['G'];
            $diemcc = $sheetData[$row]['H'];
            $diemgk = $sheetData[$row]['I'];
            $diemck = $sheetData[$row]['J'];
            $loai = $sheetData[$row]['K'];

            // Kiểm tra mục nhập trùng lặp
            $checkSql = "SELECT * FROM diem WHERE mamon = ? AND hoten = ?";
            $stmtCheck = $con->prepare($checkSql);
            $stmtCheck->bind_param("ss", $mamon, $hoten);
            $stmtCheck->execute();
            $result = $stmtCheck->get_result();
            if ($result->num_rows == 0 && !empty($mamon)) {
                $stmt->bind_param("ssssiisiiis", $mamon, $hoten, $msv, $tenmon, $sotinchi, $diemso, $diemchu, $diemcc, $diemgk, $diemck, $loai);
                $stmt->execute();
            }
            $stmtCheck->close();
        }
    }
    $stmt->close();
    echo "<script>alert('Thêm thành công!'); window.location.href='teacher_board.php';</script>";
}

$msv = '';
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
    $msv = $_POST['txtmsv'];
    $tm = $_POST['txttenmon'];
    $stc = $_POST['txtstc'];
    $ds = $_POST['txtdiemso'];
    $dc = $_POST['txtdiemchu'];
    $cc = $_POST['txtdiemcc'];
    $gk = $_POST['txtdiemgk'];
    $ck = $_POST['txtdiemck'];

    // Kiểm tra mục nhập trùng lặp
    $checkSql = "SELECT * FROM diem WHERE mamon = '$mm' AND ma = '$msv'";
    $result = mysqli_query($con, $checkSql);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Điểm của sinh viên này đã tồn tại!'); window.location.href='teacher_add.php';</script>";
    } else {
        $sql = "INSERT INTO diem (mamon, hoten, ma, tenmon, sotinchi, diemso, diemchu, diemcc, diemgk, diemck)
        VALUES ('$mm', '$ht', '$msv', '$tm', '$stc', '$ds', '$dc', '$cc', '$gk', '$ck')";

        $kq = mysqli_query($con, $sql);
        if ($kq) {
            echo "<script>alert('Thêm mới thành công!'); window.location.href='teacher_board.php';</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="teacher_info.css">
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
                <a href="teacher_listgv.php">
                    <span class="icon"><i class="fa-solid fa-list"></i></span>
                    <span class="text">Danh sách quản lý</span>
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
        <div class="container mt-4">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="col">
                    <div class="input-group"
                        style="width: 400px; margin-top:10px; margin-bottom: 10px; margin-left: 350px;">
                        <input class="form-control" type="file" id="formFile" name="file">
                        <button class="btn btn-outline-success" type="submit" name="btnGui">Gửi</button>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group">
                        <i class="fa-solid fa-arrow-right"></i>
                        <div class="form-field">
                            <label>Mã học phần</label>
                            <select name="txtmamon" id="" class="info1">
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
                        </div>
                    </div>
                </div>
                <div class="col" style="margin-top: 10px;">
                    <div class="input-group">
                        <i class="fa-solid fa-arrow-right"></i>
                        <div class="form-field">
                            <label>Tên sinh viên</label>
                            <input class="info1" type="text" name="txthoten" value="<?php echo $ht; ?>"
                                placeholder="Họ và tên">
                        </div>
                    </div>
                </div>
                <div class="col" style="margin-top: 10px;">
                    <div class="input-group">
                        <i class="fa-solid fa-arrow-right"></i>
                        <div class="form-field">
                            <label>Mã sinh viên</label>
                            <input class="info1" type="text" name="txtmsv" value="<?php echo $msv; ?>"
                                placeholder="Mã sinh viên">
                        </div>
                    </div>
                </div>
                <div class="col" style="margin-top: 10px;">
                    <div class="input-group">
                        <i class="fa-solid fa-arrow-right"></i>
                        <div class="form-field">
                            <label>Tên học phần</label>
                            <input class="info1" type="text" name="txttenmon" value="<?php echo $tm; ?>"
                                placeholder="Tên học phần">
                        </div>
                    </div>
                </div>
                <div class="col" style="margin-top: 10px;">
                    <div class="input-group">
                        <i class="fa-solid fa-arrow-right"></i>
                        <div class="form-field">
                            <label>Số tín chỉ</label>
                            <input class="info1" type="text" name="txtstc" value="<?php echo $stc; ?>"
                                placeholder="Số tín chỉ">
                        </div>
                    </div>
                </div>
                <div class="col" style="margin-top: 10px;">
                    <div class="input-group">
                        <i class="fa-solid fa-arrow-right"></i>
                        <div class="form-field">
                            <label>Điểm số</label>
                            <input class="info1" type="text" name="txtdiemso" value="<?php echo $ds; ?>"
                                placeholder="Điểm số">
                        </div>
                    </div>
                </div>
                <div class="col" style="margin-top: 10px;">
                    <div class="input-group">
                        <i class="fa-solid fa-arrow-right"></i>
                        <div class="form-field">
                            <label>Điểm chữ</label>
                            <input class="info1" type="text" name="txtdiemchu" value="<?php echo $dc; ?>"
                                placeholder="Điểm chữ">
                        </div>
                    </div>
                </div>
                <div class="col" style="margin-top: 10px;">
                    <div class="input-group">
                        <i class="fa-solid fa-arrow-right"></i>
                        <div class="form-field">
                            <label>Điểm chuyên cần</label>
                            <input class="info1" type="text" name="txtdiemcc" value="<?php echo $cc; ?>"
                                placeholder="Điểm chuyên cần">
                        </div>
                    </div>
                </div>
                <div class="col" style="margin-top: 10px;">
                    <div class="input-group">
                        <i class="fa-solid fa-arrow-right"></i>
                        <div class="form-field">
                            <label>Điểm giữa kỳ</label>
                            <input class="info1" type="text" name="txtdiemgk" value="<?php echo $gk; ?>"
                                placeholder="Điểm giữa kỳ">
                        </div>
                    </div>
                </div>
                <div class="col" style="margin-top: 10px;">
                    <div class="input-group">
                        <i class="fa-solid fa-arrow-right"></i>
                        <div class="form-field">
                            <label>Điểm cuối kỳ</label>
                            <input class="info1" type="text" name="txtdiemck" value="<?php echo $ck; ?>"
                                placeholder="Điểm cuối kỳ">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success" name="btnLuu"
                    style="margin-left: 38%; width: 200px; margin-top:10px; margin-bottom: 10px">Lưu</button>
        </div>
        </form>
        </div>
    </article>
</body>

</html>