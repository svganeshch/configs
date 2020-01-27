<?php
include('session.php');
require('devices_connect_moi.php');

function getData($cur_device) {
    global $devices_db;
    global $result_array;

    $get_data_query = "SELECT * FROM `$cur_device`";
    $get_data_query_res = mysqli_query($devices_db, $get_data_query) or die("Fetching data from table failed!" . mysqli_error($devices_db));
    $get_data_query_rows = mysqli_num_rows($get_data_query_res);

    if ($get_data_query_rows > 0) {
        while($row = mysqli_fetch_assoc($get_data_query_res)) {
            array_push($result_array, $row);
        }
    }
}

// Start
$cur_device = $_SESSION["cur_device"];
$result_array = array();

getData($cur_device);

echo json_encode($result_array);

?>