<?php
include_once "connectdb.php";
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
    header('location: ./chức năng của qlsv/teacher_add_qlsv.php');
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
        <form method="POST" action="">
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
                style="margin-left:425px; margin-top:10px; margin-bottom: 10px">Tìm
                kiếm</button>
            <button type="submit" class="btn btn-primary" name="btnThemmoi"
                style="margin-left:150px; margin-top:10px; margin-bottom: 10px">Thêm
                mới</button>
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
                                <a href="./chức năng của qlsv/teacher_fix_qlsv.php?ma=<?php echo $row['ma'] ?>"
                                    class="btn btn-light"">Sửa</a>
                                <a href=" ./chức năng của qlsv/teacher_del_qlsv.php?ma=<?php echo $row['ma'] ?>"
                            onclick="return confirm('Bạn có chắc chắn muốn xóa không ?')" class="btn btn-danger" ">Xoá</a>
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