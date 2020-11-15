<?php
    $path = $_SERVER['DOCUMENT_ROOT'];
    include($path.'/utils/session.php');
    $_SESSION['device_profile'] = $_POST['device_profile'];
?>