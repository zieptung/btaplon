   <?php
   include_once "../connectdb.php";
   session_start();
   $sql = "
   SELECT 
   d.ma, 
   MAX(d.hoten) AS hoten, 
   MAX(d.tenlop) AS tenlop, 
   MAX(d.khoahoc) AS khoahoc,
   d.hocky,
   SUM(d.diemtong * d.sotinchi) / SUM(d.sotinchi) AS gpa_10,
   ROUND(
      CASE 
      WHEN (SUM(d.diemtong * d.sotinchi) / SUM(d.sotinchi)) >= 8.5 THEN 4.0
      WHEN (SUM(d.diemtong * d.sotinchi) / SUM(d.sotinchi)) >= 7.0 THEN 
      3.0 + ((SUM(d.diemtong * d.sotinchi) / SUM(d.sotinchi) - 7.0) * 0.1)
      WHEN (SUM(d.diemtong * d.sotinchi) / SUM(d.sotinchi)) >= 5.5 THEN 
      2.0 + ((SUM(d.diemtong * d.sotinchi) / SUM(d.sotinchi) - 5.5) * 0.2)
      WHEN (SUM(d.diemtong * d.sotinchi) / SUM(d.sotinchi)) >= 4.0 THEN 
      1.0 + ((SUM(d.diemtong * d.sotinchi) / SUM(d.sotinchi) - 4.0) * 0.2)
      ELSE 0.0
      END, 
      2) AS gpa_4,
      SUM(d.sotinchi) AS stc_hk
      FROM diem d
      GROUP BY d.ma, d.hocky;
      ";
      
      $result = $con->query($sql);
      
      if ($result->num_rows > 0) {
         // Duyệt qua từng sinh viên và học kỳ để cập nhật bảng hoc_bong
         while ($row = $result->fetch_assoc()) {
            $ma = $row["ma"];
            $hoten = $row["hoten"];
         $tenlop = $row["tenlop"];
         $khoahoc = $row["khoahoc"];
         $hocky = $row["hocky"];
         $gpa = $row["gpa_4"];
         $stc_hk = $row["stc_hk"];
         
         // Kiểm tra xem dữ liệu đã tồn tại trong bảng hoc_bong chưa
         $checkSql = "SELECT * FROM hoc_bong WHERE ma = '$ma' AND hocky = '$hocky'";
         $checkResult = $con->query($checkSql);
         
         if ($checkResult->num_rows > 0) {
            // Nếu đã có, cập nhật GPA và STC_HK
            $updateSql = "
            UPDATE hoc_bong 
            SET gpa = $gpa, stc_hk = $stc_hk, hoten = '$hoten', tenlop = '$tenlop', khoahoc = '$khoahoc'
            WHERE ma = '$ma' AND hocky = '$hocky'";
            $con->query($updateSql);
            } else {
               // Nếu chưa có, chèn mới
               $insertSql = "
               INSERT INTO hoc_bong (ma, gpa, hoten, tenlop, khoahoc, stc_hk, hocky) 
               VALUES ('$ma', $gpa, '$hoten', '$tenlop', '$khoahoc', $stc_hk, '$hocky')";
               $con->query($insertSql);
            }
         }
   }
   $sql_khoahoc_hocky = "SELECT DISTINCT khoahoc, hocky FROM hoc_bong ORDER BY khoahoc, hocky";
   $result = $con->query($sql_khoahoc_hocky);
   $sql_hoc_bong = "SELECT * FROM hoc_bong ORDER BY khoahoc, hocky";
   $data = $con->query($sql_hoc_bong);

   // Đếm tổng số học sinh trong bảng hoc_bong
   $sql_count = "SELECT COUNT(*) AS total_students FROM hoc_bong";
   $result_count = $con->query($sql_count);
   $total_students = $result_count->fetch_assoc()['total_students'];

   // Tính 5% số lượng học sinh
   $top_5_percent = ceil($total_students * 0.05);

   // Lấy 5% học sinh có GPA cao nhất
   $sql_top_students = "
   SELECT * 
   FROM hoc_bong 
   WHERE gpa >= 2.5
   ORDER BY gpa DESC 
   LIMIT $top_5_percent";
   $result_top_students = $con->query($sql_top_students);

   $sql = "SELECT DISTINCT khoahoc, hocky FROM hoc_bong ORDER BY khoahoc ASC, hocky ASC";
   $sql_khoahoc_hocky = mysqli_query($con, $sql);
