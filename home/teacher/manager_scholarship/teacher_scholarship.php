<?php
include_once "../connectdb.php";
session_start();

// Khởi tạo giá trị mặc định
$hocky = isset($_POST['hocky']) ? $_POST['hocky'] : '';
$khoahoc = isset($_POST['khoahoc']) ? $_POST['khoahoc'] : '';

// Truy vấn để lấy danh sách sinh viên đạt học bổng từ bảng diem và mon_hoc nếu đã chọn kỳ và niên khóa
$data = null;
if ($hocky && $khoahoc) {
    $search_sql = "SELECT user.ma, user.hoten, SUM(mon_hoc.sotinchi) AS tong_tc, 
                    AVG(diem.diemso * mon_hoc.sotinchi) / SUM(mon_hoc.sotinchi) AS diem_tb, 
                    khoa_hoc.hocky, khoa_hoc.khoahoc
                   FROM user
                   LEFT JOIN diem ON user.ma = diem.ma
                   LEFT JOIN mon_hoc ON diem.mamon = mon_hoc.mamon
                   LEFT JOIN lop_hoc ON user.tenlop = lop_hoc.tenlop
                   LEFT JOIN khoa_hoc ON user.khoahoc = khoa_hoc.khoahoc
                   WHERE user.is_admin = 0
                     AND khoa_hoc.hocky = '$hocky'
                     AND khoa_hoc.khoahoc = '$khoahoc'
                   GROUP BY user.ma, khoa_hoc.hocky, khoa_hoc.khoahoc
                   HAVING diem_tb >= 3.2
                   ORDER BY khoa_hoc.hocky, khoa_hoc.khoahoc, diem_tb DESC";

    $data = mysqli_query($con, $search_sql);

    if (!$data) {
        die("Lỗi truy vấn: " . mysqli_error($con));
    }
}

?>

<!DOCTYPE html>
<html>

<head>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <link rel="stylesheet" href="../teacher_info.css">
   <link rel="stylesheet" href="../teacher_homepage.css">
   <title>Danh sách học bổng sinh viên</title>
</head>

<body>
   <!-- header -->
   <div class="header">
      <span class="header-text">Danh sách học bổng</span>
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
            <a href="teacher_class.php">
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
      <!-- Form chọn kỳ và niên khóa -->
      <form method="POST" action="">
         <div class="form-group">
            <label for="hocky">Chọn kỳ:</label>
            <select class="form-control" id="hocky" name="hocky">
               <option value="">Chọn kỳ</option>
               <?php
                    // Truy vấn để lấy danh sách học kỳ từ bảng khoa_hoc
                    $hocky_sql = "SELECT DISTINCT hocky FROM khoa_hoc ORDER BY hocky";
                    $hocky_data = mysqli_query($con, $hocky_sql);
                    if ($hocky_data && mysqli_num_rows($hocky_data) > 0) {
                        while ($row = mysqli_fetch_assoc($hocky_data)) {
                            echo "<option value='{$row['hocky']}' " . (($hocky == $row['hocky']) ? 'selected' : '') . ">{$row['hocky']}</option>";
                        }
                    }
                    ?>
            </select>
         </div>
         <div class="form-group">
            <label for="khoahoc">Chọn niên khóa:</label>
            <select class="form-control" id="khoahoc" name="khoahoc">
               <option value="">Chọn niên khóa</option>
               <?php
                    // Truy vấn để lấy danh sách niên khóa từ bảng khoa_hoc
                    $khoahoc_sql = "SELECT DISTINCT khoahoc FROM khoa_hoc ORDER BY khoahoc";
                    $khoahoc_data = mysqli_query($con, $khoahoc_sql);
                    if ($khoahoc_data && mysqli_num_rows($khoahoc_data) > 0) {
                        while ($row = mysqli_fetch_assoc($khoahoc_data)) {
                            echo "<option value='{$row['khoahoc']}' " . (($khoahoc == $row['khoahoc']) ? 'selected' : '') . ">{$row['khoahoc']}</option>";
                        }
                    }
                    ?>
            </select>
         </div>
         <button type="submit" class="btn btn-primary">Xem danh sách</button>
      </form>

      <!-- Bảng hiển thị danh sách học bổng -->
      <table class="table table-bordered" style="background-color: #3F72AF; color: #F9F7F7;">
         <thead style="background-color: #1B262C; color: #FADA7A; text-align: center;">
            <tr>
               <th>STT</th>
               <th>Mã sinh viên</th>
               <th>Tên sinh viên</th>
               <th>Tổng số tín chỉ</th>
               <th>Điểm trung bình (hệ số 4)</th>
               <th>Loại học bổng</th>
            </tr>
         </thead>
         <tbody style="text-align: center;">
            <?php
                if ($data && mysqli_num_rows($data) > 0) {
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($data)) {
                        $loai_hb = ($row['diem_tb'] > 3.6) ? 'Xuất sắc' : 'Giỏi';
                        ?>
            <tr>
               <td><?php echo $i++ ?></td>
               <td><?php echo $row['ma'] ?></td>
               <td><?php echo $row['hoten'] ?></td>
               <td><?php echo $row['tong_tc'] ?></td>
               <td><?php echo $row['diem_tb'] ?></td>
               <td><?php echo $loai_hb ?></td>
            </tr>
            <?php
                    }
                } else {
                    echo "<tr><td colspan='6'>Không có sinh viên nào đạt học bổng.</td></tr>";
                }
                ?>
         </tbody>
      </table>
   </article>
</body>

</html>