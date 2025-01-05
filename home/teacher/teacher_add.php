<?php
include_once "connectdb.php";
if (isset($_POST['btnGui'])) {
    require "./Classes/PHPExcel.php";
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['file']['tmp_name'];
        $objReader = PHPExcel_IOFactory::createReaderForFile($file);
        $objReader->setLoadSheetsOnly('Sheet1');
        $objExcel = $objReader->load($file);
        $sheetData = $objExcel->getActiveSheet()->toArray(null, true, true, true);
        $highestRow = $objExcel->setActiveSheetIndex()->getHighestRow();
        // Chuẩn bị câu lệnh chèn
        for ($row = 2; $row <= $highestRow; $row++) {
            $mamon = $sheetData[$row]['A'];
            $hoten = $sheetData[$row]['B'];
            $ma = $sheetData[$row]['C'];
            $tenmon = $sheetData[$row]['D'];
            $sotinchi = $sheetData[$row]['E'];
            $diemcc = $sheetData[$row]['F'];
            $diemgk = $sheetData[$row]['G'];
            $diemck = $sheetData[$row]['H'];

            // Kiểm tra sinh viên có tồn tại trong bảng sinh_vien không
            $sql5 = "SELECT ma FROM sinh_vien WHERE ma = '$ma'";
            $result = mysqli_query($con, $sql5);

            if (mysqli_num_rows($result) > 0) {
                // Sinh viên tồn tại, chèn dữ liệu vào bảng diem
                $sql6 = "INSERT INTO diem(mamon, hoten, ma, tenmon, sotinchi, diemcc, diemgk, diemck) 
                      VALUES ('$mamon', '$hoten', '$ma', '$tenmon', '$sotinchi', '$diemcc', '$diemgk', '$diemck')";
                mysqli_query($con, $sql6);
            }
        }
    }
    echo "<script>alert('Thêm thành công'); window.location.href = 'teacher_board.php';</script>";
}

$ma = '';
$mm = '';
$ht = '';
$tm = '';
$stc = '';
$cc = '';
$gk = '';
$ck = '';

$sql = "SELECT * from diem";
$monhoc = mysqli_query($con, $sql);

$sql1 = "SELECT * from mon_hoc";
$mon_hoc = mysqli_query($con, $sql1);

if (isset($_POST["btnLuu"])) {
    $mm = $_POST['txtmamon'];
    $ht = $_POST['txthoten'];
    $ma = $_POST['txtma'];
    $tm = $_POST['txttenmon'];
    $stc = $_POST['txtstc'];
    $cc = $_POST['txtdiemcc'];
    $gk = $_POST['txtdiemgk'];
    $ck = $_POST['txtdiemck'];

    // Kiểm tra mục nhập trùng lặp
    $checkSql = "SELECT * FROM diem WHERE mamon = '$mm' AND ma = '$ma'";
    $result = mysqli_query($con, $checkSql);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Điểm của sinh viên này đã tồn tại!'); window.location.href='teacher_add.php';</script>";
    } else {
        $sql = "INSERT INTO diem (mamon, hoten, ma, tenmon, sotinchi, diemcc, diemgk, diemck)
        VALUES ('$mm', '$ht', '$ma', '$tm', '$stc', '$cc', '$gk', '$ck')";

        $kq = mysqli_query($con, $sql);
        if ($kq) {
            echo "<script>alert('Thêm mới thành công!'); window.location.href='teacher_board.php';</script>";
        }
    }
}

