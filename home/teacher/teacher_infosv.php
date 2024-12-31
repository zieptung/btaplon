<?php
include_once "connectdb.php";
require "../Classes/PHPExcel.php";
if (isset($_POST['btnGui'])) {
    $file = $_FILES['file']['tmp_name'];

    $objReader = PHPExcel_IOFactory::createReaderForFile($file);
    $listWorkSheets = $objReader->listWorksheetNames($file);
    foreach ($listWorkSheets as $sheetName) {
        $objReader->setLoadSheetsOnly($sheetName);
        $objExcel = $objReader->load($file);
        $sheetData = $objExcel->getActiveSheet()->toArray(null, true, true, true);
        $highestRow = $objExcel->setActiveSheetIndex()->getHighestRow();
        $stmt = $con->prepare("INSERT INTO user(ma, hoten, email, password, is_admin) VALUES (?, ?, ?, ?, ?)");
        for ($row = 2; $row <= $highestRow; $row++) {
            $ma = $sheetData[$row]['A'];
            $hoten = $sheetData[$row]['B'];
            $email = $sheetData[$row]['C'];
            $password = $sheetData[$row]['D'];
            $is_admin = $sheetData[$row]['E'];

            // Kiểm tra mục nhập trùng lặp
            $checkSql = "SELECT * FROM user WHERE ma = ?";
            $stmtCheck = $con->prepare($checkSql);
            $stmtCheck->bind_param("s", $ma);
            $stmtCheck->execute();
            $result = $stmtCheck->get_result();
            if ($result->num_rows == 0 && !empty($ma)) {
                $stmt->bind_param("ssssi", $ma, $hoten, $email, $password, $is_admin);
                $stmt->execute();
            }
            $stmtCheck->close();
        }
        $stmt->close();
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
    $sheet->setCellValue("C1", 'Email');
    $sheet->setCellValue("D1", 'Mật khẩu');
    $sheet->setCellValue("E1", 'Quyền truy cập');

    $sql = "SELECT ma, hoten, email, password, is_admin FROM user";
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $rowCount++;
        $sheet->setCellValue("A{$rowCount}", $row['ma']);
        $sheet->setCellValue("B{$rowCount}", $row['hoten']);
        $sheet->setCellValue("C{$rowCount}", $row['email']);
        $sheet->setCellValue("D{$rowCount}", $row['password']);
        $sheet->setCellValue("E{$rowCount}", $row['is_admin']);
    }

    //định dạng cột tiêu đề
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setAutoSize(true);
    $sheet->getColumnDimension('E')->setAutoSize(true);

    //gán màu nền
    $sheet->getStyle('A1:E1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('00FF00');

    //căn giữa
    $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    //Kẻ bảng 
    $styleAray = [
        'borders' => [
            'allborders' => [
                'style' => PHPExcel_Style_Border::BORDER_THIN
            ]
        ]
    ];
    $sheet->getStyle("A1:E{$rowCount}")->applyFromArray($styleAray);
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
$em = "";
$sql = "SELECT * FROM user WHERE is_admin = 0";
if (isset($_POST['btnTimkiem'])) {
    $ma = $_POST['txtma'];
    $ht = $_POST['txthoten'];
    $em = $_POST['txtemail'];
    $sql = "SELECT * FROM user WHERE ma LIKE '%$ma%' AND hoten LIKE '%$ht%' AND email LIKE '%$em%' AND is_admin = 0";
    $ma = "";
    $ht = "";
    $em = "";
}
$data = mysqli_query($con, $sql);
if (isset($_POST['btnThemmoi'])) {
    header("location: ./manager_sv/teacher_add_qlsv.php");
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
        <span class="header-text">Quản lý sinh viên</span>
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
                <a href="teacher_forum.php">
                    <span class="icon"><i class="fa-solid fa-bell"></i></span>
                    <span class="text">Diễn đàn</span>
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
                        <i class="fa-solid fa-envelope"></i>
                        <div class="form-field">
                            <input class="info1" type="text" name="txtemail" value="<?php echo $em; ?>"
                                placeholder="Email">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <button type="submit" class="btn btn-info" name="btnTimkiem"
                        style="margin-left:160px; margin-top:20px; margin-bottom: 10px;">Tìm
                        kiếm</button>
                </div>
                <div class="col">
                    <div class="input-group" style="width: 400px; margin-top:10px; margin-bottom: 10px;">
                        <input class="form-control" type="file" id="formFile" name="file">
                        <button class="btn btn-outline-success" type="submit" name="btnGui">Gửi</button>
                        <button class="btn btn-outline-info" type="submit" name="btnXuat">Xuất file</button>
                    </div>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-info" name="btnThemmoi"
                        style="margin-left:100px; margin-top:20px; margin-bottom: 10px">Thêm mới</button>
                </div>
            </div>
        </form>
        <table class="table table-bordered" style="background-color: #3F72AF; color: #F9F7F7;">
            <thead style="background-color: #1B262C; color: #FADA7A; text-align: center;">
                <tr>
                    <th>STT</th>
                    <th>Mã sinh viên</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Mật khẩu</th>
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
                            <td>
                                <a href="./manager_sv/teacher_fix_qlsv.php?ma=<?php echo $row['ma'] ?>" class="btn btn-light"><i
                                        class=" fa-solid fa-wrench"></i></a>
                                <a href=" ./manager_sv/teacher_del_qlsv.php?ma=<?php echo $row['ma'] ?>"
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