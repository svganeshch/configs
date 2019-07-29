<?php
include('session.php');
include('jenkins_config.php');
error_reporting(E_ALL & ~E_NOTICE);

function responseHandler($url) {
    $data = curl_init();
    curl_setopt($data, CURLOPT_URL, $url);
    curl_setopt($data, CURLOPT_POST, 1);
    curl_setopt($data, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($data);
    curl_close($data);

    return $response;
}

$device = $_SESSION["cur_device"];
$device_url = 'https://'.urlencode(jenkins_username).':'.urlencode(jenkins_user_api).'@'.urlencode(jenkins_url).'/job/'.urlencode($device);
$build_url = $device_url.'/build?cause=Started+by+ArrowConfigs';
$lastBuild_url = $device_url.'/lastBuild/api/json?pretty=true';
$lastBuild_queue_url = $device_url.'/api/json?pretty=true';
$build_stop = $device_url.'/lastBuild/stop';

// stop the build
if($_POST['buildStop'] == 'yes') {
    // check if a build is running first
    $lastBuildInfo = responseHandler($lastBuild_url);
    $lastBuildInfo = json_decode($lastBuildInfo, true);
    $build_status = $lastBuildInfo['building'];

    if($build_status == 'true') {
        $stopRes=responseHandler($build_stop);
        exit($stopRes);
    }
    else exit("What are you trying to stop!\nThere's no build running currently!");
}

// remove from queue
if($_POST["buildRemoveQueue"] == 'yes') {
    $lastBuildQueueInfo = responseHandler($lastBuild_queue_url);
    $lastBuildQueueInfo = json_decode($lastBuildQueueInfo, true);
    if(isset($lastBuildQueueInfo['queueItem'])) {
        $queueID = $lastBuildQueueInfo['queueItem']['id'];
        $queue_url = 'https://'.urlencode(jenkins_username).':'.urlencode(jenkins_user_api).'@'.urlencode(jenkins_url).'/queue/cancelItem?id='.$queueID;
        $queueCancelRes = responseHandler($queue_url);
        if($queueCancelRes == "") exit("Removed the build from queue");
        else exit("Something went wrong while trying to remove build from queue\n".$queueCancelRes);
    }
    else {
        exit("There's no build in queue");
    }
}

if($_POST["buildTrigger"] == 'yes') {
    // check if a build is already running
    $lastBuildInfo = responseHandler($lastBuild_url);
    $lastBuildInfo = json_decode($lastBuildInfo, true);
    $build_status = $lastBuildInfo['building'];
    if($build_status == 'true') exit("A build is already running for your device!");

    // check if a build is already in queue
    $lastBuildQueueInfo = responseHandler($lastBuild_queue_url);
    $lastBuildQueueInfo = json_decode($lastBuildQueueInfo, true);
    if(isset($lastBuildQueueInfo['queueItem'])) {
        $msg = $lastBuildQueueInfo['queueItem']['why'];
        exit("There's a build already in queue for your device\n".$msg);
    }

    if(responseHandler($build_url) == "") exit("Build initiated!");
    else exit("Something went wrong!");
}
?>