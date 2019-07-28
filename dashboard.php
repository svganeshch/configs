<?php
    include('session.php');

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
                                                        'user' AS buildtype,
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
            	$url = 'https://raw.githubusercontent.com/ArrowOS/android_vendor_arrow/arrow-9.x/arrow.devices';
            	
                $devices_list = nl2br( file_get_contents("$url") );
                ?>
                <div class="row text-center pad-top">
                <?php
                foreach(preg_split("/((\r?\n)|(\r\n?))/", $devices_list) as $device){
                    if ($device != null) {
                        $fetch_device = explode(' ', trim($device));
                        $device = $fetch_device[0];
                        $device_buildtype = explode('<', $fetch_device[1], 2);

                        if (!$_SESSION['is_admin']) {
                            $device = $_SESSION['maintainer_device'];
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
                                                             'user' AS buildtype,
                                                             'no' AS bootimage,
                                                             'no' AS lunch_override_state,
                                                             '$device_buildtype[0]' AS default_buildtype,
                                                             'no' AS ovr_force_clean,
                                                             'no' AS ovr_test_build,
                                                             'yes' AS ovr_is_official,
                                                             'user' AS ovr_buildtype,
                                                             'no' AS ovr_bootimage";
                                    mysqli_query($db, $create_table_query) or die(mysqli_error($db));

                                    /* update the default buildtype eachtime on login or dashboard */
                                    $update_default_buildtype = "UPDATE `$device` SET `default_buildtype`='$device_buildtype[0]'";
                                    mysqli_query($db, $update_default_buildtype) or die(mysqli_error($db));

                                    /* to add a new column 
                                    $alter_table_query = "ALTER TABLE $device ADD default_buildtype TEXT(20) NULL AFTER xda_link";
                                    mysqli_query($db, $alter_table_query) or die(mysqli_error($db));*/
                                ?>
                            </div>
                        </a>                                         
                    </div>           
                <?php 
                    }
                    if (!$_SESSION['is_admin']) {
                        break;
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
   
</body>
</html>
