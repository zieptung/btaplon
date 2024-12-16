<?php
session_start();
ob_start(); // Bắt đầu bộ đệm đầu ra

// Kết nối cơ sở dữ liệu bằng mysqli_connect()
$conn = mysqli_connect('localhost', 'root', '', '74dcht21-qldiemsv');

// Kiểm tra kết nối
if (!$conn) {
   die("Kết nối thất bại: " . mysqli_connect_error());
}
// Xử lý khi người dùng nhấn "Đăng nhập"
if (isset($_POST["btnDangnhap"])) {
   $Masv = $_POST['Masv'];
   $password = $_POST['password'];

   // Bảo vệ SQL Injection
   $Masv = mysqli_real_escape_string($conn, $Masv);
   $password = mysqli_real_escape_string($conn, $password);

   // Kiểm tra tài khoản với password không mã hóa
   $sql = "SELECT * FROM sinhvien WHERE Masv = '$Masv' AND password = '$password'";
   $result = mysqli_query($conn, $sql);

   if (mysqli_num_rows($result) > 0) {
      // Đăng nhập thành công
      header("Location: homepage.php");
      exit;
   } else {
      // Đăng nhập thất bại
      $_SESSION['error'] = "Tài khoản hoặc mật khẩu sai!";
      header("Location: login.php");
      exit;
   }
}

// Đóng kết nối khi không sử dụng nữa
mysqli_close($conn);
ob_end_flush(); // Xả bộ đệm đầu ra
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
   <link rel="stylesheet" href="login.css">
   <title>Quản lý điểm sinh viên đại học</title>
</head>

<body>
   <div class="container" id="Dangnhap">
      <h1 class="form-title">Đăng nhập</h1>
      <form method="POST">
         <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="Masv" id="Masv" placeholder="Mã sinh viên">
         </div>
         <div class="input-group">
            <i class="fa-solid fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Mật khẩu">
            <i id="togglePassword" class="fa-solid fa-eye" onclick="togglePasswordVisibility()"></i>
            <?php if (isset($_SESSION['error'])): ?>
               <span class="error-message" id="error-message"><?php echo $_SESSION['error'];
               unset($_SESSION['error']); ?></span>
            <?php endif; ?>
         </div>
         <button type="submit" class="button" name="btnDangnhap" id="btnDangnhap" style="text-decoration: none;">Đăng
            nhập</button>
         <p class="recover">
            <a href="recover.php">Quên mật khẩu?</a>
         </p>
      </form>
   </div>
   <script src="script.js"></script>
</body>

</html>