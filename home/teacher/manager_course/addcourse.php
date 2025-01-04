<?php
include_once "../connectdb.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mamon = $_POST['mamon'];
    $tenmon = $_POST['tenmon'];
    $sotinchi = $_POST['sotinchi'];
    $khoahoc = $_POST['khoahoc'];
    $hocki = $_POST['hocki'];

    $sql_insert = "INSERT INTO mon_hoc (mamon, tenmon, sotinchi, khoahoc, hocki) VALUES ('$mamon', '$tenmon', '$sotinchi', '$khoahoc', '$hocki')";
    if (mysqli_query($con, $sql_insert)) {
        header("Location: teacher_course_update.php");
    } else {
        echo "Lỗi: " . mysqli_error($con);
    }
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
    <div class="row">
        <h4>Thêm mới môn học:</h4>
        <form method="POST" action="">
            <div class="form-group">
                <label for="mamon">Mã môn:</label>
                <input type="text" class="form-control" id="mamon" name="mamon" required>
            </div>
            <div class="form-group">
                <label for="tenmon">Tên môn:</label>
                <input type="text" class="form-control" id="tenmon" name="tenmon" required>
            </div>
            <div class="form-group">
                <label for="sotinchi">Số tín chỉ:</label>
                <input type="number" class="form-control" id="sotinchi" name="sotinchi" required>
            </div>
            <div class="form-group">
                <label for="khoahoc">Khóa học:</label>
                <input type="text" class="form-control" id="khoahoc" name="khoahoc" required>
            </div>
            <div class="form-group">
                <label for="hocki">Học kỳ:</label>
                <input type="number" class="form-control" id="hocki" name="hocki" required>
            </div>
            <button type="submit" class="btn btn-success">Thêm môn học</button>
            <a href="teacher_course.php" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
    </article>
</body>