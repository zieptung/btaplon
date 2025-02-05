<?php
session_start();
include_once "../connectdb.php";
require "../teacher/Classes/PHPExcel.php";
if (isset($_POST['btnGui'])) {
    $file = $_FILES['file']['tmp_name'];

    $objReader = PHPExcel_IOFactory::createReaderForFile($file);
    $listWorkSheets = $objReader->listWorksheetNames($file);
    foreach ($listWorkSheets as $sheetName) {
        $objReader->setLoadSheetsOnly($sheetName);
        $objExcel = $objReader->load($file);
        $sheetData = $objExcel->getActiveSheet()->toArray(null, true, true, true);
        $highestRow = $objExcel->setActiveSheetIndex()->getHighestRow();
        for ($row = 2; $row <= $highestRow; $row++) {
            $ma = $sheetData[$row]['A'];
            $hoten = $sheetData[$row]['B'];
            $tenlop = $sheetData[$row]['C'];
            $email = $sheetData[$row]['D'];
            $password = $sheetData[$row]['E'];
            $is_admin = $sheetData[$row]['F'];
            $kh = $sheetData[$row]['G'];

            // Kiểm tra mã sinh viên đã tồn tại trong bảng user chưa
            $checkSql = "SELECT * FROM user WHERE ma = '$ma'";
            $result = mysqli_query($con, $checkSql);
            if (mysqli_num_rows($result) == 0 && !empty($ma)) {
                // Thêm sinh viên mới vào bảng user
                $sqlUser = "INSERT INTO user(ma, hoten, tenlop, email, password, is_admin, khoahoc) VALUES ('$ma', '$hoten', '$tenlop', '$email', '$password', '$is_admin', '$kh')";
                mysqli_query($con, $sqlUser);

                // Thêm sinh viên mới vào bảng sinhvien
                $sqlStudent = "INSERT INTO sinh_vien(ma, hoten, tenlop, email, khoahoc) VALUES ('$ma', '$hoten', '$tenlop', '$email', '$kh')";
                mysqli_query($con, $sqlStudent);
            }
        }
    }
    echo "<script>alert('Thêm thành công')</script>";
}

if (isset($_POST['btnXuat'])) {
    $objExcel = new PHPExcel();
    $objExcel->setActiveSheetIndex(0);
    $sheet = $objExcel->getActiveSheet()->setTitle('haha');
    $rowCount = 1;

    //Tạo tiêu đề cho cột trong excel
    $sheet->setCellValue("A1", 'Mã sinh viên');
    $sheet->setCellValue("B1", 'Tên sinh viên');
    $sheet->setCellValue("C1", 'Tên lớp');
    $sheet->setCellValue("D1", 'Email');
    $sheet->setCellValue("E1", 'Mật khẩu');
    $sheet->setCellValue("F1", 'Quyền truy cập');
    $sheet->setCellValue("G1", 'Khóa học');

    $tc = $_POST['txttruycap'];
    $sql = "SELECT ma, hoten, tenlop, email, password, is_admin, khoahoc FROM user";
    if ($tc !== '') {
        $sql .= " WHERE is_admin = '$tc'";
    }
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $rowCount++;
        $sheet->setCellValue("A{$rowCount}", $row['ma']);
        $sheet->setCellValue("B{$rowCount}", $row['hoten']);
        $sheet->setCellValue("C{$rowCount}", $row['tenlop']);
        $sheet->setCellValue("D{$rowCount}", $row['email']);
        $sheet->setCellValue("E{$rowCount}", $row['password']);
        $sheet->setCellValue("F{$rowCount}", $row['is_admin']);
        $sheet->setCellValue("G{$rowCount}", $row['khoahoc']);
    }

    //định dạng cột tiêu đề
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setAutoSize(true);
    $sheet->getColumnDimension('E')->setAutoSize(true);
    $sheet->getColumnDimension('F')->setAutoSize(true);
    $sheet->getColumnDimension('G')->setAutoSize(true);

    //gán màu nền
    $sheet->getStyle('A1:G1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('00FF00');

    //căn giữa
    $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    //Kẻ bảng 
    $styleAray = [
        'borders' => [
            'allborders' => [
                'style' => PHPExcel_Style_Border::BORDER_THIN
            ]
        ]
    ];
    $sheet->getStyle("A1:G{$rowCount}")->applyFromArray($styleAray);
    $objWriter = new PHPExcel_Writer_Excel2007($objExcel);
    $fileName = 'DStruycap.xlsx';
    $objWriter->save($fileName);
    ob_end_clean(); // Xóa bộ đệm đầu ra
    header("Content-Disposition: attachment; filename=\"{$fileName}\"");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Length: ' . filesize($fileName));
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate');
    header('Pragma: no-cache');
    readfile($fileName);
    unlink($fileName); // Xóa tệp sau khi tải xuống
    exit;
}

$ma = "";
$ht = "";
$lop = "";
$tc = "";
if (isset($_POST['btnXoa'])) {
    $sql = "DELETE FROM user WHERE is_admin = 1 OR is_admin = 0";
    mysqli_query($con, $sql);
    echo "<script>alert('Xoá thành công')</script>";
}
$sql_lop = "SELECT tenlop FROM lop_hoc";
$result_lop = mysqli_query($con, $sql_lop);

