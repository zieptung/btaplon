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