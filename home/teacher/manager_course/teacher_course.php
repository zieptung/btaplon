<?php
include_once "../connectdb.php";
session_start();

$khoahoc_hocki = '';
$sql_khoahoc_hocki = "SELECT DISTINCT khoahoc, hocki FROM mon_hoc ORDER BY khoahoc ASC, hocki ASC";

$monhoc_result = null;

// Xử lý khi người dùng gửi biểu mẫu
if (isset($_POST['submit']) || isset($_POST['edit_course']) || isset($_POST['delete_course'])) {
    if (isset($_POST['submit'])) {
        $khoahoc_hocki = $_POST['khoahoc_hocki'];
    } elseif (isset($_POST['edit_course']) || isset($_POST['delete_course'])) {
        $khoahoc_hocki = $_POST['current_khoahoc_hocki'];
    }

    // Tách khóa học và học kỳ từ lựa chọn
    list($khoahoc, $hocki) = explode('|', $khoahoc_hocki);

    // Truy vấn lấy thông tin môn học dựa trên khóa học và học kỳ
    $sql_monhoc = "SELECT * FROM mon_hoc WHERE khoahoc = '$khoahoc' AND hocki = '$hocki'";
    $monhoc_result = mysqli_query($con, $sql_monhoc);
}

// Sửa môn học
if (isset($_POST['edit_course'])) {
    $mamon = $_POST['mamon'];
    $tenmon = $_POST['tenmon'];
    $sotinchi = $_POST['sotinchi'];

    $sql_update = "UPDATE mon_hoc SET tenmon = '$tenmon', sotinchi = '$sotinchi' WHERE mamon = '$mamon'";
    mysqli_query($con, $sql_update);
}

// Xóa môn học
if (isset($_POST['delete_course'])) {
    $mamon = $_POST['mamon'];

    $sql_delete = "DELETE FROM mon_hoc WHERE mamon = '$mamon'";
    mysqli_query($con, $sql_delete);
}
?>

<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
<link rel="stylesheet" href="../teacher_info.css">
<link rel="stylesheet" href="../teacher_homepage.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                <a href="../teacher_infosv.php">
                    <span class="icon"><i class="fa-solid fa-circle-exclamation"></i></span>
                    <span class="text">Quản lý sinh viên</span>
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
                <a href="../teacher_listgv.php">
                    <span class="icon"><i class="fa-solid fa-list"></i></span>
                    <span class="text">Danh sách quản lý</span>
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
    <div class="container mt-4">
        <form method="POST" action="">
            <div class="form-group">
                <label for="khoahoc_hocki">Khóa học và học kỳ:</label>
                <select class="form-control" id="khoahoc_hocki" name="khoahoc_hocki" required>
                    <option value="">-- Chọn khóa học và học kỳ --</option>
                    <?php
                    $result_khoahoc_hocki = mysqli_query($con, $sql_khoahoc_hocki);
                    while ($row = mysqli_fetch_assoc($result_khoahoc_hocki)) {
                        $value = $row['khoahoc'] . '|' . $row['hocki'];
                        $display = $row['khoahoc'] . ' - Học kỳ ' . $row['hocki'];
                        $selected = ($value == $khoahoc_hocki) ? 'selected' : '';
                        echo "<option value='$value' $selected>$display</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Xem thông tin môn học</button>
        </form>
    </div>

    <!-- Hiển thị thông tin môn học -->
    <form method="POST" action="" ></form>
        <div class="container mt-4">
            <?php if ($monhoc_result && mysqli_num_rows($monhoc_result) > 0): ?>
                <h4>Danh sách môn học:</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mã môn</th>
                            <th>Tên môn</th>
                            <th>Số tín chỉ</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($monhoc_result)): ?>
                            <tr>
                                <td><?= $row['mamon'] ?></td>
                                <td><?= $row['tenmon'] ?></td>
                                <td><?= $row['sotinchi'] ?></td>
                                <td>
                                    <!-- Form sửa môn học -->
                                    <form method="POST" action="" style="display:inline-block;">
                                        <input type="hidden" name="mamon" value="<?= $row['mamon'] ?>">
                                        <input type="hidden" name="current_khoahoc_hocki" value="<?= $khoahoc_hocki ?>">
                                        <input type="text" name="tenmon" value="<?= $row['tenmon'] ?>" required>
                                        <input type="number" name="sotinchi" value="<?= $row['sotinchi'] ?>" required>
                                        <button type="submit" name="edit_course" class="btn btn-warning btn-sm">Sửa</button>
                                    </form>

                                    <!-- Form xóa môn học -->
                                    <form method="POST" action="" style="display:inline-block;">
                                        <input type="hidden" name="mamon" value="<?= $row['mamon'] ?>">
                                        <input type="hidden" name="current_khoahoc_hocki" value="<?= $khoahoc_hocki ?>">
                                        <button type="submit" name="delete_course" class="btn btn-danger btn-sm">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php elseif (isset($_POST['submit']) || isset($_POST['edit_course']) || isset($_POST['delete_course'])): ?>
                <p>Không có môn học nào cho khóa học và học kỳ đã chọn.</p>
            <?php endif; ?>
        </div>
    </form>
        <button><a href="addcourse.php">Thêm mới môn học</a></button>
    </article>
</body>

</html>