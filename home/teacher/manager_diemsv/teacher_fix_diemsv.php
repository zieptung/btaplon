<?php
session_start();
include_once "../connectdb.php";
$ma = $_GET['ma'];
$mamon = $_GET['mamon'];

$sql1 = "SELECT * from diem Where ma='$ma' AND mamon = '$mamon' LIMIT 1";
$data = mysqli_query($con, $sql1);

if (isset($_POST["btnLuu"])) {
   $mm = $_POST['txtmamon'];
   $ht = $_POST['txthoten'];
   $tm = $_POST['txttenmon'];
   $stc = $_POST['txtstc'];
   $cc = $_POST['txtdiemcc'];
   $gk = $_POST['txtdiemgk'];
   $ck = $_POST['txtdiemck'];
   $sql = "UPDATE diem SET hoten='$ht', tenmon='$tm', sotinchi='$stc', diemcc='$cc', diemgk='$gk', diemck='$ck' WHERE ma='$ma' AND mamon = '$mamon' LIMIT 1";

   if (mysqli_query($con, $sql)) {
      echo "<script>alert('Cập nhật thành công!'); window.location.href='../teacher_board.php';</script>";
   }
}
if (isset($_POST["btnBack"])) {
   header("location:../teacher_board.php");
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
      <span class="header-text">Thêm điểm sinh viên</span>
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
               <span class="text">Thông tin quản lý</span>
            </a>
         </li>
         <li>
            <a href="../teacher_message.php">
               <span class="icon"><i class="fa-solid fa-envelope"></i></span>
               <span class="text">Tin nhắn</span>
            </a>
         </li>
         <li>
            <a href="../teacher_forum.php">
               <span class="icon"><i class="fa-solid fa-bell"></i></span>
               <span class="text">Diễn đàn</span>
            </a>
         </li>
         <li>
            <a href="../teacher_infosv.php">
               <span class="icon"><i class="fa-solid fa-circle-exclamation"></i></span>
               <span class="text">Quản lý sinh viên</span>
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
            <a href="../teacher_logout.php">
               <span class="icon"><i class="fa-solid fa-right-from-bracket"></i></span>
               <span class="text">Đăng xuất</span>
            </a>
         </li>
      </ul>
   </div>
   <article class="content">
      <form method="POST">
         <?php
         if (isset($data) && mysqli_num_rows($data) > 0) {
            while ($r = mysqli_fetch_array($data)) {
               ?>
               <div class="form-group" style="width: 75%; margin-left: 150px; margin-top: 50px; margin-bottom: 10px;">
                  <div class="input-group" style="margin-bottom: 10px;">
                     <i class="fa-solid fa-arrow-right"></i>
                     <div class="form-field">
                        <label>Mã học phần</label>
                        <input class="info1" type="text" name="txtmamon" value="<?php echo $r['mamon']; ?>"
                           placeholder="Mã học phần" disabled>
                        <input type="hidden" name="txtmamon" value="<?php echo $r['mamon']; ?>" placeholder="Mã học phần">
                     </div>
                  </div>
                  <div class="input-group" style="margin-bottom: 10px;">
                     <i class="fa-solid fa-arrow-right"></i>
                     <div class="form-field">
                        <label>Tên sinh viên</label>
                        <input class="info1" type="text" name="txthoten" value="<?php echo $r['hoten'] ?>"
                           placeholder="Tên sinh viên" disabled>
                        <input type="hidden" name="txthoten" value="<?php echo $r['hoten'] ?>" placeholder="Tên sinh viên">
                     </div>
                  </div>
                  <div class="input-group" style="margin-bottom: 10px;">
                     <i class="fa-solid fa-arrow-right"></i>
                     <div class="form-field">
                        <label>Mã sinh viên</label>
                        <input class="info1" type="text" name="txtma" value="<?php echo $r['ma'] ?>"
                           placeholder="Mã sinh viên" disabled>
                        <input type="hidden" name="txtma" value="<?php echo $r['ma'] ?>" placeholder="Mã sinh viên">
                     </div>
                  </div>
                  <div class="input-group" style="margin-bottom: 10px;">
                     <i class="fa-solid fa-arrow-right"></i>
                     <div class="form-field">
                        <label>Tên học phần</label>
                        <input class="info1" type="text" name="txttenmon" value="<?php echo $r['tenmon'] ?>"
                           placeholder="Tên học phần" disabled>
                        <input type="hidden" name="txttenmon" value="<?php echo $r['tenmon'] ?>" placeholder="Tên học phần">
                     </div>
                  </div>
                  <div class="input-group" style="margin-bottom: 10px;">
                     <i class="fa-solid fa-arrow-right"></i>
                     <div class="form-field">
                        <label>Số tín chỉ</label>
                        <input class="info1" type="text" name="txtstc" value="<?php echo $r['sotinchi'] ?>"
                           placeholder="Số tín chỉ" disabled>
                        <input type="hidden" name="txtstc" value="<?php echo $r['sotinchi'] ?>" placeholder="Số tín chỉ">
                     </div>
                  </div>
                  <div class="input-group" style="margin-bottom: 10px;">
                     <i class="fa-solid fa-arrow-right"></i>
                     <div class="form-field">
                        <label>Điểm chuyên cần</label>
                        <input class="info1" type="text" name="txtdiemcc" value="<?php echo $r['diemcc'] ?>"
                           placeholder="Điểm chuyên cần">
                     </div>
                  </div>
                  <div class="input-group" style="margin-bottom: 10px;">
                     <i class="fa-solid fa-arrow-right"></i>
                     <div class="form-field">
                        <label>Điểm giữa kỳ</label>
                        <input class="info1" type="text" name="txtdiemgk" value="<?php echo $r['diemgk'] ?>"
                           placeholder="Điểm giữa kỳ">
                     </div>
                  </div>
                  <div class="input-group" style="margin-bottom: 10px;">
                     <i class="fa-solid fa-arrow-right"></i>
                     <div class="form-field">
                        <label>Điểm cuối kỳ</label>
                        <input class="info1" type="text" name="txtdiemck" value="<?php echo $r['diemck'] ?>"
                           placeholder="Điểm cuối kỳ">
                     </div>
                  </div>
                  <button type="submit" class="btn btn-success" name="btnLuu"
                     style="margin-left: 38%; margin-top: 10px; width: 200px">Lưu</button>
                  <button type="submit" class="btn btn-info" name="btnBack" style="margin-left:150px; margin-top:10px">Trở
                     về</button>
               </div>
               <?php
            }
         }
         ?>
      </form>
   </article>
</body>

</html>