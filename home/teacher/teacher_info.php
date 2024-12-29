<?php
include_once "connectdb.php";
session_start();
<<<<<<< Updated upstream
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
=======
$ma='';$hoten='';$ns='';$diachi='';$sdt='';$email='';$gioitinh='';$mamon='';$chucvu='';$hocvan='';$malop='';

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM giangvien WHERE ma = '$user_id'";
    $result = mysqli_query($con, $sql);
    if($row = mysqli_fetch_assoc($result)){
>>>>>>> Stashed changes
        $ma = $row['ma'];
        $hoten = $row['hoten'];
        $ns = $row['ns'];
        $diachi = $row['diachi'];
        $sdt = $row['sdt'];
        $email = $row['email'];
        $gioitinh = $row['gioitinh'];
<<<<<<< Updated upstream
        $chucvu = $row['chucvu'];
        $hocvan = $row['hocvan'];
    }
}

if(isset($_POST['btnluu'])) {
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
=======
        $mamon = $row['mamon'];
        $chucvu = $row['chucvu'];
        $hocvan = $row['hocvan'];
        $malop = $row['malop'];
    }
}

$sql1 ="SELECT giangvien.hoten, user.hoten
FROM giangvien
INNER JOIN user ON giangvien.hoten = user.hoten";

if(isset($_POST['btnluu'])) {
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
}
$sql = "UPDATE giangvien SET 
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

mysqli_multi_query($con, $sql);
>>>>>>> Stashed changes
?>

<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
<link rel="stylesheet" href="teacher_homepage.css">
<<<<<<< Updated upstream
<link rel="stylesheet" href="teacher_info.css">
=======
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
            $sql = "SELECT hoten FROM giang_vien WHERE ma = '$user_id'";
=======
            $sql = "SELECT hoten FROM giangvien WHERE ma = '$user_id'";
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
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
                                <option value="Nam" <?php if($gioitinh=="Nam") echo "selected"; ?>>Nam</option>
                                <option value="Nữ" <?php if($gioitinh=="Nữ") echo "selected"; ?>>Nữ</option>
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
                        <i class="fas fa-home"></i>
                        <div class="form-field">
                            <label class="label">Chức vụ</label>
                            <input class="info1" type="text" name="txtchucvu" value="<?php echo $chucvu; ?>">
=======
        <form method="POST" action="">
            <div class="form-group">
                <div class="form-group row mb-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <label for="txtma" class="form-label me-3 mb-0" style="min-width: 100px;">Mã giảng viên</label>
                            <input type="text" class="form-control" name="txtma" placeholder="Nhập mã" value="<?php echo  $ma; ?>" disabled>
>>>>>>> Stashed changes
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-md-6">
<<<<<<< Updated upstream
                        <button type="submit" name="btnluu" class="button_slide slide_right hidden"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
=======
                        <div class="d-flex align-items-center">
                            <label for="txthoten" class="form-label me-3 mb-0" style="min-width: 100px;">Họ và tên</label>
                            <input type="text" class="form-control" name="txthoten" placeholder="Nhập họ tên" value="<?php echo $hoten; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <label for="txtns" class="form-label me-3 mb-0" style="min-width: 100px;">Ngày sinh</label>
                            <input type="date" class="form-control" name="txtns" placeholder="Nhập ngày sinh" value="<?php echo $ns; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <label for="txtdiachi" class="form-label me-3 mb-0" style="min-width: 100px;">Địa chỉ</label>
                            <input type="text" class="form-control" name="txtdiachi" placeholder="Nhập địa chỉ" value="<?php echo $diachi; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <label for="txtsdt" class="form-label me-3 mb-0" style="min-width: 100px;">Số điện thoại</label>
                            <input type="text" class="form-control" name="txtsdt" placeholder="Nhập số điện thoại" value="<?php echo $sdt; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <label for="txtemail" class="form-label me-3 mb-0" style="min-width: 100px;">Email</label>
                            <input type="text" class="form-control" name="txtemail" placeholder="Nhập email" value="<?php echo $email; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <label for="txtgioitinh" class="form-label me-3 mb-0" style="min-width: 100px;">Giới tính</label>
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
                            <label for="txtmamon" class="form-label me-3 mb-0" style="min-width: 100px;">Mã môn</label>
                            <input type="text" class="form-control" name="txtmamon" placeholder="Nhập mã môn" value="<?php echo $mamon; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <label for="txtchucvu" class="form-label me-3 mb-0" style="min-width: 100px;">Chức vụ</label>
                            <input type="text" class="form-control" name="txtchucvu" placeholder="Nhập chức vụ" value="<?php echo $chucvu; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <label for="txtchucvu" class="form-label me-3 mb-0" style="min-width: 100px;">Học vấn</label>
                            <input type="text" class="form-control" name="txthocvan" placeholder="Nhập học vấn" value="<?php echo $hocvan; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <label for="txtchucvu" class="form-label me-3 mb-0" style="min-width: 100px;">Mã lớp</label>
                            <input type="text" class="form-control" name="txtmalop" placeholder="Nhập mã lớp" value="<?php echo $malop; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-md-6">
                        <button type="submit" name="btnluu" class="btn btn-success me-2 hidden">Lưu</button>
>>>>>>> Stashed changes
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const inputs = document.querySelectorAll('input, select');
                        const saveButton = document.querySelector('button[name="btnluu"]');
<<<<<<< Updated upstream
                            inputs.forEach(input => {
                            input.addEventListener('input', function () {
                                saveButton.classList.remove('hidden');
                                    });
                                });
                            });
                </script>
            </form>
        </div>
=======

                        inputs.forEach(input => {
                            input.addEventListener('input', function() {
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
>>>>>>> Stashed changes
    </article>
</body>
</html>