if (isset($_POST["btnNhap"])) {
    $ma = $_POST['txtma'];
    $sql = "SELECT hoten FROM sinh_vien WHERE ma = '$ma'";
    $result = mysqli_query($con, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $ht = $row['hoten'];
    } else {
        echo "<script>alert('Mã sinh viên không tồn tại!'); window.location.href='teacher_add.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="teacher_info.css">
<link rel="stylesheet" href="teacher_homepage.css">
<title>Quản lý điểm sinh viên đại học</title>

<body>
   <!-- header -->
   <div class="header">
      <span class="header-text">Thêm điểm sinh viên</span>
      <span class="header-icon"><i class="fa-solid fa-circle-user"></i></span>
      <?php
        session_start();
        include_once "connectdb.php";
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
            <a href="teacher_info.php">
               <span class="icon"><i class="fa-solid fa-user"></i></span>
               <span class="text">Thông tin cá nhân</span>
            </a>
         </li>
         <li>
            <a href="teacher_infosv.php">
               <span class="icon"><i class="fa-solid fa-circle-exclamation"></i></span>
               <span class="text">Quản lý sinh viên</span>
            </a>
         </li>
         <li>
            <a href="./manager_class/teacher_class.php">
               <span class="icon"><i class="fa-solid fa-landmark"></i></span>
               <span class="text">Quản lý lớp học</span>
            </a>
         </li>
         <li>
            <a href="./manager_course/teacher_course.php">
               <span class="icon"><i class="fa-solid fa-pen-to-square"></i></span>
               <span class="text">Quản lý môn học</span>
            </a>
         </li>
         <li>
            <a href="teacher_add.php">
               <span class="icon"><i class="fa-solid fa-wrench"></i></span>
               <span class="text">Thêm điểm sinh viên</span>
            </a>
         </li>
         <li>
            <a href="teacher_board.php">
               <span class="icon"><i class="fa-solid fa-table"></i></span>
               <span class="text">Bảng điểm sinh viên</span>
            </a>
         </li>
         <li>
            <a href="teacher_listgv.php">
               <span class="icon"><i class="fa-solid fa-list"></i></span>
               <span class="text">Danh sách quản lý</span>
            </a>
         </li>
         <li>
            <a href="./manager_scholarship/teacher_scholarship.php">
               <span class="icon"><i class="fa-solid fa-user-graduate"></i></span>
               <span class="text">Danh sách học bổng</span>
            </a>
         </li>
         <li>
            <a href="teacher_logout.php">
               <span class="icon"><i class="fa-solid fa-right-from-bracket"></i></span>
               <span class="text">Đăng xuất</span>
            </a>
         </li>
      </ul>
   </div>
   <article class="content">
      <div class="container mt-4">
         <form action="teacher_add.php" method="POST" enctype="multipart/form-data" id="myForm">
            <div class="col">
               <div class="input-group" style="width: 400px; margin-top:10px; margin-bottom: 10px; margin-left: 350px;">
                  <input class="form-control" type="file" id="formFile" name="file">
                  <button class="btn btn-outline-success" type="submit" name="btnGui">Gửi</button>
               </div>
            </div>
            <div class="col" style="margin-top: 10px;">
               <div class="input-group">
                  <i class="fa-solid fa-arrow-right"></i>
                  <div class="form-field">
                     <label>Mã sinh viên</label>
                     <input class="info1" type="text" name="txtma" value="<?php echo $ma; ?>" placeholder="Mã sinh viên"
                        id="studentIdInput" oninput="showNhapButton()">
                  </div>
               </div>
            </div>
            <button type="submit" class="btn btn-success" name="btnNhap" id="btnNhap"
               style="margin-left: 42%; width: 200px; margin-top:10px; margin-bottom: 10px; display: <?php echo empty($ma) ? 'block' : 'none'; ?>">Nhập</button>
            <div class="col" style="margin-top: 10px;">
               <div class="input-group">
                  <i class="fa-solid fa-arrow-right"></i>
                  <div class="form-field">
                     <label>Tên sinh viên</label>
                     <input class="info1" type="text" name="txthoten" value="<?php echo $ht; ?>" placeholder="Họ và tên"
                        id="studentNameInput" readonly>
                  </div>
               </div>
            </div>
            <div class="col" style="margin-top: 10px;">
               <div class="input-group">
                  <i class="fa-solid fa-arrow-right"></i>
                  <div class="form-field">
                     <label>Mã học phần</label>
                     <select name="txtmamon" id="" class="info1">
                        <option value="">---Chọn mã học phần---</option>
                        <?php
                                if (isset($mon_hoc) && mysqli_num_rows($mon_hoc) > 0) {
                                    while ($row = mysqli_fetch_assoc($mon_hoc)) {
                                        ?>
                        <option value="<?php echo $row['mamon'] ?>">
                           <?php echo $row['mamon'] ?>
                        </option>
                        <?php
                                    }
                                }
                                ?>
                     </select>
                  </div>
               </div>
            </div>
            <div class="col" style="margin-top: 10px;">
               <div class="input-group">
                  <i class="fa-solid fa-arrow-right"></i>
                  <div class="form-field">
                     <label>Tên học phần</label>
                     <input class="info1" type="text" name="txttenmon" value="<?php echo $tm; ?>"
                        placeholder="Tên học phần">
                  </div>
               </div>
            </div>
            <div class="col" style="margin-top: 10px;">
               <div class="input-group">
                  <i class="fa-solid fa-arrow-right"></i>
                  <div class="form-field">
                     <label>Số tín chỉ</label>
                     <input class="info1" type="text" name="txtstc" value="<?php echo $stc; ?>"
                        placeholder="Số tín chỉ">
                  </div>
               </div>
            </div>
            <div class="col" style="margin-top: 10px;">
               <div class="input-group">
                  <i class="fa-solid fa-arrow-right"></i>
                  <div class="form-field">
                     <label>Điểm chuyên cần</label>
                     <input class="info1" type="text" name="txtdiemcc" value="<?php echo $cc; ?>"
                        placeholder="Điểm chuyên cần">
                  </div>
               </div>
            </div>
            <div class="col" style="margin-top: 10px;">
               <div class="input-group">
                  <i class="fa-solid fa-arrow-right"></i>
                  <div class="form-field">
                     <label>Điểm giữa kỳ</label>
                     <input class="info1" type="text" name="txtdiemgk" value="<?php echo $gk; ?>"
                        placeholder="Điểm giữa kỳ">
                  </div>
               </div>
            </div>
            <div class="col" style="margin-top: 10px;">
               <div class="input-group">
                  <i class="fa-solid fa-arrow-right"></i>
                  <div class="form-field">
                     <label>Điểm cuối kỳ</label>
                     <input class="info1" type="text" name="txtdiemck" value="<?php echo $ck; ?>"
                        placeholder="Điểm cuối kỳ">
                  </div>
               </div>
            </div>
            <button type="submit" class="btn btn-success" name="btnLuu"
               style="margin-left: 41%; width: 200px; margin-top:10px; margin-bottom: 10px">Lưu</button>
      </div>
      </form>
      </div>
   </article>
   <script>
   function showNhapButton() {
      document.getElementById("btnNhap").style.display = "block";
   }
   document.addEventListener("DOMContentLoaded", function() {
      document.getElementById("btnNhap").style.display = "none";
   });
   document
      .getElementById("studentIdInput")
      .addEventListener("input", function() {
         if (this.value === "") {
            document.getElementById("btnNhap").style.display = "none";
         } else {
            document.getElementById("btnNhap").style.display = "block";
         }
      });
   </script>
</body>

</html>