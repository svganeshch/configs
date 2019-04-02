<?php
include('session.php');
session_start();

function geturlresp($jenurl) {
  $url = $jenurl;
  $output = file_get_contents("$url");
  return $output;
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php
    if (isset($_GET['select_device'])) {
      $_SESSION["cur_device"]=$_GET['select_device'];
      $cur_device_url="https://jenkins.arrowos.net/job/".$_SESSION["cur_device"]."/";
      ?>
      <title><?php echo ucfirst($_SESSION["cur_device"]) ?></title>
      <?php
    }
    ?>
	<!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link href="assets/css/switch.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
     
           
          
<div id="wrapper">
  <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="adjust-nav">
          <div class="navbar-header">
            <a class="navbar-brand" href="/">
              <img class="arrow-logo" src="assets/img/logo.png" />
            </a>
          </div>
              
              <span class="logout-spn" >
                <a href="logout.php" style="color:#fff;">LOGOUT</a>  
              </span>
      </div>
  </div>

<!-- /. NAV SIDE  -->
<div id="page-wrapper-device" >
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2><?php echo ucfirst($_SESSION["cur_device"]) ?></h2>   
            </div>
        </div>              
        <!-- /. ROW  -->
        <hr />

        <div class="row">
            <div class="col-lg-12 ">
                <div class="alert alert-info">
                Last successful build for
                <strong>
                <?php 
                    $jsonresp = geturlresp($cur_device_url.'lastSuccessfulBuild/api/json');
                    $obj = json_decode($jsonresp);
                    $build_id = $obj->{'displayName'};
                    $build_date = $obj->{'timestamp'}/1000;
                ?>
                <?php echo ucfirst($_SESSION["cur_device"]); echo " "; echo $build_id; ?> on <?php echo date('d/m/Y H:i:s', "$build_date"); ?>
                </strong>
                </div>                  
            </div>
        </div>

        <!-- /. ROW  -->
        <div class="onoffswitch">
            <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" checked>
                <label class="onoffswitch-label" for="myonoffswitch">
                    <span class="onoffswitch-inner"></span>
                    <span class="onoffswitch-switch"></span>
                </label>
        </div>

        <!-- Text Fields -->
        <div class="form-group">  
            <form name="add_name" id="add_name">
                    <ul class="list-group">
                        <li class="list-group-item" id="dynamic_field-1-">
                            <label>Path of repos to delete!</label>
                            <button type="button" name="add1" id="add1" class="btn btn-success">Add More</button>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-2">
                                    <div class="form-group">
                                        <input type="text" name="repo_paths[]" class="form-control" />
                                    </div>
                                </div>                                
                            </div>
                        </li>
                    </ul>

                    <ul class="list-group">
                        <li class="list-group-item" id="dynamic_field-2-">
                            <label>Url's of repos to clone/sync!</label>
                            <button type="button" name="add2" id="add2" class="btn btn-success">Add More</button>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-2">
                                    <div class="form-group">
                                        <input type="text" name="repo_clones[]" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>

                    <ul class="list-group">
                        <li class="list-group-item" id="dynamic_field-3-">
                            <label>Repopick topics!</label>
                            <button type="button" name="add3" id="add3" class="btn btn-success">Add More</button>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-2">
                                    <div class="form-group">
                                        <input type="text" name="repopick_topics[]" class="form-control" />
                                    </div>
                                </div>                                
                            </div>
                        </li>
                    </ul>

                    <ul class="list-group">
                        <li class="list-group-item" id="dynamic_field-4-">
                            <label>Repopick change numbers!</label>
                            <button type="button" name="add4" id="add4" class="btn btn-success">Add More</button>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-2">
                                    <div class="form-group">
                                        <input type="text" name="repopick_changes[]" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>

                <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />
            </form>  
        </div>

             <!-- /. PAGE INNER  -->
    </div>
         <!-- /. PAGE WRAPPER  -->
</div>

<div class="footer">
  <div class="row">
    <div class="col-lg-12" >
      &copy;  2014 yourdomain.com | Design by: <a href="http://binarytheme.com" style="color:#fff;"  target="_blank">www.binarytheme.com</a>
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
    <script src="assets/js/textbox.js"></script>
   
</body>
</html>
