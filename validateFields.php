<?php
include('session.php');

$badPaths = array();
$repoPaths = $_POST["repo_paths"];
//exit(print_r(count($_POST["repo_paths"])));

foreach($repoPaths as $value) {
    if(!preg_match('/^([A-z0-9-_+]+\/)*([A-z0-9]+)$/', $value)) {
        $content[] = $value;
    }
}

$badPaths = array('repo_paths' => $content);
echo json_encode($badPaths, JSON_UNESCAPED_SLASHES);
?>