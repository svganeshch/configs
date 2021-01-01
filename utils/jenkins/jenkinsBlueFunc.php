<?php
error_reporting(E_ALL & ~E_NOTICE);

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/utils/session.php');
require_once($path . '/utils/data/get_data.php');
require_once($path . '/config/jenkins_config.php');
require_once($path . '/helpers/devices_connect_moi.php');

$device = $_SESSION["cur_device"];
$blu_builder_pipeline_url = 'https://'.urlencode(jenkins_username).':'.urlencode(jenkins_user_api).'@'.urlencode(jenkins_url).'/blue/rest/organizations/jenkins/pipelines/'.urlencode(builder_pipeline);
$leg_builder_pipeline_url = 'https://'.urlencode(jenkins_username).':'.urlencode(jenkins_user_api).'@'.urlencode(jenkins_url).'/job/'.urlencode(builder_pipeline);
$kicker_pipeline_url = 'https://'.urlencode(jenkins_username).':'.urlencode(jenkins_user_api).'@'.urlencode(jenkins_url).'/job/'.urlencode(kicker_pipeline);

$kicker_build_pipeline_url = $kicker_pipeline_url.'/buildWithParameters?cause=Started+by+ArrowConfigs&active_devices=';
$lastBuild_url = $blu_builder_pipeline_url.'/runs';
$leg_lastBuild_url = $leg_builder_pipeline_url;

function getResponseHandler($url) {
    $data = curl_init();
    curl_setopt($data, CURLOPT_URL, $url);
    curl_setopt($data, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($data);
    curl_close($data);

    return $response;
}

function responseHandler($url) {
    $data = curl_init();
    curl_setopt($data, CURLOPT_URL, $url);
    curl_setopt($data, CURLOPT_POST, 1);
    curl_setopt($data, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($data);
    curl_close($data);

    return $response;
}

function setBuildID($cur_dev) {
    global $lastBuild_url;
    $lastBuildInfo = getResponseHandler($lastBuild_url.'/?pretty=true');
    $lastBuildInfo = json_decode($lastBuildInfo, true);

    foreach($lastBuildInfo as $buildInfo) {
        if($buildInfo['name'] == $cur_dev.' ('.explode('_', $_SESSION['got_version'])[0].')'.' ('.$_SESSION["device_profile"].')') {
            if($buildInfo['state'] == 'RUNNING' || $buildInfo['state'] == 'QUEUED')
                $_SESSION['jenkins_build_id'] = $buildInfo['id'];
        }
    }
}

if(!isset($_SESSION['jenkins_build_id']))
    setBuildID($device);

// get build status functions
function isBuildRunning($cur_dev) {
    global $lastBuild_url;
    $lastBuildInfo = getResponseHandler($lastBuild_url.'/'.$_SESSION['jenkins_build_id'].'/?pretty=true');
    $lastBuildInfo = json_decode($lastBuildInfo, true);

    if($lastBuildInfo['state'] == 'RUNNING') return true;
    else false;
}

function isBuildInQueue($cur_dev) {
    global $lastBuild_url;
    $lastBuildInfo = getResponseHandler($lastBuild_url.'/'.$_SESSION['jenkins_build_id'].'/?pretty=true');
    $lastBuildInfo = json_decode($lastBuildInfo, true);

    if($lastBuildInfo['state'] == 'QUEUED') return true;
    else return false;
}

// GetBuildStatus
if(isset($_POST['getBuildStatus']) && $_POST['getBuildStatus'] == 'yes') {
    if($_SESSION['jenkins_build_id'] == null) exit("idle");
    elseif(isBuildRunning($device)) exit("building");
    elseif(isBuildInQueue($device)) exit("waiting");
    else exit("---");
}

// Jenkins button actions
// stop the build
if(isset($_POST['buildStop']) && $_POST['buildStop'] == 'yes') {
    $build_stop_url = $leg_builder_pipeline_url.'/'.$_SESSION['jenkins_build_id'].'/stop';

    if(isBuildInQueue($device)) {
        exit("There's a build waiting in queue!\nTry remove from queue option instead.");
    }

    if(isBuildRunning($device)) {
        $stopRes=responseHandler($build_stop_url);
        if($stopRes == "") exit("Stopped the build!");
        else exit("Something went wrong while trying to stop the build".$stopRes);
    }
    else exit("What are you trying to stop!\nThere's no build running currently!");
}

// remove from queue
if(isset($_POST["buildRemoveQueue"]) && $_POST["buildRemoveQueue"] == 'yes') {
    if(isBuildInQueue($device)) {
        $queue_url = $leg_builder_pipeline_url.'/'.$_SESSION['jenkins_build_id'].'/stop';
        $queueCancelRes = responseHandler($queue_url);
        if($queueCancelRes == "") exit("Removed the build from queue");
        else exit("Something went wrong while trying to remove build from queue\n".$queueCancelRes);
    }
    else {
        exit("There's no build in queue");
    }
}

// initiate a build
if(isset($_POST["buildTrigger"]) && $_POST["buildTrigger"] == 'yes') {
    // check if a build is already running
    if(isBuildRunning($device)) exit("A build is already running for your device!");

    // check if a build is already in queue
    if(isBuildInQueue($device)) {
        exit("There's a build already in queue for your device");
    }

    $force_node = getDataValue("common_config", "force_node");
    
    if($_SESSION['device_profile'] == "official" && !$_SESSION['is_admin'])
        exit("Initiating builds from official profile is forbidden for non-admins, please refresh the page or choose appropriate profile!");

    if(responseHandler($kicker_build_pipeline_url.$device.'&version='.$_SESSION['got_version'].'&force_node='.$force_node.'&device_profile='.$_SESSION['device_profile']) == "") {
        unset($_SESSION['jenkins_build_id']);
        setBuildID($device);
        exit("Build initiated!");
    } else exit("Something went wrong!");
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

    $kicker_build_pipeline_url = substr($kicker_build_pipeline_url, 0, -1);
    $force_node = getDataValue("common_config", "force_node");
    $pipeline_response = responseHandler($kicker_build_pipeline_url.'&version='.$_SESSION['got_version'].'&force_node='.$force_node.'&device_profile='.$_SESSION['device_profile']);

    if ($pipeline_response == "") exit("Pipeline triggered!");
    else exit("Failed to trigger pipeline!\n".$pipeline_response);
}
?>
