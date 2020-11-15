<?php
error_reporting(E_ALL & ~E_NOTICE);

$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/utils/session.php');
require($path . '/helpers/devices_connect_moi.php');
require_once($path . '/config/dbcon_config.php');

if (isset($_SESSION['cur_device'])) {
    unset($_SESSION['cur_device']);
}

unset($_SESSION['jenkins_build_id']);
?>

<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-lg-12">
                <h2>Official Devices</h2>
            </div>
        </div>

        <!-- /. ROW  -->
        <hr />
        <div class="row">
            <div class="col-lg-12 ">
                <div class="alert alert-warning">
                    <strong>Versions</strong>
                    <br>
                    <strong>Showing: <?php echo $_SESSION['got_version'] ?></strong>
                    <div class="btn-toolbar">
                        <?php foreach ($VERSIONS as $version => $value) {
                            if (!strpos($version, 'community')) { ?>
                                <button type="button" id="versionbutton" class="btn btn-primary btn-sm" value="<?php echo $version ?>"><?php echo $version ?></button>
                        <?php }
                        } ?>
                        <div class="dropdown">
                            <button class="btn btn-warning btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                community
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <?php foreach ($VERSIONS as $version => $value) {
                                    if (strpos($version, 'community')) { ?>
                                        <li>
                                            <button type="button" id="versionbutton" class="btn btn-warning btn-sm" value="<?php echo $version ?>"><?php echo explode('_', $version)[0] ?></button>
                                        </li>
                                <?php }
                                } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 ">
                <div class="alert alert-info">
                    <strong>Welcome <?php echo $_SESSION['login_user'] ?>!</strong>
                </div>
            </div>
        </div>

        <!-- /. ROW  -->
        <?php if ($_SESSION['is_admin']) { ?>
            <div class="row text-center pad-top">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                    <a class="nolink" href="device.php?select_device=<?php echo "common_config" ?>">
                        <div class="div-square">
                            <i class="fa fa-mobile fa-5x"></i>
                            <h4>Common Config</h4>
                            <?php
                            $create_table_query = "CREATE TABLE IF NOT EXISTS common_config (
                                                        id int(10) AUTO_INCREMENT PRIMARY KEY,
                                                        repo_paths JSON NULL,
                                                        repo_clones JSON NULL,
                                                        repo_clones_paths JSON NULL,
                                                        repopick_topics JSON NULL,
                                                        repopick_changes JSON NULL,
                                                        force_clean varchar(10) NULL,
                                                        test_build varchar(10) NULL,
                                                        is_official varchar(10) NULL,
                                                        buildtype varchar(10) NULL,
                                                        buildvariant varchar(10) NULL,
                                                        default_buildtype_state varchar(10) NULL,
                                                        bootimage varchar(10) NULL,
                                                        global_override varchar(10) NULL,
                                                        changelog LONGTEXT NULL,
                                                        force_node varchar(10) NULL)
                                                       AS SELECT
                                                        'no' AS force_clean,
                                                        'no' AS test_build,
                                                        'yes' AS is_official,
                                                        'userdebug' AS buildtype,
                                                        'vanilla' AS buildvariant,
                                                        'yes' AS default_buildtype_state,
                                                        'no' AS bootimage,
                                                        'no' AS global_override,
                                                        'default' AS force_node";
                            mysqli_query($devices_db, $create_table_query) or die(mysqli_error($devices_db));

                            // create common_config table also in test profile
                            mysqli_query($devices_test_profile_db, $create_table_query) or die(mysqli_error($devices_test_profile_db));

                            /* to add a new column 
                                $alter_table_query = "ALTER TABLE common_config ADD default_buildtype_state varchar(10) NULL AFTER buildtype";
                                mysqli_query($devices_db, $alter_table_query) or die(mysqli_error($devices_db));*/
                            ?>
                        </div>
                    </a>
                </div>
            </div>
        <?php } ?>

        <?php
        $devices_list = explode("\n", trim(file_get_contents($devices_list_url)));
        ?>
        <div class="row text-center pad-top">
            <?php
            foreach ($devices_list as $device) {
                if ($device != null && $device[0] != '#') {
                    $fetch_device = explode(' ', $device, 4);
                    $device = $fetch_device[1];
                    $device_buildtype = $fetch_device[2];

                    if (!$_SESSION['is_admin']) {
                        /*if(strpos($_SESSION['maintainer_device'], $device) !== false) {
                            } else {
                                continue;
                            }*/
                        $pattern = "/\b" . $device . "\b/i";
                        if (!preg_match($pattern, $_SESSION['maintainer_device']))
                            continue;
                    }
            ?>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                        <a class="nolink" href="device.php?select_device=<?php echo $device ?>">
                            <div class="div-square">
                                <i class="fa fa-mobile fa-5x"></i>
                                <h4><?php echo $device ?></h4>
                                <?php
                                $create_table_query = "CREATE TABLE IF NOT EXISTS $device (
                                                            id int(10) AUTO_INCREMENT PRIMARY KEY,
                                                            repo_paths JSON NULL,
                                                            repo_clones JSON NULL,
                                                            repo_clones_paths JSON NULL,
                                                            repopick_topics JSON NULL,
                                                            repopick_changes JSON NULL,
                                                            force_clean varchar(10) NULL,
                                                            test_build varchar(10) NULL,
                                                            is_official varchar(10) NULL,
                                                            buildtype varchar(10) NULL,
                                                            buildvariant varchar(10) NULL,
                                                            bootimage varchar(10) NULL,
                                                            changelog LONGTEXT NULL,
                                                            weeklies_opt varchar(10) NULL,
                                                            opts int(10) NULL,
                                                            xda_link LONGTEXT NULL,
                                                            default_buildtype TEXT(20) NULL)
                                                           AS SELECT
                                                             'no' AS force_clean,
                                                             'no' AS test_build,
                                                             'yes' AS is_official,
                                                             '$device_buildtype' AS buildtype,
                                                             'vanilla' AS buildvariant,
                                                             'no' AS bootimage,
                                                             'yes' AS weeklies_opt,
                                                             '$device_buildtype' AS default_buildtype,
                                                             '0' AS opts";
                                mysqli_query($devices_db, $create_table_query) or die(mysqli_error($devices_db));

                                // create device table also in test profile
                                mysqli_query($devices_test_profile_db, $create_table_query) or die(mysqli_error($devices_test_profile_db));

                                /* update the default buildtype eachtime on login or dashboard */
                                $update_default_buildtype = "UPDATE `$device` SET `default_buildtype`='$device_buildtype'";
                                mysqli_query($devices_db, $update_default_buildtype) or die(mysqli_error($devices_db));

                                /* to add a new column 
                                    $alter_table_query = "ALTER TABLE $device ADD `opts` int(10) DEFAULT '0' AFTER `xda_link`";
                                    mysqli_query($devices_db, $alter_table_query) or die(mysqli_error($devices_db));*/
                                ?>
                            </div>
                        </a>
                    </div>
            <?php
                }
            }
            ?>
        </div>
        <?php
        ?>
    </div>
    <!-- /. PAGE INNER  -->
</div>
<!-- /. PAGE WRAPPER  -->
