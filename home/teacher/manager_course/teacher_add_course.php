<?php
include_once "../connectdb.php";
$mm = $tm = $stc = $khoahoc = $hocky = "";
if (isset($_POST['btnLuu'])) {
    $mm = $_POST['txtmamon'];
    $tm = $_POST['txttenmon'];
    $stc = $_POST['txtstc'];
    $khoahoc = $_POST['txtkhoahoc'];
    $hocky = $_POST['txthocky'];

    $sql_insert = "INSERT INTO mon_hoc (mamon, tenmon, sotinchi, khoahoc, hocky) VALUES ('$mm', '$tm', '$stc', '$khoahoc', '$hocky')";

    $sql_check = "SELECT * FROM mon_hoc WHERE mamon = '$mm'";
    $result_check = mysqli_query($con, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        echo "<script>alert('Môn học đã tồn tại');</script>";
    } else {
        if (mysqli_query($con, $sql_insert)) {
            echo "<script>alert('Thêm môn học thành công'); window.location.href = './teacher_course.php'; </script>";

        }
    }
}
if (isset($_POST['btnBack'])) {
    header("location: teacher_course.php");
}
?>

<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="../teacher_info.css">
<link rel="stylesheet" href="../teacher_homepage.css">
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
        <form method="post" action="">
            <div class="form-group" style="width: 75%; margin-left: 150px; margin-top: 50px; margin-bottom: 10px;">
                <div class="input-group" style="margin-top: 20px;">
                    <i class="fa-solid fa-arrow-right"></i>
                    <div class="form-field">
                        <label>Mã học phần</label>
                        <input class="info1" type="text" name="txtmamon" value="<?php echo $mm; ?>"
                            placeholder="Mã học phần">
                    </div>
                </div>
                <div class="input-group" style="margin-top: 20px;">
                    <i class="fa-solid fa-arrow-right"></i>
                    <div class="form-field">
                        <label>Tên học phần</label>
                        <input class="info1" type="text" name="txttenmon" value="<?php echo $tm; ?>"
                            placeholder="Tên học phần">
                    </div>
                </div>
                <div class="input-group" style="margin-top: 20px;">
                    <i class="fa-solid fa-arrow-right"></i>
                    <div class="form-field">
                        <label>Số tín chỉ</label>
                        <input class="info1" type="text" name="txtstc" value="<?php echo $stc; ?>"
                            placeholder="Số tín chỉ">
                    </div>
                </div>
                <div class="input-group" style="margin-top: 20px;">
                    <i class="fa-solid fa-arrow-right"></i>
                    <div class="form-field">
                        <label>Khoá học</label>
                        <input class="info1" type="text" name="txtkhoahoc" value="<?php echo $khoahoc; ?>"
                            placeholder="Khoá học">
                    </div>
                </div>
                <div class="input-group" style="margin-top: 20px;">
                    <i class="fa-solid fa-arrow-right"></i>
                    <div class="form-field">
                        <label>Học kỳ</label>
                        <input class="info1" type="text" name="txthocky" value="<?php echo $hocky; ?>"
                            placeholder="Học kỳ">
                    </div>
                </div>
                <button type="submit" class="btn btn-success" name="btnLuu"
                    style="margin-left:300px; margin-top:10px">Lưu</button>
                <button type="submit" class="btn btn-info" name="btnBack" style="margin-left:150px; margin-top:10px">Trở
                    về</button>
            </div>
            </div>
    </article>
</body>