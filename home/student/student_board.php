<?php
include_once "../connectdb.php";
session_start();

if (!isset($_SESSION['user_id'])) {
   header("Location: student_login.php");
   exit();
}

$user_id = $_SESSION['user_id'];


// Truy vấn SQL để chọn tất cả các bản ghi từ bảng 'diem' nơi cột 'ma' khớp với ID người dùng đã cho.
$sql = "SELECT * FROM diem WHERE ma = '$user_id'";
$data = mysqli_query($con, $sql);

$tm = '';
if (isset($_POST['btnTimkiem'])) {
   $tm = $_POST['txttenmon'];
   $sql = "SELECT d.*, mh.tenmon, u.hoten FROM diem d JOIN mon_hoc mh ON d.mamon = mh.mamon JOIN user u ON d.ma = u.ma WHERE d.ma = '$user_id' AND mh.tenmon LIKE '%$tm%'";
   $tm = '';
   $data = mysqli_query($con, $sql);
}

$sortOrder = "DESC"; //Biến sắp xếp mặc định
if (isset($_POST['sortOrder'])) {
   $sortOrder = $_POST['sortOrder'];
}
if (isset($_POST['btnSapxep'])) {
   $sql = "SELECT d.*, mh.tenmon, u.hoten FROM diem d JOIN mon_hoc mh ON d.mamon = mh.mamon JOIN user u ON d.ma = u.ma WHERE d.ma = '$user_id' ORDER BY d.diemtong $sortOrder";
   $data = mysqli_query($con, $sql);
}

?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="student_homepage.css">
<link rel="stylesheet" href="student_info.css">
<title>Quản lý điểm sinh viên đại học</title>
<style>
   .sidebar {
      overflow: hidden;
   }
</style>

<body>
   <!-- header -->
   <div class="header">
      <span class="header-text">Bảng điểm</span>
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
   <!-- content -->
   <article class="content">
      <form action="" method="post">
         <div class="row">
            <div class="col" style="margin:10px">
               <div class="input-group full-width">
                  <i class="fa-solid fa-book"></i>
                  <div class="form-field">
                     <input class="info1" type="text" name="txttenmon" value="<?php echo $tm; ?>"
                        placeholder="Tên học phần">
                  </div>
               </div>
            </div>
            <div class="col" style="margin:10px">
               <div class="input-group full-width">
                  <i class="fa-solid fa-arrow-up-wide-short"></i>
                  <div class="form-field">
                     <label for="sortOrder">Sắp xếp</label>
                     <select class="info1" name="sortOrder">
                        <option value="DESC" <?php if ($sortOrder == "DESC")
                           echo "selected"; ?>>Giảm dần</option>
                        <option value="ASC" <?php if ($sortOrder == "ASC")
                           echo "selected"; ?>>Tăng dần</option>
                     </select>
                  </div>
               </div>
            </div>
         </div>
         <button type="submit" class="btn btn-info" name="btnTimkiem"
            style="margin-left:390px; margin-top:10px; margin-bottom: 10px; margin-right: 60px">Tìm
            kiếm</button>
         <button class="btn btn-info" type="submit" name="btnSapxep" style="margin-left: 360px">Sắp xếp</button>
      </form>
      <table class="table table-bordered" style="background-color: #3F72AF; color: #F9F7F7;">
         <thead style="background-color: #1B262C; color: #FADA7A; text-align: center;">
            <tr>
               <th>STT</th>
               <th>Mã học phần</th>
               <th>Tên sinh viên</th>
               <th>Mã sinh viên</th>
               <th>Tên học phần</th>
               <th>Số tín chỉ</th>
               <th>Điểm số</th>
               <th>Điểm chữ</th>
               <th>Điểm chuyên cần</th>
               <th>Điểm giữa kỳ</th>
               <th>Điểm cuối kỳ</th>
               <th>Điểm tổng</th>
               <th>Loại</th>
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
                     <td><?php echo $row['ma'] ?></td>
                     <td><?php echo $row['tenmon'] ?></td>
                     <td><?php echo $row['sotinchi'] ?></td>
                     <td><?php echo $row['diemso'] ?></td>
                     <td><?php echo $row['diemchu'] ?></td>
                     <td><?php echo $row['diemcc'] ?></td>
                     <td><?php echo $row['diemgk'] ?></td>
                     <td><?php echo $row['diemck'] ?></td>
                     <td><?php echo $row['diemtong'] ?></td>
                     <td><?php echo $row['loai'] ?></td>
                  </tr>
                  <?php
               }
            }
            ?>
         </tbody>
      </table>
   </article>
   <!-- sidebar -->
   <div class="sidebar">
      <ul>
         <li>
            <a href="student_homepage.php" class="logo">
               <span class="icon"><i class="fa-solid fa-house"></i></span>
               <span class="text">Trang chủ</span>
            </a>
         </li>
         <li>
            <a href="student_message.php">
               <span class="icon"><i class="fa-solid fa-envelope"></i></span>
               <span class="text">Tin nhắn</span>
            </a>
         </li>
         <li>
            <a href="student_forum.php">
               <span class="icon"><i class="fa-solid fa-bell"></i></span>
               <span class="text">Diễn đàn</span>
            </a>
         </li>
         <li>
            <a href="student_board.php">
               <span class="icon"><i class="fa-solid fa-table"></i></span>
               <span class="text">Bảng điểm</span>
            </a>
         </li>
         <li>
            <a href="student_info.php">
               <span class="icon"><i class="fa-solid fa-user"></i></span>
               <span class="text">Thông tin sinh viên</span>
            </a>
         </li>
         <li>
            <a href="student_logout.php">
               <span class="icon"><i class="fa-solid fa-right-from-bracket"></i></span>
               <span class="text">Đăng xuất</span>
            </a>
         </li>
      </ul>
   </div>
</body>

</html>