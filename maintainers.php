<?php
    include('session.php');

    if (!$_SESSION['is_admin']) {
        header("Location: device404.php");
        exit();
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
                    <h2>Maintainers Management</h2>
                </div>
            </div>

            <hr />
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-info">
                        <strong><label id="maintainer_info_msg"></label></strong>
                    </div>                       
                </div>
            </div>

            <form id="MaintainersData">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <h4><strong>Add New Maintainers:</strong></h4>
                            <div class="col-md-6">
                                <label id="profile_label">Maintainer Username :</label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" id="new_maintainer_username" name="new_maintainer_username" class="form-control" required />
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-12">
                            <div class="col-md-6">
                                <label id="profile_label">Maintainer Devices :</label>
                            </div>
                            <div class="col-md-6">
                                <input placeholder="separate by spaces if more than one device" type="text" id="new_maintainer_devices" name="new_maintainer_devices" class="form-control" required />
                            </div>
                        </div>
                    </div>
                    <br/>
                    <input type="button" name="add_maintainer" id="add_maintainer" class="btn btn-info btn-xs pull-right" value="Add Maintainer" />
                </div>

                <br/>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <h4><strong>Current Maintainers:</strong></h4>
                        <div class="table-responsive">
                            <table class="table table-responsive table-hover">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Device</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="maintainersTable">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
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
    <script src="assets/js/maintainer_data.js"></script>
   
</body>
</html>
