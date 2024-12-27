<?php
class UserModel {
    private $conn;

    public function __construct() {
        $this->conn = mysqli_connect('localhost', 'root', '', '74dcht21-qldiemsv');
        if (!$this->conn) {
            die("Kết nối thất bại: " . mysqli_connect_error());
        }
    }

    public function getUser($Masv, $password) {
        $sql = "SELECT * FROM sinhvien WHERE Masv = '$Masv' AND password = '$password'";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }

    public function __destruct() {
        mysqli_close($this->conn);
    }
}
?>