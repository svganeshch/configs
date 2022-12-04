<?php
error_reporting(E_ALL & ~E_NOTICE);

$path = "/var/www/html";
require_once($path . '/utils/session.php');
require_once($path . '/helpers/devices_connect_moi.php');

function get_maintainers() {
    global $devices_db;
    global $login_db;
    global $maintainer_data;
    global $maintainer_admin;

    $get_maintainer_data_query = "SELECT `username`,`maintainer_device`,`status` FROM `device_maintainers`";
    $get_maintainer_data_query_res = mysqli_query($devices_db, $get_maintainer_data_query) or die("Fetching maintainer names failed!" . mysqli_error($devices_db));
    $get_maintainer_data_query_rows = mysqli_num_rows($get_maintainer_data_query_res);

    if ($get_maintainer_data_query_rows > 0) {
        while($row = mysqli_fetch_assoc($get_maintainer_data_query_res)) {
            $get_maintainer_admin_query = "SELECT `is_admin` FROM `login` WHERE `username`='".$row['username']."'";
            $get_maintainer_admin_query_res = mysqli_query($login_db, $get_maintainer_admin_query) or die("Fetching maintainer admin status failed!" . mysqli_error($login_db));
            $get_maintainer_admin_query_res = mysqli_fetch_assoc($get_maintainer_admin_query_res)['is_admin'];

            $row['is_admin'] = $get_maintainer_admin_query_res;

            array_push($maintainer_data, $row);
        }
    }
}

$maintainer_data = array();
get_maintainers();
exit(json_encode($maintainer_data));
?>