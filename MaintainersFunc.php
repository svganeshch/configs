<?php
include('session.php');
error_reporting(E_ALL & ~E_NOTICE);

/* Nuke Maintainer */
if(isset($_POST['nuke_maintainer']) && $_POST['nuke_maintainer'] == 'yes') {
    global $db;
    $username = $_POST['user'];

    $nuke_maintainer_query = "DELETE FROM `login` WHERE `username`='$username'";
    if(mysqli_query($db, $nuke_maintainer_query))
        exit('Nuked maintainer '.$username.' successfully!');
    else
        exit('Something went wrong, Failed to nuke this maintainer! '.mysqli_error($db));
}

/* Maintainer access revoke */
if(isset($_POST['revoke_maintainer']) && $_POST['revoke_maintainer'] == 'yes') {
    global $db;
    $username = $_POST['user'];

    // check if user is already revoked
    $check_maintainer_revoke_query = "SELECT `status` FROM `login` WHERE `username`='$username'";
    $check_maintainer_revoke_query_res = mysqli_query($db, $check_maintainer_revoke_query) or die("Failed to fetch maintainer status". mysqli_error($db));
    $maintainer_status = mysqli_fetch_array($check_maintainer_revoke_query_res,MYSQLI_ASSOC);
    $maintainer_status = $maintainer_status['status'];

    if ($maintainer_status == 'revoked') {
        $revoke_maintainer_query = "UPDATE `login` SET `status`='active' WHERE `username`='$username'";
        if(mysqli_query($db, $revoke_maintainer_query))
            exit('Revoked maintainer access '.$username.' back to active successfully!');
        else
            exit('Something went wrong, Failed to revoke this maintainer! '.mysqli_error($db));
    }
    else {
        $revoke_maintainer_query = "UPDATE `login` SET `status`='revoked' WHERE `username`='$username'";
        if(mysqli_query($db, $revoke_maintainer_query))
            exit('Revoked maintainer access '.$username.' successfully!');
        else
            exit('Something went wrong, Failed to revoke this maintainer! '.mysqli_error($db));
    }
}

/* Reset password to default */
if(isset($_POST['reset_pass']) && $_POST['reset_pass'] == 'yes') {
    global $db;
    $username = $_POST['user'];
    $default_pass_hash = "$2y$10$7eq56qYax0VLu/EcVJVwRO6V92fugE3zzddyjzAUI69q20uvmasNi"; //maintainer@arrowos

    $reset_maintainer_pass_query = "UPDATE `login` SET `password`='$default_pass_hash' WHERE `username`='$username'";
    if(mysqli_query($db, $reset_maintainer_pass_query))
        exit('Password has been reset to default for '.$username.' successfully!');
    else
        exit('Something went wrong, Failed to reset pass for this maintainer! '.mysqli_error($db));
}

/* Add new maintainer */
if(isset($_POST['add_new_maintainer']) && $_POST['add_new_maintainer'] == 'yes') {
    global $db;

    if(isset($_POST['new_maintainer_username']) && $_POST['new_maintainer_username'] != "")
        $maintainer_username = $_POST['new_maintainer_username'];
    else
        exit('No username specified for new maintainer!');

    if(isset($_POST['new_maintainer_devices']) && $_POST['new_maintainer_devices'] != "")
        $maintainer_devices = $_POST['new_maintainer_devices'];
    else
        exit('No devices specified for new maintainer!');

    $add_new_maintainer_query = "INSERT into `login` (`username`, `maintainer_device`) VALUES ('$maintainer_username', '$maintainer_devices')";
    if(mysqli_query($db, $add_new_maintainer_query))
        exit('New maintainer '.$username.' for devices '.$maintainer_devices.' has been added successfully!');
    else
        exit('Something went wrong, Failed to add new maintainer! '.mysqli_error($db));
}

?>