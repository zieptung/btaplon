<?php
include_once "../connectdb.php";
$ht = "";
$tm = "";

$sql = "SELECT * FROM diem";
if (isset($_POST['btnTimkiem'])) {
    $ht = $_POST['txthoten'];
    $tm = $_POST['txttenmon'];
    $sql = "SELECT * FROM diem WHERE hoten LIKE '%$ht%' AND tenmon LIKE '%$tm%'";
    $ht = "";
    $tm = "";
}
$data = mysqli_query($con, $sql);
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
        <form method="post" action="">
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="row">
                    <div class="col" style="margin:10px">
                        <label>Tên sinh viên</label>
                        <input type="text" class="form-control" placeholder="Tên sinh viên" name="txthoten"
                            value="<?php echo $ht ?>">
                    </div>
                    <div class="col" style="margin:10px">
                        <label>Tên học phần</label>
                        <input type="text" class="form-control" placeholder="Tên học phần" name="txttenmon"
                            value="<?php echo $tm ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" name="btnTimkiem"
                    style="margin-left:550px; margin-top:10px; margin-bottom: 10px; margin-right: 60px">Tìm
                    kiếm</button>
                <table class="table table-bordered" style="background-color: #3F72AF; color: #F9F7F7;">
                    <thead style="background-color: #1B262C; color: #FADA7A; text-align: center;">
                        <tr>
                            <th>STT</th>
                            <th>Mã học phần</th>
                            <th>Tên sinh viên</th>
                            <th>Tên học phần</th>
                            <th>Số tín chỉ</th>
                            <th>Điểm số</th>
                            <th>Điểm chữ</th>
                            <th>Điểm chuyên cần</th>
                            <th>Điểm giữa kỳ</th>
                            <th>Điểm cuối kỳ</th>
                            <th>Điểm tổng</th>
                            <th>Loại</th>
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
                                    <td><?php echo $row['mamon'] ?></td>
                                    <td><?php echo $row['hoten'] ?></td>
                                    <td><?php echo $row['tenmon'] ?></td>
                                    <td><?php echo $row['sotinchi'] ?></td>
                                    <td><?php echo $row['diemso'] ?></td>
                                    <td><?php echo $row['diemchu'] ?></td>
                                    <td><?php echo $row['diemcc'] ?></td>
                                    <td><?php echo $row['diemgk'] ?></td>
                                    <td><?php echo $row['diemck'] ?></td>
                                    <td><?php echo $row['diemtong'] ?></td>
                                    <td><?php echo $row['loai'] ?></td>
                                    <td>
                                        <a href="./manager_diemsv/teacher_fix_diemsv.php?mamon=<?php echo $row['mamon'] ?>"
                                            class="btn btn-light"><i class=" fa-solid fa-wrench"></i></a>
                                        <a href="./manager_diemsv/teacher_del_diemsv.php?mamon=<?php echo $row['mamon'] ?>"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa không ?')"
                                            class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
    </article>
</body>

</html>