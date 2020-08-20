<?php
error_reporting(E_ALL & ~E_NOTICE);

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/utils/session.php');
require_once($path . '/helpers/devices_connect_moi.php');

$cur_user = $_SESSION['login_user'];

if (!empty($_POST['profile_username']) and !empty($_POST['current_profile_password']) and !empty($_POST['profile_password']) and !empty($_POST['re_profile_password'])) {
    $set_username = stripslashes($_POST["profile_username"]);
    $set_cur_password = stripslashes($_POST["current_profile_password"]);
    $set_password = stripslashes($_POST["profile_password"]);
    $set_re_password = stripslashes($_POST["re_profile_password"]);

    // check if the user exists with the current username
    $username_check = "SELECT * from `login` WHERE `username`='$cur_user'";
    $username_check_res = mysqli_query($login_db, $username_check) or die(mysqli_error($login_db));
    $cur_username = mysqli_fetch_assoc($username_check_res);
    $cur_username = $cur_username['username'];
    if (mysqli_num_rows($username_check_res) == 1) {
        // check if a user already exists with the new choosen username
        $exist_user_check = "SELECT * from `login` WHERE `username`='$set_username'";
        $exist_user_check_res = mysqli_query($login_db, $exist_user_check) or die(mysqli_error($login_db));
        if (mysqli_num_rows($exist_user_check_res) == 0 | $cur_username == $set_username) {
            // current password check
            $password_hash_query = "SELECT `password` FROM `login` WHERE `username`='$cur_user'";
		    $password_hash_res = mysqli_query($login_db, $password_hash_query) or die(mysqli_error($login_db));
		    $pass_hash = mysqli_fetch_assoc($password_hash_res);
            $pass_hash = $pass_hash['password'];
            if (password_verify($set_cur_password, $pass_hash)) {
                // new and re pass match check
                if ($set_password == $set_re_password) {
                    // hash the new password
                    $hashed_pass = password_hash($set_password, PASSWORD_DEFAULT);
                    // update with the new data
                    $update_query = "UPDATE `login` SET `username`='$set_username', `password`='$hashed_pass' WHERE `username`='$cur_user'";
                    $update_device_maintainer_query = "UPDATE `device_maintainers` SET `username`='$set_username' WHERE `username`='$cur_user'";

                    $update_query_res = mysqli_query($login_db, $update_query) or die(mysqli_error($login_db));
                    $update_device_maintainer_query_res = mysqli_query($devices_db, $update_device_maintainer_query) or die(mysqli_error($devices_db));

                    if (!empty($update_query_res) && !empty($update_device_maintainer_query_res)) {
                        $_SESSION['login_user'] = $set_username;
                        echo "Successfully updated profile!";
                    }
                } else {
                    echo "New password and Retyped password mismatch!";
                }
            } else {
                echo "Current password is wrong";
            }
        } else {
            echo "A user already exists with the username!";
        }
    } else {
        echo "Something went wrong!";
    }
} else {
    echo "These fields can't be empty!";
}

?>