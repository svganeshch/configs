<?php
error_reporting(E_ALL & ~E_NOTICE);

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/utils/session.php');

$invalidFields = array();
$repoPaths = $_POST["repo_paths"];
$repoCloneUrls = $_POST["repo_clones"];
$repoCloneBranch = $_POST["repo_clone_branch"];
$repoClonesPaths = $_POST["repo_clones_paths"];
$repopickTopics = $_POST["repopick_topics"];
$repopickChangeNums = $_POST["repopick_changes"];

foreach($repoPaths as $value) {
    $value = trim($value);
    if($value != null) {
        if(!preg_match('/^([A-z0-9-_.]+\/)*([A-z0-9-_.]*)$/', $value)) {
            $repoPathsContent[] = $value;
        }
    }
}
$invalidFields['repo_paths'] = $repoPathsContent;

foreach($repoCloneUrls as $value) {
    $value = trim($value);
    if($value != null) {
        if(!preg_match('/(http[s]?:\/\/)?[^\s(["<,>]*\.[^\s[",><]*(\/)?$/', $value)) {
            $repoCloneUrlsContent[] = $value;
        }
    }
}
$invalidFields['repo_clones'] = $repoCloneUrlsContent;

foreach($repoCloneBranch as $value) {
    $value = trim($value);
    if($value != null) {
        if(!preg_match('/^([A-z0-9-_+.\/]*)[A-z0-9]*$/', $value)) {
            $repoCloneBranchContent[] = $value;
        }
    }
}
$invalidFields['repo_clone_branch'] = $repoCloneBranchContent;

foreach($repoClonesPaths as $value) {
    $value = trim($value);
    if($value != null) {
        if(!preg_match('/^([A-z0-9-_.]+\/)*([A-z0-9-_.]*)$/', $value)) {
            $repoClonesPathsContent[] = $value;
        }
    }
}
$invalidFields['repo_clones_paths'] = $repoClonesPathsContent;

foreach($repopickTopics as $value) {
    $value = trim($value);
    if($value != null) {
        if(!preg_match('/^([A-z0-9- _:\/@+.]*)[A-z0-9.]*$/', $value)) {
            $repopickTopicsContent[] = $value;
        }
    }
}
$invalidFields['repopick_topics'] = $repopickTopicsContent;

foreach($repopickChangeNums as $value) {
    $value = trim($value);
    if($value != null) {
        if(!preg_match('/^([0-9]*)?[0-9 ]*$/', $value)) {
            $repopickChangeNumsContent[] = $value;
        }
    }
}
$invalidFields['repopick_changes'] = $repopickChangeNumsContent;
echo json_encode($invalidFields, JSON_UNESCAPED_SLASHES);
?>
