<?php
   define('DB_SERVER', 'localhost:3306');
   define('DB_USERNAME', 'nani');
   define('DB_PASSWORD', 'nani');
   define('DB_DATABASE', 'jenkins');
   $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
   if (!$db) {
	die("Database Connection Failed" . mysqli_error($connection));
   }
?>
