<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" />
   <link rel="stylesheet" href="login.css" />
   <title>Quản lý điểm sinh viên đại học</title>
</head>

<body>
   <?php
    require 'project/app/controllers/LoginController.php';

    $action = $_GET['action'] ?? 'showLoginForm';
    $controller = new LoginController();

    if (method_exists($controller, $action)) {
        $controller->$action(); } else { echo "Action not found!"; } ?>
   <div class="container" id="Dangnhap">
      <h1 class="form-title">Đăng nhập</h1>
      <form method="POST">
         <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="Masv" id="Masv" placeholder="Mã sinh viên" />
         </div>
         <div class="input-group">
            <i class="fa-solid fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Mật khẩu" />
            <i id="togglePassword" class="fa-solid fa-eye" onclick="togglePasswordVisibility()"></i>
         </div>
         <button type="submit" class="button" name="btnDangnhap" id="btnDangnhap" style="text-decoration: none">
            Đăng nhập
         </button>
         <p class="recover">
            <a href="recover.php">Quên mật khẩu?</a>
         </p>
      </form>
   </div>
   <script src="script.js"></script>
</body>

</html>