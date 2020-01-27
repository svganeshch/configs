<?php
   include('session.php');
   require_once('dbcon_config.php');

   if ($_SESSION['got_version'] == 'arrow-9.x') {
      $devices_list_url = DEVICES_LIST_URL_PIE;
   } else if ($_SESSION['got_version'] == 'arrow-10.0') {
      $devices_list_url = DEVICES_LIST_URL_Q;
   } else {
      // Fallback to current version in case if no version is passed
      $devices_list_url = DEVICES_LIST_URL_Q;
      $_SESSION['got_version'] = 'arrow-10.0';
   }

   define('DB_DATABASE_DEVICES', 'configs_'.$_SESSION['got_version']);
   $devices_db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE_DEVICES);
   if (!$devices_db) {
      die("Database Connection Failed" . mysqli_error($devices_db));
   }

   // get maintainer device
   $maintainer_device_query = "SELECT `maintainer_device` FROM `device_maintainers` WHERE `username`='".$_SESSION['login_user']."'";
   $maintainer_device = mysqli_query($devices_db, $maintainer_device_query) or die(mysqli_error($devices_db));
   $maintainer_device = mysqli_fetch_assoc($maintainer_device);
   $maintainer_device = $maintainer_device['maintainer_device'];
   $_SESSION['maintainer_device'] = $maintainer_device;

   // get maintainer status
   $maintainer_status_query = "SELECT `status` FROM `device_maintainers` WHERE `username`='".$_SESSION['login_user']."'";
   $maintainer_status = mysqli_query($devices_db, $maintainer_status_query) or die(mysqli_error($devices_db));
   $maintainer_status = mysqli_fetch_assoc($maintainer_status);
   $maintainer_status = $maintainer_status['status'];
   $_SESSION['maintainer_status'] = $maintainer_status;
?>
