<?php
include_once("connectdb.php");
$mm = '';
$tm = '';
$stc = '';
$bm = '';
$ds = '';
$dc = '';
$loai = '';
$cc = '';
$gk = '';
$ck = '';
$diemtong = '';
$sql = 'Select * from mon_hoc';
$lop_hoc = mysqli_query($con, $sql);
$data = $lop_hoc;
if (isset($_POST['timkiem'])) {
    $mm = $_POST['txtmamon'];
    $tm = $_POST['txttenmon'];
    $sql = "SELECT * FROM mon_hoc WHERE mamon like'%$mm%' and tenmon like'%$tm%'";
    $data = mysqli_query($con, $sql);
}
if (isset($_POST['btnnew'])) {
    $mm = isset($_POST['txtmamon']) ? $_POST['txtmamon'] : '';
    $tm = isset($_POST['txttenmon']) ? $_POST['txttenmon'] : '';
    $stc = isset($_POST['txtsotinchi']) ? $_POST['txtsotinchi'] : '';
    $bm = isset($_POST['txtbomon']) ? $_POST['txtbomon'] : '';
    $ds = isset($_POST['txtdiemso']) ? $_POST['txtdiemso'] : '';
    $dc = isset($_POST['txtdiemchu']) ? $_POST['txtdiemchu'] : '';
    $loai = isset($_POST['txtloai']) ? $_POST['txtloai'] : '';
    $cc = isset($_POST['txtdiemcc']) ? $_POST['txtdiemcc'] : '';
    $gk = isset($_POST['txtdiemgk']) ? $_POST['txtdiemgk'] : '';
    $ck = isset($_POST['txtdiemck']) ? $_POST['txtdiemck'] : '';
    $diemtong = isset($_POST['txtdiemtong']) ? $_POST['txtdiemtong'] : '';
    // Thực hiện câu lệnh SQL
    $sql = "INSERT INTO mon_hoc(mamon,tenmon,sotinchi,bomon,diemso,diemcc,loai,diemgk,diemck,diemtong) VALUES('$mm','$tm','$stc','$bm','$ds','$dc','$loai','$cc','$gk','$ck','$diemtong')";
}
if (isset($_POST["btnnew"])) {
    header('location:teacher_them.php');
}
?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="teacher_homepage.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<title>Quản lý điểm sinh viên đại học</title>

<body>
    <!-- header -->
    <div class="header">
        <span class="header-text">Bảng điểm sinh viên</span>
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
        <form method="post" action="">
            <div class="form-group">
                <div>
                    <label for="mamon" style="width: 250px; padding: 10px;">Mã môn</label>
                    <input type="text" class="form-control" id="mamon" name="txtmamon" value="<?php echo $mm ?>">
                </div>
            </div>
            <div class="form-group" style="width: 250px; padding: 10px;">
                <label for="hoten">Tên môn</label>
                <input type="text" class="form-control" id="tenmon" name="txttenmon" value="<?php echo $tm ?>">
            </div>
            <button type="submit" class="btn btn-primary" name="btnnew" style=" margin:10px ">Thêm </button>
            &emsp;&emsp;&emsp;
            <button type="submit" class="btn btn-primary" name="timkiem">Tìm kiếm</button>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã Môn</th>
                        <th>Tên môn</th>
                        <th>Số tín chỉ</th>
                        <th>Bộ môn</th>
                        <th>Điểm số</th>
                        <th>Điểm chữ</th>
                        <th>Loại</th>
                        <th>Điểm chuyên cần</th>
                        <th>Điểm giữa kỳ</th>
                        <th>Điểm cuối kỳ</th>
                        <th>Điểm Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($data) && mysqli_num_rows($data) > 0) {
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($data)) {
                            ?>
                            <tr>
                                <td><?php echo $i++ ?></td>
                                <td><?php echo $row['mamon'] ?></td>
                                <td><?php echo $row['tenmon'] ?></td>
                                <td><?php echo $row['sotinchi'] ?></td>
                                <td><?php echo $row['bomon'] ?></td>
                                <td><?php echo $row['diemso'] ?></td>
                                <td><?php echo $row['diemchu'] ?></td>
                                <td><?php echo $row['loai'] ?></td>
                                <td><?php echo $row['diemcc'] ?></td>
                                <td><?php echo $row['diemgk'] ?></td>
                                <td><?php echo $row['diemck'] ?></td>
                                <td><?php echo $row['diemtong'] ?></td>
                                <td>
                                    <!-- --link chinh sua va xoa -->
                                    <a href="./teacher_fixpoint.php?mamon=<?php echo $row['mamon'] ?>"><i
                                            class="bi bi-pencil-square"></i></a>
                                    &nbsp;&nbsp;&nbsp;
                                    <a href="./teacher_del.php?mamon=<?php echo $row['mamon'] ?>"><i
                                            class="bi bi-trash3-fill"></i></a>
                                <td>

                            </tr>
                            <?php
                        }
                    }
                    ?>
    </article>
</body>

</html>