<?php
include('session.php');
include('connect_moi.php');

function pushQuery($json_data, $row_name, $cur_device) {
    global $db;

    $check_query = "SELECT `$row_name` FROM `$cur_device`";
    $check_res = mysqli_query($db, $check_query) or die("Checking for table failed!" . mysqli_error($db));
    $check_res = mysqli_num_rows($check_res);

    if ($check_res == 0) {
        $final_query = "INSERT into `$cur_device` (`$row_name`) VALUES ('$json_data')";
    } else {
        $final_query="UPDATE `$cur_device` SET `$row_name`='$json_data'";
    }

    $final_res = mysqli_query($db, $final_query) or die(mysqli_error($db));

    if (empty($final_res)) {
        echo "Failed to insert into database!";
    } else {
        echo "Successfully inserted into database";
    }

}

function genJsonData ($counts, $key_value_name) {
    global $cur_device;

    for ($i=0; $i < $counts; $i++) { 
        if (trim($_POST["$key_value_name"][$i] != '')) {
            $content [] = $_POST["$key_value_name"][$i];
        }
    }
    $repo_data = array($key_value_name => $content);
    $repo_data = json_encode($repo_data);
    pushQuery($repo_data, $key_value_name, $cur_device);
}

// Constants
$repo_path_count = count($_POST["repo_paths"]);
$repo_clone_count = count($_POST["repo_clones"]);
$repo_topic_count = count($_POST["repopick_topics"]);
$repo_change_count = count($_POST["repopick_changes"]);

$cur_device = $_SESSION["cur_device"];

genJsonData($repo_path_count, 'repo_paths');
genJsonData($repo_clone_count, 'repo_clones');
genJsonData($repo_topic_count, 'repopick_topics');
genJsonData($repo_change_count, 'repopick_changes');

?>