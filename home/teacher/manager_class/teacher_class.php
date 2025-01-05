<?php
include_once "../connectdb.php";
require '../Classes/PHPExcel.php';

session_start();

$kh = "";
$ma = "";
$ht = "";
$lop = "";
$sql2 = "SELECT * FROM lop_hoc";
$lophoc = mysqli_query($con, $sql2);

$sql1 = "SELECT DISTINCT khoahoc FROM khoa_hoc";
$khoahoc = mysqli_query($con, $sql1);

$sql = "SELECT user.*, lop_hoc.tenlop FROM user LEFT JOIN lop_hoc ON user.tenlop = lop_hoc.tenlop WHERE user.is_admin = 0";
$data = mysqli_query($con, $sql);

// Kiểm tra nếu có câu truy vấn tìm kiếm trong session
if (isset($_SESSION['search_sql'])) {
   $search_sql = $_SESSION['search_sql'];
   $data = mysqli_query($con, $search_sql);
} else {
   $search_sql = $sql;
}

if (isset($_POST['btnTimkiem'])) {
   $ma = $_POST['txtma'];
   $ht = $_POST['txthoten'];
   $lop = $_POST['lophoc'];
   $kh = $_POST['khoahoc'];
   $search_sql = "SELECT user.*, lop_hoc.tenlop FROM user LEFT JOIN lop_hoc ON user.tenlop = lop_hoc.tenlop WHERE user.ma LIKE '%$ma%' AND user.hoten LIKE '%$ht%' AND user.tenlop LIKE '%$lop%' AND user.khoahoc LIKE '%$kh%' AND user.is_admin = 0";
   $data = mysqli_query($con, $search_sql);

   // Lưu câu truy vấn tìm kiếm vào session
   $_SESSION['search_sql'] = $search_sql;
}