$sql = "SELECT * FROM user WHERE 1=1";
if (isset($_POST['btnTimkiem'])) {
    $ma = $_POST['txtma'];
    $ht = $_POST['txthoten'];
    $lop = $_POST['txtlop'];
    $tc = $_POST['txttruycap'];

    if (!empty($ma)) {
        $sql .= " AND ma = '$ma'";
    }
    if (!empty($ht)) {
        $sql .= " AND hoten = '$ht'";
    }
    if (!empty($lop)) {
        $sql .= " AND tenlop = '$lop'";
    }
    if ($tc !== '') {
        $sql .= " AND is_admin = '$tc'";
    }
}
$data = mysqli_query($con, $sql);


if (isset($_POST['btnThemmoi'])) {
    echo "<script>
        if (confirm('Bạn muốn thêm mới Giảng viên?')) {
            window.location.href = 'admin_add_gv.php';
        } else {
            window.location.href = 'admin_add_sv.php';
        }
    </script>";
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
<style>
    .sidebar {
        overflow: hidden;
    }
</style>

<body>
    <!-- header -->
    <div class="header">
        <span class="header-text">Quản lý người dùng</span>
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
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="row">
                <div class="col" style="margin:10px">
                    <div class="input-group full-width">
                        <i class="fa-solid fa-user"></i>
                        <div class="form-field">
                            <input class="info1" type="text" name="txtma" value="<?php echo $ma; ?>"
                                placeholder="Mã sinh viên">
                        </div>
                    </div>
                </div>
                <div class="col" style="margin:10px">
                    <div class="input-group full-width">
                        <i class="fa-solid fa-signature"></i>
                        <div class="form-field">
                            <input class="info1" type="text" name="txthoten" value="<?php echo $ht; ?>"
                                placeholder="Họ tên">
                        </div>
                    </div>
                </div>
                <div class="col" style="margin:10px">
                    <div class="input-group full-width">
                        <i class="fa-solid fa-school"></i>
                        <div class="form-field">
                            <label for="txtlop">Lớp</label>
                            <select class="form-control" id="txtlop" name="txtlop">
                                <option value="">Chọn lớp</option>
                                <?php
                                if (isset($result_lop) && mysqli_num_rows($result_lop) > 0) {
                                    while ($row = mysqli_fetch_assoc($result_lop)) {
                                        ?>
                                        <option value="<?php echo $row['tenlop'] ?>" <?php echo ($lop == $row['tenlop']) ? 'selected' : ''; ?>>
                                            <?php echo $row['tenlop'] ?>
                                        </option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col" style="margin:10px">
                    <div class="input-group full-width">
                        <i class="fa-solid fa-clipboard"></i>
                        <div class="form-field">
                            <select class="info1" name="txttruycap">
                                <option value="">Quyền truy cập</option>
                                <option value="1" <?php echo ($tc == '1') ? 'selected' : ''; ?>>Giảng viên</option>
                                <option value="0" <?php echo ($tc == '0') ? 'selected' : ''; ?>>Sinh viên</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <button type="submit" class="btn btn-info" name="btnTimkiem"
                        style="margin-left:70px; margin-top:20px; margin-bottom: 10px;"><i
                            class="fa-solid fa-magnifying-glass"></i> Tìm
                        kiếm</button>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-info" name="btnThemmoi"
                        style="margin-top:20px; margin-bottom: 10px; margin-left:20px"><i class="fa-solid fa-plus"></i>
                        Thêm mới</button>
                </div>
                <div class="col">
                    <div class="col">
                        <div class="input-group"
                            style="width: 400px; margin-top:10px; margin-bottom: 10px; margin-right: 100px;">
                            <input class="form-control" type="file" id="formFile" name="file">
                            <button class="btn btn-outline-success" type="submit" name="btnGui">Gửi</button>
                            <button class="btn btn-outline-info" type="submit" name="btnXuat">Xuất file</button>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-danger" name="btnXoa"
                    style="margin-right:50px; margin-top:20px; margin-bottom: 10px; margin-left: 50px;"
                    onclick="return confirm('Bạn có chắc chắn muốn xóa không ?')">Xoá tất cả <i
                        class="fa-solid fa-xmark"></i></button>
            </div>
        </form>
        <table class="table table-bordered" style="background-color: #3F72AF; color: #F9F7F7;">
            <thead style="background-color: #1B262C; color: #FADA7A; text-align: center;">
                <tr>
                    <th>STT</th>
                    <th>Mã</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Mật khẩu</th>
                    <th>Quyền truy cập</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody style="text-align: center;">
                <?php
                if (isset($data) && mysqli_num_rows($data) > 0) {
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($data)) {
                        ?>
                        <tr>
                            <td><?php echo $i++ ?></td>
                            <td><?php echo $row['ma'] ?></td>
                            <td><?php echo $row['hoten'] ?></td>
                            <td><?php echo $row['email'] ?></td>
                            <td><?php echo $row['password'] ?></td>
                            <td><?php echo $row['is_admin'] == 1 ? 'Giảng viên' : ($row['is_admin'] == 0 ? 'Sinh viên' : 'Admin'); ?>
                            </td>
                            <td>
                                <a href="<?php echo ($row['is_admin'] == 1 || $row['is_admin'] == 2) ? 'admin_fix_gv.php' : 'admin_fix_sv.php'; ?>?ma=<?php echo $row['ma'] ?>"
                                    class="btn btn-light"><i class="fa-solid fa-wrench"></i></a>
                                <a href="admin_del.php?ma=<?php echo $row['ma'] ?>"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa không ?')" class="btn btn-danger"><i
                                        class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>

    </article>
</body>

</html>