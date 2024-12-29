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
$mamon = '';
$chucvu = '';
$hocvan = '';
$malop = '';

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
        $mamon = $row['mamon'];
        $chucvu = $row['chucvu'];
        $hocvan = $row['hocvan'];
        $malop = $row['malop'];
    }
}

if (isset($_POST['btnluu'])) {
    $hoten = $_POST['txthoten'];
    $ns = $_POST['txtns'];
    $diachi = $_POST['txtdiachi'];
    $sdt = $_POST['txtsdt'];
    $email = $_POST['txtemail'];
    $gioitinh = $_POST['txtgioitinh'];
    $mamon = $_POST['txtmamon'];
    $chucvu = $_POST['txtchucvu'];
    $hocvan = $_POST['txthocvan'];
    $malop = $_POST['txtmalop'];

    $sql1 = "UPDATE giang_vien SET 
        hoten = '$hoten',
        ns = '$ns',
        diachi = '$diachi',
        sdt = '$sdt',
        email = '$email',
        gioitinh = '$gioitinh',
        mamon = '$mamon',
        chucvu = '$chucvu',
        hocvan = '$hocvan',
        malop = '$malop'
        WHERE ma = '$ma'";
    $sql2 = "UPDATE user SET email = '$email' WHERE ma = '$user_id'";
    if (mysqli_query($con, $sql1) && mysqli_query($con, $sql2)) {
    }
}
?>

<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                <a href="teacher_fix.php">
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
        <div class="container mt-4">
            <form method="POST" action="">
                <div class="form-group">
                    <div class="form-group row mb-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <label for="txtma" class="form-label me-3 mb-0" style="min-width: 100px;">Mã giảng
                                    viên</label>
                                <input type="text" class="form-control" name="txtma" placeholder="Nhập mã"
                                    value="<?php echo $ma; ?>" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <label for="txthoten" class="form-label me-3 mb-0" style="min-width: 100px;">Họ và
                                    tên</label>
                                <input type="text" class="form-control" name="txthoten" placeholder="Nhập họ tên"
                                    value="<?php echo $hoten; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <label for="txtns" class="form-label me-3 mb-0" style="min-width: 100px;">Ngày
                                    sinh</label>
                                <input type="date" class="form-control" name="txtns" placeholder="Nhập ngày sinh"
                                    value="<?php echo $ns; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <label for="txtdiachi" class="form-label me-3 mb-0" style="min-width: 100px;">Địa
                                    chỉ</label>
                                <input type="text" class="form-control" name="txtdiachi" placeholder="Nhập địa chỉ"
                                    value="<?php echo $diachi; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <label for="txtsdt" class="form-label me-3 mb-0" style="min-width: 100px;">Số điện
                                    thoại</label>
                                <input type="text" class="form-control" name="txtsdt" placeholder="Nhập số điện thoại"
                                    value="<?php echo $sdt; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <label for="txtemail" class="form-label me-3 mb-0"
                                    style="min-width: 100px;">Email</label>
                                <input type="text" class="form-control" name="txtemail" placeholder="Nhập email"
                                    value="<?php echo $email; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <label for="txtgioitinh" class="form-label me-3 mb-0" style="min-width: 100px;">Giới
                                    tính</label>
                                <select class="form-select custom-select" name="txtgioitinh">
                                    <option value="Nam" <?php echo ($gioitinh == 'Nam') ? 'selected' : ''; ?>>Nam</option>
                                    <option value="Nữ" <?php echo ($gioitinh == 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <label for="txtmamon" class="form-label me-3 mb-0" style="min-width: 100px;">Mã
                                    môn</label>
                                <input type="text" class="form-control" name="txtmamon" placeholder="Nhập mã môn"
                                    value="<?php echo $mamon; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <label for="txtchucvu" class="form-label me-3 mb-0" style="min-width: 100px;">Chức
                                    vụ</label>
                                <input type="text" class="form-control" name="txtchucvu" placeholder="Nhập chức vụ"
                                    value="<?php echo $chucvu; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <label for="txtchucvu" class="form-label me-3 mb-0" style="min-width: 100px;">Học
                                    vấn</label>
                                <input type="text" class="form-control" name="txthocvan" placeholder="Nhập học vấn"
                                    value="<?php echo $hocvan; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <label for="txtchucvu" class="form-label me-3 mb-0" style="min-width: 100px;">Mã
                                    lớp</label>
                                <input type="text" class="form-control" name="txtmalop" placeholder="Nhập mã lớp"
                                    value="<?php echo $malop; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-md-6">
                            <button type="submit" name="btnluu" class="btn btn-success me-2 hidden">Lưu</button>
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
                    <style>
                        .hidden {
                            display: none;
                        }
                    </style>
                </div>
            </form>
        </div>
    </article>
</body>

</html>