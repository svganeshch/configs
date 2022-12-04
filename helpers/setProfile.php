<?php
    $path = "/var/www/html";
    include($path.'/utils/session.php');
    $_SESSION['device_profile'] = $_POST['device_profile'];
?>