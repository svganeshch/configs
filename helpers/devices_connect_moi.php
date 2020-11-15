<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include($path . '/utils/session.php');
require_once($path . '/config/dbcon_config.php');

if (isset($_SESSION['got_version'])) {
   foreach ($VERSIONS as $version => $version_url) {
      if ($_SESSION['got_version'] == $version) {
         $devices_list_url = $version_url;
         $_SESSION['got_version'] = $version;
      }
   }
} else {
   // Always/Initially fallback to current version in case if no version is passed
   $devices_list_url = $VERSIONS['arrow-10.0'];
   $_SESSION['got_version'] = 'arrow-10.0';
}

define('DB_DATABASE_DEVICES', 'configs_' . $_SESSION['got_version']);
$devices_db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE_DEVICES);
if (!$devices_db) {
   die("Database Connection Failed" . mysqli_error($devices_db));
}

define('DB_DATABASE_DEVICES_TEST_PROFILE', 'configs_test_profile_' . $_SESSION['got_version']);
$devices_test_profile_db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE_DEVICES_TEST_PROFILE);
if (!$devices_test_profile_db) {
   die("Database test profile Connection Failed" . mysqli_error($devices_test_profile_db));
}

// get maintainer device
$maintainer_device_query = "SELECT `maintainer_device` FROM `device_maintainers` WHERE `username`='" . $_SESSION['login_user'] . "'";
$maintainer_device = mysqli_query($devices_db, $maintainer_device_query) or die(mysqli_error($devices_db));
$maintainer_device = mysqli_fetch_assoc($maintainer_device);
$maintainer_device = $maintainer_device['maintainer_device'];
$_SESSION['maintainer_device'] = $maintainer_device;

// get maintainer status
$maintainer_status_query = "SELECT `status` FROM `device_maintainers` WHERE `username`='" . $_SESSION['login_user'] . "'";
$maintainer_status = mysqli_query($devices_db, $maintainer_status_query) or die(mysqli_error($devices_db));
$maintainer_status = mysqli_fetch_assoc($maintainer_status);
$maintainer_status = $maintainer_status['status'];
$_SESSION['maintainer_status'] = $maintainer_status;
?>
