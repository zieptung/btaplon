<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
include_once "../connectdb.php";
require_once '../Classes/PHPExcel.php';
if (isset($_POST['btnXuat'])) {
   ob_start();

   //code xuất excel
   $objExcel = new PHPExcel();
   $objExcel->setActiveSheetIndex(0);
   $sheet = $objExcel->getActiveSheet()->setTitle('Diem');
   $rowCount = 1;
   //Tạo tiêu đề cho cột trong excel

   $sheet->setCellValue("A$rowCount", 'Mã Học Phần');
   $sheet->setCellValue("B$rowCount", 'Tên Sinh Viên');
   $sheet->setCellValue("C$rowCount", 'Mã Sinh Viên');
   $sheet->setCellValue("D$rowCount", 'Tên Học Phần');
   $sheet->setCellValue("E$rowCount", 'Số Tín Chỉ');
   $sheet->setCellValue("F$rowCount", 'Điểm Số');
   $sheet->setCellValue("G$rowCount", 'Điểm Chữ');
   $sheet->setCellValue("H$rowCount", 'Điểm Chuyên Cần');
   $sheet->setCellValue("I$rowCount", 'Điểm Giữa Kỳ');
   $sheet->setCellValue("J$rowCount", 'Điểm Cuối Kỳ');
   $sheet->setCellValue("K$rowCount", 'Điểm Tổng');
   $sheet->setCellValue("L$rowCount", 'Loại');

   $sql1 = "SELECT * FROM diem";
   $data = mysqli_query($con, $sql1);
   while ($row = mysqli_fetch_array($data)) {
      $rowCount++;
      $sheet->setCellValue("A{$rowCount}", $row['mamon']);
      $sheet->setCellValue("B{$rowCount}", $row['hoten']);
      $sheet->setCellValue("C{$rowCount}", $row['ma']);
      $sheet->setCellValue("D{$rowCount}", $row['tenmon']);
      $sheet->setCellValue("E{$rowCount}", $row['sotinchi']);
      $sheet->setCellValue("F{$rowCount}", $row['diemso']);
      $sheet->setCellValue("G{$rowCount}", $row['diemchu']);
      $sheet->setCellValue("H{$rowCount}", $row['diemcc']);
      $sheet->setCellValue("I{$rowCount}", $row['diemgk']);
      $sheet->setCellValue("J{$rowCount}", $row['diemck']);
      $sheet->setCellValue("K{$rowCount}", $row['diemtong']);
      $sheet->setCellValue("L{$rowCount}", $row['loai']);
   }

   //định dạng cột tiêu đề
   $sheet->getColumnDimension('A')->setAutoSize(true);
   $sheet->getColumnDimension('B')->setAutoSize(true);
   $sheet->getColumnDimension('C')->setAutoSize(true);
   $sheet->getColumnDimension('D')->setAutoSize(true);
   $sheet->getColumnDimension('E')->setAutoSize(true);
   $sheet->getColumnDimension('F')->setAutoSize(true);
   $sheet->getColumnDimension('G')->setAutoSize(true);
   $sheet->getColumnDimension('H')->setAutoSize(true);
   $sheet->getColumnDimension('I')->setAutoSize(true);
   $sheet->getColumnDimension('J')->setAutoSize(true);
   $sheet->getColumnDimension('K')->setAutoSize(true);
   $sheet->getColumnDimension('L')->setAutoSize(true);
   //gán màu nền
   $sheet->getStyle('A1:L1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('00FF00');
   //căn giữa
   $sheet->getStyle('A1:L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

   // Thực hiện truy vấn để lấy dữ liệu từ cơ sở dữ liệu
   //Kẻ bảng 
   $styleAray = [
      'borders' => [
         'allborders' => [
            'style' => PHPExcel_Style_Border::BORDER_THIN
         ]
      ]
   ];
   $sheet->getStyle("A1:L$rowCount")->applyFromArray($styleAray);
   $objWriter = new PHPExcel_Writer_Excel2007($objExcel);
   ob_end_flush(); // Xóa bộ đệm đầu ra
   $fileName = 'Diem.xlsx';
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

if (isset($_POST['btnXoa'])) {
   $sql = "DELETE FROM diem";
   $data = mysqli_query($con, $sql);
   if ($data) {
      echo "<script>alert('Xoá thành công')</script>";
   }
}

$ht = "";
$tm = "";
$ma = "";

$sql = "SELECT * FROM diem";

if (isset($_POST['btnTimkiem'])) {
   $ma = $_POST['txtma'];
   $ht = $_POST['txthoten'];
   $tm = $_POST['txttenmon'];
   $sql = "SELECT * FROM diem WHERE hoten LIKE '%$ht%' AND tenmon LIKE '%$tm%' AND ma LIKE '%$ma%'";
}
$data = mysqli_query($con, $sql);


$sortOrder = "DESC"; //Biến sắp xếp mặc định
if (isset($_POST['sortOrder'])) {
   $sortOrder = $_POST['sortOrder'];
}
if (isset($_POST['btnSapxep'])) {
   $ma = $_POST['txtma'];
   $ht = $_POST['txthoten'];
   $tm = $_POST['txttenmon'];
   $sql = "SELECT * FROM diem WHERE hoten LIKE '%$ht%' AND tenmon LIKE '%$tm%' AND ma LIKE '%$ma%' ORDER BY diemtong $sortOrder";
   $data = mysqli_query($con, $sql);
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
      <span class="header-text">Bảng điểm sinh viên</span>
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
            <a href="teacher_message.php">
               <span class="icon"><i class="fa-solid fa-envelope"></i></span>
               <span class="text">Tin nhắn</span>
            </a>
         </li>
         <li>
            <a href="teacher_forum.php">
               <span class="icon"><i class="fa-solid fa-bell"></i></span>
               <span class="text">Diễn đàn</span>
            </a>
         </li>
         <li>
            <a href="teacher_infosv.php">
               <span class="icon"><i class="fa-solid fa-circle-exclamation"></i></span>
               <span class="text">Quản lý sinh viên</span>
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
            <a href="teacher_logout.php">
               <span class="icon"><i class="fa-solid fa-right-from-bracket"></i></span>
               <span class="text">Đăng xuất</span>
            </a>
         </li>
      </ul>
   </div>
   <!-- content -->
   <article class="content">
      <form method="post" action="">
         <form method="POST" action="" enctype="multipart/form-data">
            <div class="row">
               <div class="col" style="margin:10px">
                  <div class="input-group full-width">
                     <i class="fa-solid fa-user"></i>
                     <div class="form-field">
                        <input class="info1" type="text" name="txthoten" value="<?php echo $ht; ?>"
                           placeholder="Tên sinh viên">
                     </div>
                  </div>
               </div>
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
                     <i class="fa-solid fa-book"></i>
                     <div class="form-field">
                        <input class="info1" type="text" name="txttenmon" value="<?php echo $tm; ?>"
                           placeholder="Tên học phần">
                     </div>
                  </div>
               </div>
               <div class="col" style="margin:10px">
                  <div class="input-group full-width">
                     <i class="fa-solid fa-filter"></i>
                     <div class="form-field">
                        <label for="sortOrder">Sắp xếp</label>
                        <select class="info1" name="sortOrder">
                           <option value="DESC" <?php if ($sortOrder == "DESC")
                              echo "selected"; ?>>Giảm dần</option>
                           <option value="ASC" <?php if ($sortOrder == "ASC")
                              echo "selected"; ?>>Tăng dần</option>
                        </select>
                     </div>
                  </div>
               </div>
            </div>
            <button type="submit" class="btn btn-info" name="btnTimkiem"
               style="margin-left:370px; margin-top:10px; margin-bottom: 10px; margin-right: 60px"><i
                  class="fa-solid fa-magnifying-glass"></i> Tìm
               kiếm</button>
            <button class="btn btn-info" type="submit" name="btnXuat">Xuất file</button>
            <button class="btn btn-info" type="submit" name="btnSapxep" style="margin-left:60px;"><i
                  class="fa-solid fa-arrow-up-wide-short"></i> Sắp xếp</button>
            <button class="btn btn-danger" type="submit" name="btnXoa" style="margin-left:220px;"
               onclick="return confirm('Bạn có chắc chắn muốn xóa không ?')">Xoá tất cả <i
                  class="fa-solid fa-xmark"></i></button>
            <table class="table table-bordered" style="background-color: #3F72AF; color: #F9F7F7;">
               <thead style="background-color: #1B262C; color: #FADA7A; text-align: center;">
                  <tr>
                     <th>STT</th>
                     <th>Mã học phần</th>
                     <th>Tên sinh viên</th>
                     <th>Mã sinh viên</th>
                     <th>Tên học phần</th>
                     <th>Số tín chỉ</th>
                     <th>Điểm số</th>
                     <th>Điểm chữ</th>
                     <th>Điểm chuyên cần</th>
                     <th>Điểm giữa kỳ</th>
                     <th>Điểm cuối kỳ</th>
                     <th>Điểm tổng</th>
                     <th>Loại</th>
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
                           <td><?php echo $row['mamon'] ?></td>
                           <td><?php echo $row['hoten'] ?></td>
                           <td><?php echo $row['ma'] ?></td>
                           <td><?php echo $row['tenmon'] ?></td>
                           <td><?php echo $row['sotinchi'] ?></td>
                           <td><?php echo $row['diemso'] ?></td>
                           <td><?php echo $row['diemchu'] ?></td>
                           <td><?php echo $row['diemcc'] ?></td>
                           <td><?php echo $row['diemgk'] ?></td>
                           <td><?php echo $row['diemck'] ?></td>
                           <td><?php echo $row['diemtong'] ?></td>
                           <td><?php echo $row['loai'] ?></td>
                           <td>
                              <a href="./manager_diemsv/teacher_fix_diemsv.php?ma=<?php echo $row['ma'] ?>&mamon=<?php echo $row['mamon'] ?>"
                                 class="btn btn-light"><i class="fa-solid fa-wrench"></i></a>
                              <a href="./manager_diemsv/teacher_del_diemsv.php?ma=<?php echo $row['ma'] ?>"
                                 onclick="return confirm('Bạn có chắc chắn muốn xóa không ?')" class="btn btn-danger"><i
                                    class="fa-solid fa-trash"></i></a>
                           </td>
                        </tr>
                        <?php
                     }
                  }
                  ?>
   </article>
</body>

</html>