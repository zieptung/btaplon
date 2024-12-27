<?php
session_start();
include_once "connectdb.php";
// Xử lý khi người dùng nhấn "Đăng nhập"
if (isset($_POST["btnDangnhap"])) {
   $ma = $_POST['ma'];
   $password = $_POST['password'];

   // Kiểm tra tài khoản với password không mã hóa
   $sql = "SELECT * FROM user WHERE ma = '$ma' and password = '$password'";
   $result = mysqli_query($con, $sql);

   if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);

      // Kiểm tra quyền quản lý
      switch ($row['is_admin']) {
         case 1:
            // Giảng viên
            header("Location: ./teacher/teacher_info.php");
            break;
         default:
            // Sinh viên
            header("Location: ./student/student_homepage.php");
            break;
      }
      $_SESSION['user_id'] = $row['ma'];
      $_SESSION['is_admin'] = $row['is_admin'];
      exit();
   } else {
      $_SESSION['error'] = "Mã sinh viên hoặc mật khẩu sai!";
      header("Location: login.php");
      exit();
   }
}
// Đóng kết nối khi không sử dụng nữa
mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
   <link rel="stylesheet" href="login-re.css">
   <title>Quản lý điểm sinh viên đại học</title>
</head>

<body>
   <div class="container" id="Dangnhap">
      <h1 class="form-title">Đăng nhập</h1>
      <form method="POST">
         <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="ma" id="ma" placeholder="Mã sinh viên">
         </div>
         <div class="input-group">
            <i class="fa-solid fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Mật khẩu">
            <i id="togglePassword" class="fa-solid fa-eye" onclick="togglePasswordVisibility()"></i>
            <<<<<<< Updated upstream <label for="matkhau">Mật khẩu</label>
               <?php if(isset($_SESSION['error'])): ?>
               <span class="error-message"
                  id="error-message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
               =======
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