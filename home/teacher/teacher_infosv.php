<?php
include_once "connectdb.php";
if (isset($_POST['btnGui'])) {
    require "../Classes/PHPExcel.php";
    $file = $_FILES['file']['tmp_name'];

    $objReader = PHPExcel_IOFactory::createReaderForFile($file);
    $objReader->setLoadSheetsOnly('Sheet1');
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

        // Check for duplicate entry
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
    echo "<script>alert('Thêm thành công')</script>";
    $stmt->close();
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
    <!-- content -->
    <article class="content">
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="row">
                <div class="col" style="margin:10px">
                    <label>Mã sinh viên</label>
                    <input type="text" class="form-control" placeholder="Mã sinh viên" name="txtma"
                        value="<?php echo $ma ?>">
                </div>
                <div class="col" style="margin:10px">
                    <label>Họ tên</label>
                    <input type="text" class="form-control" placeholder="Họ tên" name="txthoten"
                        value="<?php echo $ht ?>">
                </div>
                <div class="col" style="margin:10px">
                    <label>Email</label>
                    <input type="text" class="form-control" placeholder="Email" name="txtemail"
                        value="<?php echo $em ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" name="btnTimkiem"
                style="margin-left:419px; margin-top:10px; margin-bottom: 10px; margin-right: 60px">Tìm
                kiếm</button>
            <button type="submit" class="btn btn-primary" name="btnThemmoi"
                style="margin-left:100px; margin-top:10px; margin-bottom: 10px">Thêm
                mới</button>
            <div class="input-group mb-3" style="width: 300px; margin:10px; margin-left: 445px;">
                <input type="file" class="form-control" aria-label="Gửi" name="file">
                <button class="btn btn-outline-secondary" type="submit" name="btnGui">Gửi</button>
            </div>
        </form>
        <table class="table table-bordered">
            <thead style="background-color: #4e73df; color: white; text-align: center;">
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
                                <a href="./manager_sv/teacher_fix_qlsv.php?ma=<?php echo $row['ma'] ?>"
                                    class="btn btn-light""><i class=" fa-solid fa-wrench"></i></a>
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