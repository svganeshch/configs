<?php
include('session.php');
error_reporting(E_ALL & ~E_NOTICE);

function get_maintainers() {
    global $db;
    global $maintainer_data;

    $get_maintainer_data_query = "SELECT `username`,`maintainer_device`,`status`,`is_admin` FROM `login`";
    $get_maintainer_data_query_res = mysqli_query($db, $get_maintainer_data_query) or die("Fetching maintainer names failed!" . mysqli_error($db));
    $get_maintainer_data_query_rows = mysqli_num_rows($get_maintainer_data_query_res);

    if ($get_maintainer_data_query_rows > 0) {
        while($row = mysqli_fetch_assoc($get_maintainer_data_query_res)) {
            array_push($maintainer_data, $row);
        }
    }
}

$maintainer_data = array();
get_maintainers();
exit(json_encode($maintainer_data));
?>