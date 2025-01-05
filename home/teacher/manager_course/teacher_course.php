<?php
include_once "../connectdb.php";
require "../Classes/PHPExcel.php";
session_start();
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
            $mamon = $sheetData[$row]['A'];
            $tenmon = $sheetData[$row]['B'];
            $sotinchi = $sheetData[$row]['C'];
            $khoahoc = $sheetData[$row]['D'];
            $hocky = $sheetData[$row]['E'];

            // Kiểm tra mã môn học đã tồn tại trong bảng mon_hoc chưa
            $checkSql = "SELECT * FROM mon_hoc WHERE mamon = '$mamon'";
            $result = mysqli_query($con, $checkSql);
            if (mysqli_num_rows($result) == 0 && !empty($mamon)) {
                // Thêm môn học mới vào bảng mon_hoc
                $sqlCourse = "INSERT INTO mon_hoc(mamon, tenmon, sotinchi, khoahoc, hocky) VALUES ('$mamon', '$tenmon', '$sotinchi', '$khoahoc', '$hocky')";
                mysqli_query($con, $sqlCourse);
            }
        }
    }
    echo "<script>alert('Thêm thành công')</script>";
}

$sql = "SELECT DISTINCT khoahoc, hocky FROM mon_hoc ORDER BY khoahoc ASC, hocky ASC";
$sql_khoahoc_hocky = mysqli_query($con, $sql);

if (isset($_POST['submit'])) {
    $khoahoc_hocky = $_POST['khoahoc_hocky'];
    if (!empty($khoahoc_hocky)) {
        $parts = explode('-Học kỳ:', $khoahoc_hocky);
        if (count($parts) == 2) {
            $khoahoc = trim($parts[0]);
            $hocky = trim($parts[1]);
            $sql1 = "SELECT * FROM mon_hoc WHERE khoahoc = '$khoahoc' AND hocky = '$hocky'";
            $monhoc_result = mysqli_query($con, $sql1);
        }
    }
}
if (isset($_POST['btnThemmoi'])) {
    header("Location: teacher_add_course.php");
}
?>

<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="../teacher_homepage.css">
<link rel="stylesheet" href="../teacher_info.css">
<title>Quản lý điểm sinh viên đại học</title>

<body>
    <!-- header -->
    <div class="header">
        <span class="header-text">Quản lý môn học</span>
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
                <a href="../teacher_info.php">
                    <span class="icon"><i class="fa-solid fa-user"></i></span>
                    <span class="text">Thông tin cá nhân</span>
                </a>
            </li>
            <li>
                <a href="../manager_class/teacher_class.php">
                    <span class="icon"><i class="fa-solid fa-landmark"></i></span>
                    <span class="text">Quản lý lớp học</span>
                </a>
            </li>
            <li>
                <a href="teacher_course.php">
                    <span class="icon"><i class="fa-solid fa-pen-to-square"></i></span>
                    <span class="text">Quản lý môn học</span>
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
                <a href="../manager_scholarship/teacher_scholarship.php">
                    <span class="icon"><i class="fa-solid fa-user-graduate"></i></span>
                    <span class="text">Danh sách học bổng</span>
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
        <!-- Form chọn khóa học và học kỳ -->
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="row">
                <div class="col" style="margin:10px">
                    <div class="input-group full-width">
                        <div class="form-field">
                            <label for="khoahoc_hocky">Khóa học và học kỳ:</label>
                            <select class="form-control" id="khoahoc_hocky" name="khoahoc_hocky">
                                <option value="">Chọn Khoá học và học kỳ</option>
                                <?php
                                if (isset($sql_khoahoc_hocky) && mysqli_num_rows($sql_khoahoc_hocky) > 0) {
                                    while ($row = mysqli_fetch_assoc($sql_khoahoc_hocky)) {
                                        $selected = (isset($_POST['khoahoc_hocky']) && $_POST['khoahoc_hocky'] == $row['khoahoc'] . '-Học kỳ:' . $row['hocky']) ? 'selected' : '';
                                        ?>
                                        <option value="<?php echo $row['khoahoc'] . '-Học kỳ:' . $row['hocky']; ?>" <?php echo $selected; ?>>
                                            <?php echo $row['khoahoc'] . ' - Học kỳ: ' . $row['hocky']; ?>
                                        </option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <button type="submit" name="submit" class="btn btn-info"
                        style="margin-left: 50px; margin-top:20px">Xem thông tin môn
                        học</button>
                </div>
                <div class="col">
                    <div class="input-group"
                        style="width: 400px; margin-top:10px; margin-bottom: 10px; margin-left: 50px;">
                        <input class="form-control" type="file" id="formFile" name="file">
                        <button class="btn btn-outline-success" type="submit" name="btnGui">Gửi</button>
                    </div>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-info" name="btnThemmoi"
                        style="margin-top: 20px; margin-left:90px"><i class="fa-solid fa-plus"></i>
                        Thêm mới</button>
                </div>
            </div>
            <table class="table table-bordered" style="background-color: #3F72AF; color: #F9F7F7;">
                <thead style="background-color: #1B262C; color: #FADA7A; text-align: center;">
                    <tr>
                        <th>Mã học phần</th>
                        <th>Tên học phần</th>
                        <th>Số tín chỉ</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody style="text-align: center;">
                    <?php
                    if (isset($monhoc_result) && mysqli_num_rows($monhoc_result) > 0) {
                        while ($row = mysqli_fetch_assoc($monhoc_result)) {
                            ?>
                            <tr>
                                <td><?php echo $row['mamon'] ?></td>
                                <td><?php echo $row['tenmon'] ?></td>
                                <td><?php echo $row['sotinchi'] ?></td>
                                <td>
                                    <a href="teacher_fix_course.php?mamon=<?php echo $row['mamon'] ?>" class="btn btn-light"><i
                                            class=" fa-solid fa-wrench"></i></a>
                                    <a href="teacher_del_course.php?mamon=<?php echo $row['mamon'] ?>"
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