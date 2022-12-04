<?php
   $path = "/var/www/html";
   require_once($path . '/helpers/login_connect_moi.php');
   if(!isset($_SESSION)) {
      session_start();
   }

   $user_check = $_SESSION['login_user'];

   $ses_sql = mysqli_query($login_db,"SELECT `username` FROM `login` WHERE `username` = '$user_check' ");

   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);

   $login_session = $row['username'];

   if(!isset($login_session)){
      header("location: ../index.php");
      die();
   }
?>