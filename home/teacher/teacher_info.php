<?php
include_once "connectdb.php";
session_start();
$ma = '';
$hoten = '';
$ns = '';
$diachi = '';
$sdt = '';
$email = '';
$gioitinh = '';
$chucvu = '';
$hocvan = '';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM giang_vien WHERE ma = '$user_id'";
    $result = mysqli_query($con, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $ma = $row['ma'];
        $hoten = $row['hoten'];
        $ns = $row['ns'];
        $diachi = $row['diachi'];
        $sdt = $row['sdt'];
        $email = $row['email'];
        $gioitinh = $row['gioitinh'];
        $chucvu = $row['chucvu'];
        $hocvan = $row['hocvan'];
    }
}

if (isset($_POST['btnluu'])) {
    $ns = $_POST['txtns'];
    $diachi = $_POST['txtdiachi'];
    $sdt = $_POST['txtsdt'];
    $email = $_POST['txtemail'];
    $gioitinh = $_POST['txtgioitinh'];
    $chucvu = $_POST['txtchucvu'];
    $hocvan = $_POST['txthocvan'];

    $sql = "UPDATE giang_vien SET 
            ns = '$ns',
            diachi = '$diachi',
            sdt = '$sdt',
            email = '$email',
            gioitinh = '$gioitinh',
            chucvu = '$chucvu',
            hocvan = '$hocvan'
            WHERE ma = '$ma'";
    $sql2 = "UPDATE user SET email = '$email' WHERE ma = '$ma'";
    $stm = mysqli_query($con, $sql);
    $stm2 = mysqli_query($con, $sql2);
}
?>

<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
<link rel="stylesheet" href="teacher_info.css">
<link rel="stylesheet" href="teacher_homepage.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<title>Quản lý điểm sinh viên đại học</title>

<body>
    <!-- header -->
    <div class="header">
        <span class="header-text">Thông tin cá nhân</span>
        <span class="header-icon"><i class="fa-solid fa-circle-user"></i></span>
        <?php
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $sql = "SELECT hoten FROM giang_vien WHERE ma = '$user_id'";
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
        <div class="container mt-4">
            <form method="POST" action="">
                <div class="form-row">
                    <div class="input-group">
                        <i class="fas fa-id-card"></i>
                        <div class="form-field">
                            <label class="label">Mã giảng viên</label>
                            <input class="info1" type="text" name="txtma" value="<?php echo $ma; ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <div class="form-field">
                            <label class="label">Họ và tên</label>
                            <input class="info1" type="text" name="txthoten" value="<?php echo $hoten; ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group">
                        <i class="fas fa-calendar"></i>
                        <div class="form-field">
                            <label class="label">Ngày sinh</label>
                            <input class="info1" type="date" name="txtns" value="<?php echo $ns; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group">
                        <i class="fas fa-venus-mars"></i>
                        <div class="form-field">
                            <label class="label">Giới tính</label>
                            <select class="info1" name="txtgioitinh">
                                <option value="Nam" <?php if ($gioitinh == "Nam")
                                    echo "selected"; ?>>Nam</option>
                                <option value="Nữ" <?php if ($gioitinh == "Nữ")
                                    echo "selected"; ?>>Nữ</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group full-width">
                        <i class="fas fa-home"></i>
                        <div class="form-field">
                            <label class="label">Địa chỉ</label>
                            <input class="info1" type="text" name="txtdiachi" value="<?php echo $diachi; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group">
                        <i class="fas fa-phone"></i>
                        <div class="form-field">
                            <label class="label">Số điện thoại</label>
                            <input class="info1" type="tel" name="txtsdt" value="<?php echo $sdt; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <div class="form-field">
                            <label class="label">Email</label>
                            <input class="info1" type="email" name="txtemail" value="<?php echo $email; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group">
                        <i class="fas fa-book"></i>
                        <div class="form-field">
                            <label class="label">Học vấn</label>
                            <input class="info1" type="text" name="txthocvan" value="<?php echo $hocvan; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="input-group full-width">
                        <i class="fa-solid fa-briefcase"></i>
                        <div class="form-field">
                            <label class="label">Chức vụ</label>
                            <input class="info1" type="text" name="txtchucvu" value="<?php echo $chucvu; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-md-6">
                        <button type="submit" name="btnluu" class="button_slide slide_right hidden"><i
                                class="fa-solid fa-floppy-disk"></i> Lưu</button>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const inputs = document.querySelectorAll('input, select');
                        const saveButton = document.querySelector('button[name="btnluu"]');
                        inputs.forEach(input => {
                            input.addEventListener('input', function () {
                                saveButton.classList.remove('hidden');
                            });
                        });
                    });
                </script>
            </form>
        </div>
    </article>
</body>

</html>