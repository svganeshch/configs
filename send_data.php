<?php
include('session.php');
include('connect_moi.php');

function pushQuery($device_data, $row_name, $cur_device) {
    global $db;

    $check_query = "SELECT `$row_name` FROM `$cur_device`";
    $check_res = mysqli_query($db, $check_query) or die("Checking for table failed!" . mysqli_error($db));
    $check_res = mysqli_num_rows($check_res);

    if ($check_res == 0) {
        $final_query = "INSERT into `$cur_device` (`$row_name`) VALUES ('$device_data')";
    } else {
        $final_query="UPDATE `$cur_device` SET `$row_name`='$device_data'";
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

// Start
$cur_device = $_SESSION["cur_device"];

// device repo values
$repo_path_count = count($_POST["repo_paths"]);
$repo_clone_count = count($_POST["repo_clones"]);
$repo_clone_paths_count = count($_POST["repo_clones_paths"]);
$repo_topic_count = count($_POST["repopick_topics"]);
$repo_change_count = count($_POST["repopick_changes"]);

// device Switch vals and changelog
$global_override = $_POST["hidden_global_override"];
$is_official = $_POST["hidden_is_official"];
$test_build = $_POST["hidden_test_build"];
$force_clean = $_POST["hidden_force_clean"];
$buildtype = $_POST["hidden_buildtype"];
$bootimage = $_POST["hidden_bootimage"];
$changelog = $_POST["changelog"];

if ($_POST['hidden_override_lunch'] == 'yes') {

    $override_name = $_POST["lunch_override_name"];

    // Overriden device Json repos data query calls
    genJsonData($repo_path_count, 'ovr_repo_paths');
    genJsonData($repo_clone_count, 'ovr_repo_clones');
    genJsonData($repo_clone_count, 'ovr_repo_clones_paths');
    genJsonData($repo_topic_count, 'ovr_repopick_topics');
    genJsonData($repo_change_count, 'ovr_repopick_changes');

    // Overriden device Switch vals and changelog query calls
    pushQuery($is_official, 'ovr_is_official', $cur_device);
    pushQuery($test_build, 'ovr_test_build', $cur_device);
    pushQuery($force_clean, 'ovr_force_clean', $cur_device);
    pushQuery($buildtype, 'ovr_buildtype', $cur_device);
    pushQuery($bootimage, 'ovr_bootimage', $cur_device);
    pushQuery($changelog, 'ovr_changelog', $cur_device);
    pushQuery($override_name, 'lunch_override_name', $cur_device);

} else {

    // Json repos data query calls
    genJsonData($repo_path_count, 'repo_paths');
    genJsonData($repo_clone_count, 'repo_clones');
    genJsonData($repo_clone_count, 'repo_clones_paths');
    genJsonData($repo_topic_count, 'repopick_topics');
    genJsonData($repo_change_count, 'repopick_changes');

    // Switch vals and changelog query calls
    if (isset($_POST['hidden_global_override'])) {
        pushQuery($global_override, 'global_override', $cur_device);
    }
    pushQuery($is_official, 'is_official', $cur_device);
    pushQuery($test_build, 'test_build', $cur_device);
    pushQuery($force_clean, 'force_clean', $cur_device);
    pushQuery($buildtype, 'buildtype', $cur_device);
    pushQuery($bootimage, 'bootimage', $cur_device);
    pushQuery($changelog, 'changelog', $cur_device);

}

?>