if (isset($_POST['btnXuat'])) {
   $result = mysqli_query($con, $search_sql);

   $objExcel = new PHPExcel();
   $objExcel->setActiveSheetIndex(0);
   $sheet = $objExcel->getActiveSheet()->setTitle('Danh sách sinh viên');
   $rowCount = 1;

   // Tạo tiêu đề cho cột trong excel
   $sheet->setCellValue("A1", 'Mã sinh viên');
   $sheet->setCellValue("B1", 'Tên sinh viên');
   $sheet->setCellValue("C1", 'Tên lớp');
   $sheet->setCellValue("D1", 'Khóa học');

   // Sử dụng câu truy vấn đã tìm kiếm
   while ($row = mysqli_fetch_assoc($result)) {
      $rowCount++;
      $sheet->setCellValue("A{$rowCount}", $row['ma']);
      $sheet->setCellValue("B{$rowCount}", $row['hoten']);
      $sheet->setCellValue("C{$rowCount}", $row['tenlop']);
      $sheet->setCellValue("D{$rowCount}", $row['khoahoc']);
   }

   // Định dạng cột tiêu đề
   $sheet->getColumnDimension('A')->setAutoSize(true);
   $sheet->getColumnDimension('B')->setAutoSize(true);
   $sheet->getColumnDimension('C')->setAutoSize(true);
   $sheet->getColumnDimension('D')->setAutoSize(true);

   // Gán màu nền
   $sheet->getStyle('A1:D1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('00FF00');

   // Căn giữa
   $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

   // Kẻ bảng 
   $styleArray = [
      'borders' => [
         'allborders' => [
            'style' => PHPExcel_Style_Border::BORDER_THIN
         ]
      ]
   ];
   $sheet->getStyle("A1:D{$rowCount}")->applyFromArray($styleArray);
   $objWriter = new PHPExcel_Writer_Excel2007($objExcel);
   $fileName = 'DSlop.xlsx';
   $objWriter->save($fileName);
   ob_end_clean(); // Xóa bộ đệm đầu ra
   header("Content-Disposition: attachment; filename=\"{$fileName}\"");
   header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
   header('Content-Length: ' . filesize($fileName));
   header('Content-Transfer-Encoding: binary');
   header('Cache-Control: must-revalidate');
   header('Pragma: no-cache');
   readfile($fileName);
   unlink($fileName); // Xóa tệp sau khi tải xuống
   exit;
}

if (isset($_POST['btnThemmoi'])) {
   header("location:teacher_add_class.php");
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
      <span class="header-text">Quản lý lớp học</span>
      <span class="header-icon"><i class="fa-solid fa-circle-user"></i></span>
      <?php
      include_once "../connectdb.php";
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
               <span class="text">Thông tin cá nhân</span>
            </a>
         </li>
         <li>
            <a href="teacher_class.php">
               <span class="icon"><i class="fa-solid fa-landmark"></i></span>
               <span class="text">Quản lý lớp học</span>
            </a>
         </li>
         <li>
            <a href="../manager_course/teacher_course.php">
               <span class="icon"><i class="fa-solid fa-pen-to-square"></i></span>
               <span class="text">Quản lý môn học</span>
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
            <a href="../manager_scholarship/teacher_scholarship.php">
               <span class="icon"><i class="fa-solid fa-user-graduate"></i></span>
               <span class="text">Danh sách học bổng</span>
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
   <!-- content -->
   <article class="content">
      <form method="POST" action="" enctype="multipart/form-data">
         <div class="row">
            <div class="col" style="margin:10px">
               <div class="input-group full-width">
                  <i class="fa-solid fa-user"></i>
                  <div class="form-field">
                     <input class="info1" type="text" name="txtma" value="<?php echo $ma; ?>"
                        placeholder="Mã sinh viên">
                  </div>
               </div>
            </div>
            <div class="col" style="margin:10px">
               <div class="input-group full-width">
                  <i class="fa-solid fa-signature"></i>
                  <div class="form-field">
                     <input class="info1" type="text" name="txthoten" value="<?php echo $ht; ?>" placeholder="Họ tên">
                  </div>
               </div>
            </div>
            <div class="col" style="margin:10px">
               <div class="input-group full-width">
                  <i class="fa-solid fa-clipboard"></i>
                  <div class="form-field">
                     <select class="info1" name="lophoc">
                        <option value="">Chọn lớp học</option>
                        <?php
                        if (isset($lophoc) && mysqli_num_rows($lophoc) > 0) {
                           while ($row = mysqli_fetch_assoc($lophoc)) {
                              ?>
                              <option value="<?php echo $row['tenlop'] ?>" <?php echo ($lop == $row['tenlop']) ? 'selected' : ''; ?>>
                                 <?php echo $row['tenlop'] ?>
                              </option>
                              <?php
                           }
                        }
                        ?>
                     </select>
                  </div>
               </div>
            </div>
            <div class="col" style="margin:10px">
               <div class="input-group full-width">
                  <i class="fa-solid fa-book"></i>
                  <div class="form-field">
                     <select class="info1" name="khoahoc">
                        <option value="">Chọn khóa học</option>
                        <?php
                        if (isset($khoahoc) && mysqli_num_rows($khoahoc) > 0) {
                           while ($row = mysqli_fetch_assoc($khoahoc)) {
                              ?>
                              <option value="<?php echo $row['khoahoc'] ?>" <?php echo ($kh == $row['khoahoc']) ? 'selected' : ''; ?>>
                                 <?php echo $row['khoahoc'] ?>
                              </option>
                              <?php
                           }
                        }
                        ?>
                     </select>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col">
               <button type="submit" class="btn btn-info" name="btnThemmoi"
                  style="margin-top:20px; margin-bottom: 10px; margin-left:45px"><i class="fa-solid fa-plus"></i>
                  Thêm mới</button>
            </div>
            <div class="col">
               <button type="submit" class="btn btn-info" name="btnTimkiem"
                  style="margin-left:27px; margin-top:20px; margin-bottom: 10px;"><i
                     class="fa-solid fa-magnifying-glass"></i> Tìm
                  kiếm</button>
            </div>
            <div class="col">
               <button type="submit" class="btn btn-info" name="btnXuat"
                  style="margin-left:27px; margin-top:20px; margin-bottom: 10px;"><i
                     class="fa-solid fa-file-export"></i> Xuất
                  file</button>
            </div>
      </form>
      <table class="table table-bordered" style="background-color: #3F72AF; color: #F9F7F7; margin-left: 15px;">
         <thead style="background-color: #1B262C; color: #FADA7A; text-align: center;">
            <tr>
               <th>STT</th>
               <th>Mã sinh viên</th>
               <th>Tên sinh viên</th>
               <th>Lớp</th>
               <th>Khoá học</th>
               <th>Chức năng</th>
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
                     <td><?php echo $row['ma'] ?></td>
                     <td><?php echo $row['hoten'] ?></td>
                     <td><?php echo $row['tenlop'] ?></td>
                     <td><?php echo $row['khoahoc'] ?></td>
                     <td>
                        <a href="teacher_del_class.php?ma=<?php echo $row['ma'] ?>"
                           onclick="return confirm('Bạn có chắc chắn muốn xóa không ?')" class="btn btn-danger"><i
                              class="fa-solid fa-trash"></i></a>
                     </td>
                  </tr>
                  <?php
               }
            }
            ?>
         </tbody>
      </table>

   </article>
   </article>
</body>

</html>