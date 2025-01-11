<?php
include_once "../connectdb.php";
session_start();
$sql = "SELECT DISTINCT khoahoc, hocky FROM mon_hoc ORDER BY khoahoc ASC, hocky ASC";
$sql_khoahoc_hocky = mysqli_query($con, $sql);

if (isset($_POST['btnTimkiem'])) {
    $khoahoc_hocky = $_POST['khoahoc_hocky'];
    $sql = "SELECT diem.*, lop_hoc.tenlop FROM diem 
           JOIN lop_hoc ON diem.tenlop = lop_hoc.tenlop 
           WHERE hoten LIKE '%$ht%' AND tenmon LIKE '%$tm%' AND ma LIKE '%$ma%' AND lop_hoc.tenlop LIKE '%$lop%'";
    if (!empty($khoahoc_hocky)) {
        $parts = explode('-Học kỳ:', $khoahoc_hocky);
        if (count($parts) == 2) {
            $khoahoc = trim($parts[0]);
            $hocky = trim($parts[1]);
            $sql .= " AND khoahoc = '$khoahoc' AND hocky = '$hocky'";
        }
    }
    $data = mysqli_query($con, $sql);
}

$sortOrder = "DESC"; //Biến sắp xếp mặc định
if (isset($_POST['sortOrder'])) {
    $sortOrder = $_POST['sortOrder'];
}
if (isset($_POST['btnSapxep'])) {
    $khoa = $_POST['khoa'];
    $khoahoc = $_POST['khoahoc'];
    $sql = "SELECT * FROM hoc_bong WHERE khoa LIKE '%$khoa%' AND khoahoc LIKE '%$khoahoc%' ORDER BY diem_tb $sortOrder";
    $data = mysqli_query($con, $sql);
}
?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="../teacher_homepage.css">
<link rel="stylesheet" href="../teacher_info.css">
<title>Quản lý học bổng sinh viên đại học</title>

<body>
    <!-- header -->
    <div class="header">
        <span class="header-text">Quản lý học bổng</span>
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
                <a href="../manager_course/teacher_course.php">
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
                <a href="teacher_scholarship.php">
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
        <form method="post" action="">
            <div class="row">
                <div class="col" style="margin:10px">
                    <div class="input-group full-width">
                        <i class="fa-solid fa-user"></i>
                        <div class="form-field">
                            <label for="khoahoc_hocky">Khóa học và học kỳ</label>
                            <select name="khoahoc">
                                <option value="">Chọn khóa học và học kỳ</option>
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
                <div class="col" style="margin:10px">
                    <div class="input-group full-width">
                        <i class="fa-solid fa-filter"></i>
                        <div class="form-field">
                            <label for="sortOrder">Sắp xếp</label>
                            <select class="info1" name="sortOrder">
                                <option value="DESC" <?php if ($sortOrder == "DESC")
                                    echo "selected"; ?>>Giảm dần</option>
                                <option value="ASC" <?php if ($sortOrder == "ASC")
                                    echo "selected"; ?>>Tăng dần</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-info" name="btnTimkiem"
                style="margin-left:370px; margin-top:10px; margin-bottom: 10px; margin-right: 60px"><i
                    class="fa-solid fa-magnifying-glass"></i> Tìm kiếm</button>
            <button class="btn btn-info" type="submit" name="btnXuat"><i class="fa-solid fa-file-export"></i> Xuất
                file</button>
            <button class="btn btn-info" type="submit" name="btnSapxep" style="margin-left:60px;"><i
                    class="fa-solid fa-arrow-up-wide-short"></i> Sắp xếp</button>
            <button class="btn btn-danger" type="submit" name="btnXoa" style="margin-left:220px;"
                onclick="return confirm('Bạn có chắc chắn muốn xóa không ?')">Xoá tất cả <i
                    class="fa-solid fa-xmark"></i></button>
            <table class="table table-bordered" style="background-color: #3F72AF; color: #F9F7F7;">
                <thead style="background-color: #1B262C; color: #FADA7A; text-align: center;">
                    <tr>
                        <th>STT</th>
                        <th>Tên sinh viên</th>
                        <th>Mã sinh viên</th>
                        <th>Lớp</th>
                        <th>Tổng STC</th>
                        <th>Điểm TB (hệ số 4)</th>
                        <th>Loại học bổng</th>
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
                                <td><?php echo $row['hoten'] ?></td>
                                <td><?php echo $row['ma'] ?></td>
                                <td><?php echo $row['lop'] ?></td>
                                <td><?php echo $row['stc_hk'] ?></td>
                                <td><?php echo $row['diem_tb'] ?></td>
                                <td><?php echo $row['loai_hoc_bong'] ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </form>
    </article>
</body>

</html>

</html>