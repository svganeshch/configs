<?php
error_reporting(E_ALL & ~E_NOTICE);

$path = "/var/www/html";
require_once($path . '/utils/session.php');
require_once($path . '/utils/data/get_data.php');
require_once($path . '/config/jenkins_config.php');
require_once($path . '/helpers/devices_connect_moi.php');

$device = $_SESSION["cur_device"];
// Base urls
$builder_pipeline_url = "https://".urlencode(jenkins_url)."/job/" . urlencode(builder_pipeline);
$scheduler_build_pipeline_url = "https://".urlencode(jenkins_url)."/job/" . urlencode(scheduler_pipeline); 

// Trigger urls
$trigger_builder_pipeline_url = $builder_pipeline_url . "/buildWithParameters";
$trigger_scheduler_pipeline_url  = $scheduler_build_pipeline_url . "/buildWithParameters?cause=Started+by+ArrowConfigs&active_devices=";

// utility urls/strings
$pretty_json =  "?pretty=true";
$allBuildsurl = $builder_pipeline_url . "/api/json?tree=allBuilds[id,inProgress,displayName,description]";

// Set active device build id (running or queued)
if(!isset($_SESSION["jenkins_build_id"])){
    setIdActiveBuild($device);
}

if(isset($_POST["getBuildStatus"]) && $_POST["getBuildStatus"] == "yes"){
    if($_SESSION["jenkins_build_id"] != null){
        exit("idle");
    }elseif(checkBuildStatus("Executing")){
        exit("building");
    }elseif(checkBuildStatus("Waiting")){
        exit("waiting");
    }else{
        exit("---");
    }
}

// Stop running build
if(isset($_POST["buildStop"]) && $_POST["buildStop"] == "yes"){
    $url = $builder_pipeline_url . "/" . $_SESSION["jenkins_build_id"] . "/stop";

    if(checkBuildStatus("Waiting")){
        exit("There is already a build waiting in queue for this device \nTry to remove it from the queue instead");
    }

    if(checkBuildStatus("Executing")){
        $response = curlCall($url, true);
        if($response == ""){
            exit("The build has been stopped");
            setIdActiveBuild($device);
        }else{
            exit("");
        }
        exit("There is already a build running in this device \nTry to stop it instead");
    }else{
        exit("What are you trying to stop!\nThere's no build running currently!");
    }
}

// Remove build from queue
if(isset($_POST["buildRemoveQueue"]) && $_POST["buildRemoveQueue"] == 'yes') {
    // Check if the build is actually in queue
    if(checkBuildStatus("Waiting")) {
        $url = $builder_pipeline_url.'/'.$_SESSION['jenkins_build_id'].'/stop';
        $response = curlCall($url, true);
        if($response == "") exit("Removed the build from queue");
        else exit("Something went wrong while trying to remove build from queue\n".$response);
    }
    else {
        exit("There's no build in queue");
    }
}

// initiate a build
if(isset($_POST["buildTrigger"]) && $_POST["buildTrigger"] == 'yes') {
    // check if a build is already running
    if(checkBuildStatus("Executing")) {
        exit("A build is already running for your device!");
    }

    // check if a build is already in queue
    if(checkBuildStatus("Waiting")) {
        exit("There's a build already in queue for your device");
    }

    $force_node = getDataValue("common_config", "force_node");

    if($_SESSION['device_profile'] == "official" && !$_SESSION['is_admin']) {
        exit("Initiating builds from official profile is forbidden for non-admins, please refresh the page or choose appropriate profile!");
    }

    if(curlCall($trigger_scheduler_pipeline_url . $device.'&version=' . $_SESSION['got_version'] . '&force_node=' . $force_node . '&device_profile=' . $_SESSION['device_profile'], true) == "") {
        unset($_SESSION['jenkins_build_id']);
        setIdActiveBuild($device);
        exit("Build initiated!");
    } else {
        exit("Something went wrong!");
    }
}

// get all non-revoked devices and initiate builds
if(isset($_POST["PipelineBuildTrigger"]) && $_POST["PipelineBuildTrigger"] == 'yes') {
    $active_devices = array();
    $get_active_devices_query = "SELECT `maintainer_device` FROM `device_maintainers` WHERE `status`='active'";
    $active_devices_list = mysqli_query($devices_db, $get_active_devices_query) or die("Failed to fetch active devices".mysqli_error($devices_db));
    $active_devices_list = mysqli_fetch_all($active_devices_list, MYSQLI_ASSOC);

    $maintainer_devices = array_merge_recursive(...$active_devices_list);
    foreach($maintainer_devices['maintainer_device'] as $got_devices) {
        foreach(explode(' ', $got_devices) as $ad) {
            if (!in_array($ad, $active_devices))
                array_push($active_devices, $ad);
        }
    }

    // Construct pipline url with parameters
    foreach($active_devices as $params) {
        // Check if the device is opted for weekly
        $check_weekly_opt_query = "SELECT `weeklies_opt` FROM `$params`";
        $check_weekly_opt_res = mysqli_query($devices_db, $check_weekly_opt_query) or die(mysqli_error($devices_db));
        $check_weekly_opt_res = mysqli_fetch_assoc($check_weekly_opt_res)['weeklies_opt'];

        if($check_weekly_opt_res != null && $check_weekly_opt_res == 'yes') {
            $kicker_build_pipeline_url = $kicker_build_pipeline_url.$params.',';
        } else if($check_weekly_opt_res != null && $check_weekly_opt_res == 'no') {
            $trip_opts_query = "UPDATE `$params` SET `opts`=`opts`+1";
            mysqli_query($devices_db, $trip_opts_query) or die(mysqli_error($devices_db));
        }
    }

    $trigger_scheduler_pipeline_url = substr($kicker_build_pipeline_url, 0, -1);
    $force_node = getDataValue("common_config", "force_node");
    $pipeline_response = curlCall($kicker_build_pipeline_url.'&version='.$_SESSION['got_version'].'&force_node='.$force_node.'&device_profile='.$_SESSION['device_profile'], true);

    if ($pipeline_response == "") exit("Pipeline triggered!");
    else exit("Failed to trigger pipeline!\n".$pipeline_response);
}

// given a string return true if the build description for the active id contains it
function checkBuildStatus($strToLook){
    global $builder_pipeline_url;
    global $pretty_json;
    $checkStatusurl = $builder_pipeline_url . "/" . $_SESSION["jenkins_build_id"] . "/api/json" . $pretty_json;
    $response = json_decode(curlCall($checkStatusurl, false), true);
    return str_contains($response["description"], $strToLook);
}

// Get all builds and check the builds for the selected device and get the id of the last running or queued build
function setIdActiveBuild($cur_dev) {
    global $allBuildsurl;
    $allBuildsInfo = json_decode(curlCall($allBuildsurl, false), true);

    foreach($allBuildsInfo["allBuilds"] as $build) {
        // Lets itterate through all the builds, when the build name matches the current device and the build is 
        // in progress or queued state then set the build id in session
        if(str_contains($build["displayName"], $cur_dev)) {
            // Running build or queued
            if(($build["inProgress"] && !$build["description"] == "Waiting for Executor") || ($build["inProgress"] && $build["description"]) == "Waiting for Executor") {
                $_SESSION["jenkins_build_id"] = $build["id"];
                break;
            }
        }
    }
}

// prepare a curl GET or POST request and return the response
function curlCall($url, $isPost) {
    global $jenkins_authentication;
    $ch = curl_init($url);
    if($isPost){
        curl_setopt($ch, CURLOPT_POST, $isPost);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_USERPWD, $jenkins_authentication);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}
