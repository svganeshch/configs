<?php
    $path = $_SERVER['DOCUMENT_ROOT'];
    include($path.'/utils/session.php');
    $_SESSION['got_version'] = $_POST['version'];
?>