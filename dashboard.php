<?php
    include('session.php');
    require('devices_connect_moi.php');

    if (isset($_SESSION['cur_device'])) {
        unset($_SESSION['cur_device']);
    }
    
    unset($_SESSION['jenkins_build_id']);
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
                        <div class="alert alert-warning">
                            <strong>Versions</strong>
                            <br>
                            <strong>Showing: <?php echo $_SESSION['got_version'] ?></strong>
                                <div class="btn-toolbar">
                                    <button type="button" id="btnArrowPie" class="btn btn-primary btn-sm" value="arrow-9.x">Arrow P</button>
                                    <button type="button" id="btnArrowQ" class="btn btn-primary btn-sm" value="arrow-10.0">Arrow Q</button>
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
                                                        buildvariant varchar(10) NULL,
                                                        default_buildtype_state varchar(10) NULL,
                                                        bootimage varchar(10) NULL,
                                                        global_override varchar(10) NULL,
                                                        changelog LONGTEXT NULL)
                                                       AS SELECT
                                                        'no' AS force_clean,
                                                        'no' AS test_build,
                                                        'yes' AS is_official,
                                                        'userdebug' AS buildtype,
                                                        'vanilla' AS buildvariant,
                                                        'yes' AS default_buildtype_state,
                                                        'no' AS bootimage,
                                                        'no' AS global_override";
                                mysqli_query($devices_db, $create_table_query) or die(mysqli_error($devices_db));

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
                $devices_list = explode(PHP_EOL, file_get_contents($devices_list_url));
                ?>
                <div class="row text-center pad-top">
                <?php
                foreach($devices_list as $device) {
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
    <script src="assets/js/dashboard.js"></script> 
   
</body>
</html>
