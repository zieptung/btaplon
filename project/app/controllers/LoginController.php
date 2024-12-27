<?php
class LoginController {
    public function showLoginForm() {
        include '../views/auth/login.php';
    }

    public function login() {
        session_start();
        $conn = mysqli_connect('localhost', 'root', '', '74dcht21-qldiemsv');

        if (!$conn) {
            die("Kết nối thất bại: " . mysqli_connect_error());
        }

        if (isset($_POST["btnDangnhap"])) {
            $Masv = $_POST['Masv'];
            $password = $_POST['password'];

            $sql = "SELECT * FROM sinhvien WHERE Masv = '$Masv' AND password = '$password'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                header("Location: ../views/home/homepage.php");
                exit();
            } else {
                $_SESSION['error'] = "Mã sinh viên hoặc mật khẩu sai!";
                header("Location: ../views/auth/login.php");
                exit();
            }
        }

        mysqli_close($conn);
    }
}
?>