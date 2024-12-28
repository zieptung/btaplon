<?php
if (isset($_POST['btnGui']) == true) {
    $email = $_POST['email'];
    $conn = new PDO("mysql:host=localhost;dbname=74dcht21-qldiemsv", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);
    $count = $stmt->rowCount();
    if ($count == 0) {
        $loi = "Email không tồn tại";
    } else {
        $password = substr(md5(rand(100000, 999999)), 0, 8);
        $sql = "update user set password=? where email=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$password, $email]);
        echo "<script>
            alert('Mật khẩu mới của bạn là: $password');
            window.location.href = 'login.php';
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="login-re.css">
    <title>Quản lý điểm sinh viên đại học</title>
</head>

<body>
    <div class="container">
        <img src="istockphoto-1306827906-170667a.jpg" alt="">
        <h1 class="form-title">Khôi phục mật khẩu</h1>
        <form method="post">
            <div class="input-group">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" name="email" id="email" value="<?php if (isset($email) == true)
                    echo $email ?>" placeholder="Nhập email của bạn">
                </div>
                <?php
                if (isset($loi) && $loi != "") { ?>
                <div class="error-message" id="error-message"><?php echo $loi;
                unset($loi); ?></div>
            <?php } ?>
            <script>
                document.getElementById('email').addEventListener('focus', function () {
                    var errorMessage = document.getElementById('error-message');
                    if (errorMessage) {
                        errorMessage.style.display = 'none';
                    }
                });
            </script>
            <button type="submit" class="button" name="btnGui" id="btnGui" style="text-decoration: none;">Gửi</button>
        </form>
    </div>
</body>

</html>