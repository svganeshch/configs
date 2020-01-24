<?php
include('session.php');
include('jenkins_config.php');
error_reporting(E_ALL & ~E_NOTICE);

$device = $_SESSION["cur_device"];
$blu_builder_pipeline_url = 'https://'.urlencode(jenkins_username).':'.urlencode(jenkins_user_api).'@'.urlencode(jenkins_url).'/blue/rest/organizations/jenkins/pipelines/'.urlencode(builder_pipeline);
$leg_builder_pipeline_url = 'https://'.urlencode(jenkins_username).':'.urlencode(jenkins_user_api).'@'.urlencode(jenkins_url).'/job/'.urlencode(builder_pipeline);
$kicker_pipeline_url = 'https://'.urlencode(jenkins_username).':'.urlencode(jenkins_user_api).'@'.urlencode(jenkins_url).'/job/'.urlencode(kicker_pipeline);

$kicker_build_pipeline_url = $kicker_pipeline_url.'/buildWithParameters?cause=Started+by+ArrowConfigs&active_devices=';
$lastBuild_url = $blu_builder_pipeline_url.'/runs/?pretty=true';

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

// get build status functions
function isBuildRunning($cur_dev) {
    global $lastBuild_url;
    $lastBuildInfo = getResponseHandler($lastBuild_url);
    $lastBuildInfo = json_decode($lastBuildInfo, true);

    foreach($lastBuildInfo as $buildInfo) {
        if($buildInfo['name'] == $cur_dev) {
            if($buildInfo['state'] == 'RUNNING') return true;
            else false;
        }
    }
}

function isBuildInQueue($cur_dev) {
    global $lastBuild_url;
    $lastBuildInfo = getResponseHandler($lastBuild_url);
    $lastBuildInfo = json_decode($lastBuildInfo, true);

    foreach($lastBuildInfo as $buildInfo) {
        if($buildInfo['name'] == $cur_dev) {
            if($buildInfo['state'] == 'QUEUED') return true;
            else return false;
        }
    }
}

function getBuildID($cur_dev) {
    global $lastBuild_url;
    $lastBuildInfo = getResponseHandler($lastBuild_url);
    $lastBuildInfo = json_decode($lastBuildInfo, true);

    foreach($lastBuildInfo as $buildInfo) {
        if($buildInfo['name'] == $cur_dev) return($buildInfo['id']);
    }
}

// GetBuildStatus
if(isset($_POST['getBuildStatus']) && $_POST['getBuildStatus'] == 'yes') {
    if(isBuildRunning($device)) exit("building");
    elseif(isBuildInQueue($device)) exit("waiting");
    else exit("idle");
}

// Jenkins button actions
// stop the build
if(isset($_POST['buildStop']) && $_POST['buildStop'] == 'yes') {
    $build_stop_url = $leg_builder_pipeline_url.'/'.getBuildID($device).'/stop';

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
        $queue_url = $leg_builder_pipeline_url.'/'.getBuildID($device).'/stop';
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

    if(responseHandler($kicker_build_pipeline_url.$device.'&version='.$_SESSION['got_version']) == "") exit("Build initiated!");
    else exit("Something went wrong!");
}

// get all non-revoked devices and initiate builds
if(isset($_POST["PipelineBuildTrigger"]) && $_POST["PipelineBuildTrigger"] == 'yes') {
    $active_devices = array();
    $get_active_devices_query = "SELECT `maintainer_device` FROM `login` WHERE `maintainer_device`!='all' and `status`='active'";
    $active_devices_list = mysqli_query($db, $get_active_devices_query) or die("Failed to fetch active devices".mysqli_error($db));
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
        $check_device_override_query = "SELECT `lunch_override_state` FROM `$params`";
        $check_device_override_res = mysqli_query($db, $check_device_override_query) or die(mysqli_error($db));
        $check_device_override_res = mysqli_fetch_assoc($check_device_override_res)['lunch_override_state'];

        if($check_device_override_res == 'yes') {
            $check_weekly_opt_query = "SELECT `ovr_weeklies_opt` FROM `$params`";
            $check_weekly_opt_res = mysqli_query($db, $check_weekly_opt_query) or die(mysqli_error($db));
            $check_weekly_opt_res = mysqli_fetch_assoc($check_weekly_opt_res)['ovr_weeklies_opt'];
        } else {
            $check_weekly_opt_query = "SELECT `weeklies_opt` FROM `$params`";
            $check_weekly_opt_res = mysqli_query($db, $check_weekly_opt_query) or die(mysqli_error($db));
            $check_weekly_opt_res = mysqli_fetch_assoc($check_weekly_opt_res)['weeklies_opt'];
        }

        if($check_weekly_opt_res != null && $check_weekly_opt_res == 'yes') {
            $kicker_build_pipeline_url = $kicker_build_pipeline_url.$params.',';
        } else if($check_weekly_opt_res != null && $check_weekly_opt_res == 'no') {
            $trip_opts_query = "UPDATE `$params` SET `opts`=`opts`+1";
            mysqli_query($db, $trip_opts_query) or die(mysqli_error($db));
        }
    }

    $pipeline_response = responseHandler($kicker_build_pipeline_url);

    if ($pipeline_response == "") exit("Pipeline triggered!");
    else exit("Failed to trigger pipeline!\n".$pipeline_response);
}
?>