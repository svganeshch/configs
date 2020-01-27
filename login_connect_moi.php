<?php
   require_once('dbcon_config.php');

   $login_db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE_LOGIN);
   if (!$login_db) {
      die("Database Connection Failed" . mysqli_error($login_db));
   }
?>
