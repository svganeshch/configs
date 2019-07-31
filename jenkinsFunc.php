<?php
include('session.php');
include('jenkins_config.php');
//error_reporting(E_ALL & ~E_NOTICE);

function responseHandler($url) {
    $data = curl_init();
    curl_setopt($data, CURLOPT_URL, $url);
    curl_setopt($data, CURLOPT_POST, 1);
    curl_setopt($data, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($data);
    curl_close($data);

    return $response;
}

$headers = [];
function buildOutputHandler($url) {
    $data = curl_init();
    global $headers;
    $response = [];
    curl_setopt($data, CURLOPT_URL, $url);
    curl_setopt($data, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($data, CURLOPT_HEADERFUNCTION,
        function($curl, $header) use (&$headers)
        {
            $len = strlen($header);
            $header = explode(':', $header, 2);
            if (count($header) < 2) // ignore invalid headers
            return $len;

            $headers[strtolower(trim($header[0]))][] = trim($header[1]);

            return $len;
        }
    );
    $response['body'] = curl_exec($data);
    $response['headers'] = json_encode($headers);
    return json_encode($response);
}

$device = $_SESSION["cur_device"];
$device_url = 'https://'.urlencode(jenkins_username).':'.urlencode(jenkins_user_api).'@'.urlencode(jenkins_url).'/job/'.urlencode($device);
$build_url = $device_url.'/build?cause=Started+by+ArrowConfigs';
$lastBuild_url = $device_url.'/lastBuild/api/json?pretty=true';
$lastBuild_queue_url = $device_url.'/api/json?pretty=true';
$build_stop_url = $device_url.'/lastBuild/stop';

// get build status functions
function isBuildRunning() {
    global $lastBuild_url;
    $lastBuildInfo = responseHandler($lastBuild_url);
    $lastBuildInfo = json_decode($lastBuildInfo, true);
    $build_status = $lastBuildInfo['building'];

    if($build_status == 'true') return true;
    else return false;
}

function getBuildInfo($key) {
    global $lastBuild_url;
    $lastBuildInfo = responseHandler($lastBuild_url);
    $lastBuildInfo = json_decode($lastBuildInfo, true);
    $key_info = $lastBuildInfo[$key];

    if($key_info != "") return $key_info;
}

function isBuildInQueue() {
    global $lastBuild_queue_url;
    $lastBuildQueueInfo = responseHandler($lastBuild_queue_url);
    $lastBuildQueueInfo = json_decode($lastBuildQueueInfo, true);
    if(isset($lastBuildQueueInfo['queueItem'])) return true;
    else return false;
}

function getBuildQueueId() {
    global $lastBuild_queue_url;
    $lastBuildQueueInfo = responseHandler($lastBuild_queue_url);
    $lastBuildQueueInfo = json_decode($lastBuildQueueInfo, true);
    if(isset($lastBuildQueueInfo['queueItem'])) return $lastBuildQueueInfo['queueItem']['id'];
    else return "";
}

function getBuildQueueMsg() {
    global $lastBuild_queue_url;
    $lastBuildQueueInfo = responseHandler($lastBuild_queue_url);
    $lastBuildQueueInfo = json_decode($lastBuildQueueInfo, true);
    if(isset($lastBuildQueueInfo['queueItem'])) return $lastBuildQueueInfo['queueItem']['why'];
    else return "";
}

function getBuildOutput($headerTextSize) {
    global $device_url;
    $build_output_url = $device_url.'/lastBuild/logText/progressiveText?start='.urlencode($headerTextSize[0]);
    $buildOutput = buildOutputHandler($build_output_url);
    return $buildOutput;
}

// GetBuildStatus
if(isset($_POST['getBuildStatus']) && $_POST['getBuildStatus'] == 'yes') {
    if(isBuildRunning()) exit("building");
    elseif(isBuildInQueue()) exit("waiting");
    else exit("idle");
}

// GetProgressStatus
if(isset($_POST['getProgressStatus']) && $_POST['getProgressStatus'] == 'yes') {
    if(isBuildRunning()) {
        $currentTime = time();
        $buildTime = round(getBuildInfo("timestamp")/1000);
        $buildEstimatedDuration = getBuildInfo("estimatedDuration");
        $progressRes = round(($currentTime - $buildTime) / $buildEstimatedDuration * 100);
        if($progressRes == 0)exit("1");
        else exit($progressRes);
    }
}

// GetBuildOutput
if(isset($_POST['getBuildOutput']) && $_POST['getBuildOutput'] == 'yes') {
    if(isset($_POST['headerTextSize'])) {
    $responseData = getBuildOutput($_POST['headerTextSize']);
    exit($responseData);
    } else exit();
}

// Jenkins button actions
// stop the build
if(isset($_POST['buildStop']) && $_POST['buildStop'] == 'yes') {
    if(isBuildInQueue()) {
        exit("There's a build waiting in queue!\nTry remove from queue option instead.");
    }

    if(isBuildRunning()) {
        $stopRes=responseHandler($build_stop_url);
        if($stopRes == "") exit("Stopped the build!");
        else exit("Something went wrong while trying to stop the build");
    }
    else exit("What are you trying to stop!\nThere's no build running currently!");
}

// remove from queue
if(isset($_POST["buildRemoveQueue"]) && $_POST["buildRemoveQueue"] == 'yes') {
    if(isBuildInQueue()) {
        $queueID = getBuildQueueId();
        $queue_url = 'https://'.urlencode(jenkins_username).':'.urlencode(jenkins_user_api).'@'.urlencode(jenkins_url).'/queue/cancelItem?id='.$queueID;
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
    if(isBuildRunning()) exit("A build is already running for your device!");

    // check if a build is already in queue
    if(isBuildInQueue()) {
        $msg = getBuildQueueMsg();
        exit("There's a build already in queue for your device\n".$msg);
    }

    if(responseHandler($build_url) == "") exit("Build initiated!");
    else exit("Something went wrong!");
}
?>