//Xử lý khi người dùng ấn nút "Tìm kiếm"
   if (isset($_POST['btnTimkiem'])) {
      $khoahoc_hocky = $_POST['khoahoc'];
      if (!empty($khoahoc_hocky)) {
          $parts = explode('-Học kỳ:', $khoahoc_hocky);
          if (count($parts) == 2) {
              $khoahoc = trim($parts[0]);
              $hocky = trim($parts[1]);
   $sql_count = "SELECT COUNT(*) as total_students 
               FROM hoc_bong 
               WHERE khoahoc = '$khoahoc' 
               AND hocky = '$hocky'";
   $result_count = $con->query($sql_count);
   $total_students = $result_count->fetch_assoc()['total_students'];
   

   // Tính 5% số lượng học sinh
   $top_5_percent = ceil($total_students * 0.05);

   // Lấy 5% học sinh có GPA cao nhất
   $sql_top_students = "SELECT * 
                        FROM hoc_bong 
                        WHERE khoahoc = '$khoahoc' 
                        AND hocky = '$hocky' 
                        AND gpa >= 2.5 
                        ORDER BY gpa DESC 
                        LIMIT $top_5_percent";
   // Lưu điều kiện tìm kiếm vào session
   $_SESSION['search_condition'] = array(
      'khoahoc' => $khoahoc,
      'hocky' => $hocky
   );
          }
      } else {
          // Nếu không chọn học kỳ, hiển thị tất cả (giữ nguyên code cũ)
          // Đếm tổng số học sinh trong bảng hoc_bong
   $sql_count = "SELECT COUNT(*) AS total_students FROM hoc_bong";
   $result_count = $con->query($sql_count);
   $total_students = $result_count->fetch_assoc()['total_students'];

   // Tính 5% số lượng học sinh
   $top_5_percent = ceil($total_students * 0.05);

   // Lấy 5% học sinh có GPA cao nhất
   $sql_top_students = "
   SELECT * 
   FROM hoc_bong 
   WHERE gpa >= 2.5
   ORDER BY gpa DESC 
   LIMIT $top_5_percent";
   // Xóa điều kiện tìm kiếm trong session
   unset($_SESSION['search_condition']);
            }
      $result_top_students = $con->query($sql_top_students);
  }
  // Sửa phần xử lý xuất Excel
