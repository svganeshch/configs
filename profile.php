<?php
    include('session.php');
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
                     <h2>Profile Settings</h2>   
                </div>
            </div>

            <hr />
            <div class="row">
                <div class="col-lg-12 ">
                    <div class="alert alert-info">
                        <strong><label id="profile_update_msg">Profile settings of current user <?php echo $_SESSION['login_user'] ?></label></strong>
                    </div>                       
                </div>
            </div>

            <input type="hidden" id="username" value="<?php echo $_SESSION['login_user'] ?>"/>
                <form name="profileData" id="profileData">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-5 col-xs-12">
                                <label id="profile_label">Username :</label>
                                <input type="text" id="profile_username" name="profile_username" class="form-control" required />

                                <label id="profile_label">Current password :</label>
                                <input type="password" id="current_profile_password" name="current_profile_password" class="form-control" required />

                                <label id="profile_label">New password :</label>
                                <input type="password" id="profile_password" name="profile_password" class="form-control" required />

                                <label id="profile_label">Retype new password :</label>
                                <input type="password" id="re_profile_password" name="re_profile_password" class="form-control" required />
                            </div>
                        </div>
                        <input type="button" name="update" id="update" class="btn btn-info" value="update profile" />
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
    <script src="assets/js/profile_data.js"></script>
   
</body>
</html>
