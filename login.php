<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Quản lý điểm sinh viên đại học</title>
</head>

<body>
    <div class="container" id="Dangnhap">
        <h1 class="form-title">Đăng nhập</h1>
        <form method="post">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="Masv" id="Masv" placeholder="Mã sinh viên">
                <label for="Masv">Mã sinh viên</label>
            </div>
            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="matkhau" id="password" placeholder="Mật khẩu">
                <i id="togglePassword" class="fa-solid fa-eye" onclick="togglePasswordVisibility()"></i>
                <label for="matkhau">Mật khẩu</label>
            </div>
            <button type="submit" class="button" id="btnDangnhap" style="text-decoration: none;">Đăng nhập</button>
            <p class="recover">
                <a href="Khoiphuc.php">Quên mật khẩu?</a>
            </p>
        </form>
    </div>
    <script src="script.js"></script>
</body>

</html>