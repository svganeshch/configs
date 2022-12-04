<?php
    $path = "/var/www/html";
    include($path.'/utils/session.php');
    $_SESSION['got_version'] = $_POST['version'];
?>