if (isset($_POST['btnXuat'])) {
   require_once '../Classes/PHPExcel.php';
   $objExcel = new PHPExcel();
   $objExcel->setActiveSheetIndex(0);
   $sheet = $objExcel->getActiveSheet()->setTitle('Danh sách học bổng');
   $rowCount = 1;

   // Tạo tiêu đề cho cột trong excel
   $sheet->setCellValue("A1", 'Tên Sinh Viên');
   $sheet->setCellValue("B1", 'Mã Sinh Viên');
   $sheet->setCellValue("C1", 'Lớp');
   $sheet->setCellValue("D1", 'Tổng Số Tín Chỉ');
   $sheet->setCellValue("E1", 'Điểm TB hệ số 4');
   $sheet->setCellValue("F1", 'Loại Học Bổng');
   $sheet->setCellValue("G1", 'Khóa học - Học kỳ');

   // Xây dựng câu query dựa trên điều kiện tìm kiếm
   if (isset($_SESSION['search_condition'])) {
       $khoahoc = $_SESSION['search_condition']['khoahoc'];
       $hocky = $_SESSION['search_condition']['hocky'];
       
       $sql_count = "SELECT COUNT(*) as total_students 
                    FROM hoc_bong 
                    WHERE khoahoc = '$khoahoc' 
                    AND hocky = '$hocky'";
       $result_count = $con->query($sql_count);
       $total_students = $result_count->fetch_assoc()['total_students'];
       $top_5_percent = ceil($total_students * 0.05);

       $sql_export = "SELECT * 
                     FROM hoc_bong 
                     WHERE khoahoc = '$khoahoc' 
                     AND hocky = '$hocky' 
                     AND gpa >= 2.5 
                     ORDER BY gpa DESC 
                     LIMIT $top_5_percent";
   } else {
       $sql_count = "SELECT COUNT(*) AS total_students FROM hoc_bong";
       $result_count = $con->query($sql_count);
       $total_students = $result_count->fetch_assoc()['total_students'];
       $top_5_percent = ceil($total_students * 0.05);

       $sql_export = "SELECT * 
                     FROM hoc_bong 
                     WHERE gpa >= 2.5
                     ORDER BY gpa DESC 
                     LIMIT $top_5_percent";
   }

   $data = mysqli_query($con, $sql_export);
   while ($row = mysqli_fetch_array($data)) {
       $loai_hoc_bong = '';
       if ($row['gpa'] >= 3.6) {
           $loai_hoc_bong = 'Xuất sắc';
       } elseif ($row['gpa'] >= 3.2) {
           $loai_hoc_bong = 'Giỏi';
       } elseif ($row['gpa'] >= 2.5) {
           $loai_hoc_bong = 'Khá';
       } else {
           $loai_hoc_bong = 'Không đạt học bổng';
       }

       $rowCount++;
       $sheet->setCellValue("A{$rowCount}", $row['hoten']);
       $sheet->setCellValue("B{$rowCount}", $row['ma']);
       $sheet->setCellValue("C{$rowCount}", $row['tenlop']);
       $sheet->setCellValue("D{$rowCount}", $row['stc_hk']);
       $sheet->setCellValue("E{$rowCount}", $row['gpa']);
       $sheet->setCellValue("F{$rowCount}", $loai_hoc_bong);
       $sheet->setCellValue("G{$rowCount}", $row['khoahoc'] . ' - Học kỳ: ' . $row['hocky']);
   }

   //định dạng cột tiêu đề
   $sheet->getColumnDimension('A')->setAutoSize(true);
   $sheet->getColumnDimension('B')->setAutoSize(true);
   $sheet->getColumnDimension('C')->setAutoSize(true);
   $sheet->getColumnDimension('D')->setAutoSize(true);
   $sheet->getColumnDimension('E')->setAutoSize(true);
   $sheet->getColumnDimension('F')->setAutoSize(true);
   $sheet->getColumnDimension('G')->setAutoSize(true);

   //gán màu nền
   $sheet->getStyle('A1:G1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('00FF00');

   //căn giữa
   $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

   // Kẻ bảng 
   $styleArray = [
      'borders' => [
         'allborders' => [
            'style' => PHPExcel_Style_Border::BORDER_THIN
         ]
      ]
   ];
   $sheet->getStyle("A1:L{$rowCount}")->applyFromArray($styleArray);
   $objWriter = new PHPExcel_Writer_Excel2007($objExcel);
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
   ?>
   <!DOCTYPE html>
   <html>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <link rel="stylesheet" href="../teacher_homepage.css">
   <link rel="stylesheet" href="../teacher_info.css">
   <title>Quản lý học bổng sinh viên đại học</title>

   <body>
      <!-- header -->
      <div class="header">
         <span class="header-text">Quản lý học bổng</span>
         <span class="header-icon"><i class="fa-solid fa-circle-user"></i></span>
         <?php
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
               <a href="../manager_class/teacher_class.php">
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
               <a href="teacher_scholarship.php">
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
         <form method="post" action="">
            <div class="row">
               <div class="col" style="margin:10px">
                  <div class="input-group full-width">
                     <i class="fa-solid fa-user"></i>
                     <div class="form-field">
                        <label for="khoahoc_hocky">Khóa học và học kỳ</label>
                        <select name="khoahoc">
                           <option value="">Chọn khóa học và học kỳ</option>
                           <?php      
                                 if (isset($sql_khoahoc_hocky) && $sql_khoahoc_hocky->num_rows > 0) {
                                    while ($row = $sql_khoahoc_hocky->fetch_assoc()) {
                                       // Kiểm tra nếu giá trị đã được chọn
                                       $selected = (isset($_POST['khoahoc']) && $_POST['khoahoc'] == $row['khoahoc'] . '-Học kỳ:' . $row['hocky']) ? 'selected' : '';
                                       ?>
                           <option value="<?php echo $row['khoahoc'] . '-Học kỳ:' . $row['hocky']; ?>"
                              <?php echo $selected; ?>>
                              <?php echo $row['khoahoc'] . ' - Học kỳ: ' . $row['hocky']; ?>
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
            <button type="submit" class="btn btn-info" name="btnTimkiem"
               style="margin-left:370px; margin-top:10px; margin-bottom: 10px; margin-right: 60px"><i
                  class="fa-solid fa-magnifying-glass"></i> Tìm kiếm</button>
            <button class="btn btn-info" type="submit" name="btnXuat"><i class="fa-solid fa-file-export"></i> Xuất
               file</button>
            <button class="btn btn-danger" type="submit" name="btnXoa" style="margin-left:380px;"
               onclick="return confirm('Bạn có chắc chắn muốn xóa không ?')">Xoá tất cả <i
                  class="fa-solid fa-xmark"></i></button>
            <table class="table table-bordered" style="background-color: #3F72AF; color: #F9F7F7;">
               <thead style="background-color: #1B262C; color: #FADA7A; text-align: center;">
                  <tr>
                     <th>STT</th>
                     <th>Tên sinh viên</th>
                     <th>Mã sinh viên</th>
                     <th>Lớp</th>
                     <th>Tổng STC</th>
                     <th>Điểm TB (hệ số 4)</th>
                     <th>Loại học bổng</th>
                     <th>Khóa học - Học kỳ</th>
                  </tr>
               </thead>
               <tbody style="text-align: center;">
                  <?php
                  if ($result_top_students && $result_top_students->num_rows > 0) {
                  $i = 1;
                  while ($row = $result_top_students->fetch_assoc()) {
                  // Phân loại học bổng theo GPA
                  $loai_hoc_bong = '';
                  if ($row['gpa'] >= 3.6) {
                     $loai_hoc_bong = 'Xuất sắc';
                  } elseif ($row['gpa'] >= 3.2) {
                     $loai_hoc_bong = 'Giỏi';
                  } elseif ($row['gpa'] >= 2.5) {
                     $loai_hoc_bong = 'Khá';
                  } else {
                     $loai_hoc_bong = 'Không đạt học bổng';
                  }
                  ?>
                  <tr>
                     <td><?php echo $i++; ?></td>
                     <td><?php echo $row['hoten']; ?></td>
                     <td><?php echo $row['ma']; ?></td>
                     <td><?php echo $row['tenlop']; ?></td>
                     <td><?php echo $row['stc_hk']; ?></td>
                     <td><?php echo $row['gpa']; ?></td>
                     <td><?php echo $loai_hoc_bong; ?></td>
                     <td><?php echo $row['khoahoc'] . ' - Học kỳ: ' . $row['hocky']; ?></td>
                  </tr>
                  <?php
                     }
                  }
                  ?>
               </tbody>
            </table>
         </form>
      </article>
   </body>

   </html>

   </html>