<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include($path . '/utils/session.php');
require($path . '/helpers/devices_connect_moi.php');

function getData($cur_device) {
    global $devices_db;
    $result_array = array();

    $get_data_query = "SELECT * FROM `$cur_device`";
    $get_data_query_res = mysqli_query($devices_db, $get_data_query) or die("Fetching data from table failed!" . mysqli_error($devices_db));
    $get_data_query_rows = mysqli_num_rows($get_data_query_res);

    if ($get_data_query_rows > 0) {
        while($row = mysqli_fetch_assoc($get_data_query_res)) {
            array_push($result_array, $row);
        }
    }
    return $result_array;
}

/* Returns value of the column
   param 1 - table name
   param 2 - column name
*/
function getDataValue($device_table, $col_name) {
    global $devices_db;

    $get_data_query = "SELECT `$col_name` FROM `$device_table`";
    $get_data_query_res = mysqli_query($devices_db, $get_data_query) or die("Fetching data from table failed!" . mysqli_error($devices_db));
    $get_data_query_rows = mysqli_num_rows($get_data_query_res);

    if ($get_data_query_rows > 0) {
        while($row = mysqli_fetch_assoc($get_data_query_res)) {
            return $row[$col_name];
        }
    }
}

// getData
if (isset($_POST['getData']) && $_POST['getData'] == 'yes') {
    $cur_device = $_SESSION["cur_device"];
    exit(json_encode(getData($cur_device)));
}
?>