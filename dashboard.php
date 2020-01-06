<?php
    include('session.php');
    include('jenkins_config.php');

    if (isset($_SESSION['cur_device'])) {
        unset($_SESSION['cur_device']);
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ArrowOS Jenkins</title>
	<!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    <!-- cookies consent -->
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.js"></script>
    <script>
    window.addEventListener("load", function(){
    window.cookieconsent.initialise({
    "palette": {
        "popup": {
        "background": "#237afc"
        },
        "button": {
        "background": "#fff",
        "text": "#237afc"
        }
    },
    "theme": "edgeless",
    "position": "top"
    })});
    </script>
</head>
<body>
     
           
          
    <div id="wrapper">
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="adjust-nav">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/">
                        <img class="arrow-logo" src="assets/img/logo.png" />

                    </a>
                    
                </div>
            </div>
        </div>

        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="active-link">
                        <a href="dashboard.php" ><i class="fa fa-home "></i>Dashboard</a>
                    </li>

                    <li>
                        <a href="profile.php" style="color: #000000"><i class="fa fa-user "></i>Profile</a>
                    </li>

                    <?php if ($_SESSION['is_admin']) { ?>
                    <li>
                        <a href="maintainers.php" style="color: #000000"><i class="fa fa-users "></i>Maintainers</a>
                    </li>
                    <?php } ?>

                    <li>
                        <a href="logout.php" style="color: #a94442"><i class="fa fa-sign-out "></i>Logout</a>
                    </li>             
                </ul>
            </div>
        </nav>

<!-- /. NAV SIDE  -->
    <div id="page-wrapper" >
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
                        <div class="alert alert-info">
                             <strong>Welcome <?php echo $_SESSION['login_user'] ?>!</strong>
                        </div>                       
                    </div>
                </div>

            <!-- /. ROW  -->
            <?php if ($_SESSION['is_admin']) { ?>
            <div class="row text-center pad-top">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                    <a class="nolink" href="device.php?select_device=<?php echo "common_config" ?>" >
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
                                                        default_buildtype_state varchar(10) NULL,
                                                        bootimage varchar(10) NULL,
                                                        global_override varchar(10) NULL,
                                                        changelog LONGTEXT NULL)
                                                       AS SELECT
                                                        'no' AS force_clean,
                                                        'no' AS test_build,
                                                        'yes' AS is_official,
                                                        'userdebug' AS buildtype,
                                                        'yes' AS default_buildtype_state,
                                                        'no' AS bootimage,
                                                        'no' AS global_override";
                                mysqli_query($db, $create_table_query) or die(mysqli_error($db));

                                /* to add a new column 
                                $alter_table_query = "ALTER TABLE common_config ADD default_buildtype_state varchar(10) NULL AFTER buildtype";
                                mysqli_query($db, $alter_table_query) or die(mysqli_error($db));*/
                            ?>
                        </div>
                    </a>
                </div>
            </div>
            <?php } ?>
            
            <?php
                // Get all the current jenkins jobs list to cross check against our official devices
                $jenkins_credential_url = 'https://'.urlencode(jenkins_username).':'.urlencode(jenkins_user_api).'@'.urlencode(jenkins_url);
                $got_jenkins_job_list = array(); 
                $jenkins_jobs_list_url = $jenkins_credential_url.'/api/json?pretty=true';
                $curl_jenkins_job_list = curl_init();
                curl_setopt($curl_jenkins_job_list, CURLOPT_URL, $jenkins_jobs_list_url);
                curl_setopt($curl_jenkins_job_list, CURLOPT_POST, 1);
                curl_setopt($curl_jenkins_job_list, CURLOPT_RETURNTRANSFER, 1);
                $jobs_list = curl_exec($curl_jenkins_job_list);
                curl_close($curl_jenkins_job_list);
                $jobs_list = json_decode($jobs_list, true);
                $jobs_count = count($jobs_list["jobs"]);

                for ($i=0; $i < $jobs_count; $i++) {
                    array_push($got_jenkins_job_list, $jobs_list["jobs"][$i]["name"]);
                }

                if (empty($got_jenkins_job_list)) { ?>
                    <hr />
                    <div class="row">
                        <div class="col-lg-12 ">
                            <div class="alert alert-info">
                                <strong>Failed to fetch Jenkins jobs list!</strong>
                            </div>                       
                        </div>
                    </div>
                <?php }

                if (!empty($got_jenkins_job_list)) {
                    // Jenkins nodes configuration
                    $jobs_created = array();
                    $jobs_failed_create = array();
                    $undefined_hal_fail = array();
                    $NO_OF_NODES = no_of_nodes;
                    $NODE_NAME_PREFIX = node_name_prefix;
                    $node_structure_url = NODE_STRUCTURE_URL;
                    $node_structure = json_decode(file_get_contents($node_structure_url), true);

                    for ($count = 1; $count <= $NO_OF_NODES; $count++) {
                        ${$NODE_NAME_PREFIX."-".$count} = array();
                        ${"node_temp".$count."_config"} = $jenkins_credential_url."/job/node".$count."_template/config.xml";
                    }

                    $devices_list_url = DEVICES_LIST_URL;
                    $devices_list = explode(PHP_EOL, file_get_contents($devices_list_url));
                    // Sort devices according to our node_structure
                    foreach($devices_list as $device) {
                        if ($device != null && $device[0] != '#') {
                            $fetch_device = explode(' ', $device, 4);
                            $device = $fetch_device[1];
                            $device_hal = $fetch_device[3];

                            for ($a=1; $a<=$NO_OF_NODES; $a++) {
                                if (in_array($device_hal, $node_structure[$NODE_NAME_PREFIX."-".$a][0]["hals"])) {
                                    array_push(${$NODE_NAME_PREFIX."-".$a}, $device);
                                }
                            }
                        }
                    }
                }
                ?>
                <div class="row text-center pad-top">
                <?php
                foreach($devices_list as $device) {
                    if ($device != null && $device[0] != '#') {
                        $fetch_device = explode(' ', $device, 4);
                        $device = $fetch_device[1];
                        $device_buildtype = $fetch_device[2];

                        if (!$_SESSION['is_admin']) {
                            $pattern = "/\b" . $device . "\b/i";
                            if(!preg_match($pattern, $_SESSION['maintainer_device']))
                                continue;
                        }
                ?>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6">
                        <a class="nolink" href="device.php?select_device=<?php echo $device ?>" >
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
                                                            bootimage varchar(10) NULL,
                                                            changelog LONGTEXT NULL,
                                                            xda_link LONGTEXT NULL,
                                                            default_buildtype TEXT(20) NULL,
                                                            lunch_override_name varchar(50) NULL,
                                                            lunch_override_state varchar(10) NULL,
                                                            ovr_repo_paths JSON NULL,
                                                            ovr_repo_clones JSON NULL,
                                                            ovr_repo_clones_paths JSON NULL,
                                                            ovr_repopick_topics JSON NULL,
                                                            ovr_repopick_changes JSON NULL,
                                                            ovr_force_clean varchar(10) NULL,
                                                            ovr_test_build varchar(10) NULL,
                                                            ovr_is_official varchar(10) NULL,
                                                            ovr_buildtype varchar(10) NULL,
                                                            ovr_bootimage varchar(10) NULL,
                                                            ovr_changelog LONGTEXT NULL,
                                                            ovr_xda_link LONGTEXT NULL)
                                                           AS SELECT
                                                             'no' AS force_clean,
                                                             'no' AS test_build,
                                                             'yes' AS is_official,
                                                             '$device_buildtype' AS buildtype,
                                                             'no' AS bootimage,
                                                             'no' AS lunch_override_state,
                                                             '$device_buildtype' AS default_buildtype,
                                                             'no' AS ovr_force_clean,
                                                             'no' AS ovr_test_build,
                                                             'yes' AS ovr_is_official,
                                                             '$device_buildtype' AS ovr_buildtype,
                                                             'no' AS ovr_bootimage";
                                    mysqli_query($db, $create_table_query) or die(mysqli_error($db));

                                    /* update the default buildtype eachtime on login or dashboard */
                                    $update_default_buildtype = "UPDATE `$device` SET `default_buildtype`='$device_buildtype'";
                                    mysqli_query($db, $update_default_buildtype) or die(mysqli_error($db));

                                    if (!empty($got_jenkins_job_list)) {
                                        if (!in_array($device, $got_jenkins_job_list)) {
                                            createJenkinsJob($device);
                                        }
                                    }

                                    /* to add a new column 
                                    $alter_table_query = "ALTER TABLE $device ADD default_buildtype TEXT(20) NULL AFTER xda_link";
                                    mysqli_query($db, $alter_table_query) or die(mysqli_error($db));*/
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

            <?php
                /* TODO : Move this code into jenkinsFunc.php */
                /* Check if the jenkins job exists, if doesn't create one and assign it to the
                    appropriate node as defined by our node structure */
                function createJenkinsJob($device) {
                    // Global vars
                    global $jenkins_credential_url;
                    global $NO_OF_NODES;
                    global $NODE_NAME_PREFIX;
                    global $undefined_hal_fail;
                    global $jobs_created;
                    global $jobs_failed_create;
                    for ($x=0; $x <= $NO_OF_NODES; $x++) {
                        global ${$NODE_NAME_PREFIX."-".$x};
                        global ${"node_temp".$x."_config"};
                    }

                    $jenkins_job_url = curl_init($jenkins_credential_url."/job/".$device."/");
                    curl_setopt($jenkins_job_url,  CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($jenkins_job_url, CURLOPT_HEADER, TRUE);
                    curl_setopt($jenkins_job_url, CURLOPT_NOBODY, TRUE);
                    curl_setopt($jenkins_job_url, CURLOPT_TIMEOUT, 10);
                    curl_exec($jenkins_job_url);
                    $httpCode = curl_getinfo($jenkins_job_url, CURLINFO_HTTP_CODE);
                    curl_close($jenkins_job_url);

                    if ($httpCode == 404) {
                        $node_temp_config = null;

                        for ($b=0; $b <= $NO_OF_NODES; $b++) {
                            if (in_array($device, ${$NODE_NAME_PREFIX."-".$b})) {
                                $node_temp_config = file_get_contents(${"node_temp".$b."_config"});
                                $which_node = $NODE_NAME_PREFIX."-".$b;
                            }
                        }

                        if (empty($node_temp_config)) {
                            array_push($undefined_hal_fail, $device);
                            return;
                        }

                        $create_job_url = $jenkins_credential_url.'/createItem?name='.$device;
                        $create_job_data = curl_init();
                        curl_setopt($create_job_data, CURLOPT_URL, $create_job_url);
                        curl_setopt($create_job_data, CURLOPT_HEADER, TRUE);
                        curl_setopt($create_job_data, CURLOPT_CUSTOMREQUEST, 'POST');
                        curl_setopt($create_job_data, CURLOPT_POST, 1);
                        curl_setopt($create_job_data,  CURLOPT_RETURNTRANSFER, TRUE);
                        curl_setopt($create_job_data, CURLOPT_TIMEOUT, 10);
                        curl_setopt($create_job_data, CURLOPT_POSTFIELDS, $node_temp_config);
                        curl_setopt($create_job_data, CURLOPT_HTTPHEADER, array(
                            'Content-type: application/xml', 
                            'Content-length: ' . strlen($node_temp_config)
                        ));
                        $response = curl_exec($create_job_data);
                        $httpCode = curl_getinfo($create_job_data, CURLINFO_HTTP_CODE);
                        curl_close($create_job_data);

                        if ( $httpCode != 404 )
                            array_push($jobs_created, $device." (".$which_node.")");
                        else
                            array_push($jobs_failed_create, $device." (".$which_node.")");
                    }
                }
            ?>

            <?php
            if (!empty($undefined_hal_fail)) { ?>
            <hr />
            <div class="row">
                <div class="col-lg-12 ">
                    <div class="alert alert-danger">
                        <strong>
                        <?php
                            echo nl2br("Cannot assign node for these devices. New/Unknown HAL defined\n");
                            foreach($undefined_hal_fail as $hal_failed) {
                                echo nl2br("- ".$hal_failed."\n");
                            }
                        ?>
                        </strong>
                    </div>                       
                </div>
            </div>
            <?php } ?>

            <?php
            if (!empty($jobs_created)) { ?>
                <hr />
                <div class="row">
                    <div class="col-lg-12 ">
                        <div class="alert alert-info">
                            <strong>
                            <?php
                                echo nl2br("Jenkins job successfully created for new devices!\n");
                                foreach($jobs_created as $created) {
                                    echo nl2br("- ".$created."\n");
                                }
                            ?>
                            </strong>
                        </div>                       
                    </div>
                </div>
            <?php } ?>

            <?php
            if (!empty($jobs_failed_create)) { ?>
            <hr />
            <div class="row">
                <div class="col-lg-12 ">
                    <div class="alert alert-danger">
                        <strong>
                        <?php
                            echo nl2br("Jenkins job failed to create for new devices!\n");
                            foreach($jobs_failed_create as $failed) {
                                echo nl2br("- ".$failed."\n");
                            }
                        ?>
                        </strong>
                    </div>                       
                </div>
            </div>
            <?php } ?>
                 
            <!-- /. ROW  -->   
				<!--<div class="row">
                	<div class="col-lg-12 ">
					<br/>
                        <div class="alert alert-danger">
                             <strong>Want More Icons Free ? </strong> Checkout fontawesome website and use any icon <a target="_blank" href="http://fortawesome.github.io/Font-Awesome/icons/">Click Here</a>.
                        </div>                       
                    </div>
                </div>-->
                  <!-- /. ROW  --> 
    	</div>
             <!-- /. PAGE INNER  -->
    </div>
         <!-- /. PAGE WRAPPER  -->
</div>

    <div class="footer">
        <div class="row">
            <div class="col-lg-12" >
                &copy;  2019 ArrowOS | <a href="https://arrowos.net" style="color:#fff;" target="_blank">www.arrowos.net</a>
            </div>
        </div>
    </div>
          

     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>    
   
</body>
</html>
