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
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
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

        <?php if ($_SESSION["cur_device"] != "common_config") { ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-5 col-xs-12">
                    <div class="alert alert-info">
                    Last successful build for
                    <strong>
                    <?php 
                        $jsonresp = geturlresp($cur_device_url.'lastSuccessfulBuild/api/json');
                        $obj = json_decode($jsonresp);
                        $build_id = $obj->{'displayName'};
                        $build_date = $obj->{'timestamp'}/1000;
                    ?>
                    <?php echo ucfirst($_SESSION["cur_device"]); echo " "; echo $build_id; ?> on <?php echo date('d/m/Y h:i', "$build_date"); ?>
                    </strong>
                    </div>                  
                </div>
            </div>
        </div>
        <?php } ?>

        <!-- Text Fields -->
        <form name="add_name" id="add_name">
            <div class="container-fluid" >
                <?php if ($_SESSION["cur_device"] == "common_config") { ?>           
                    <div class="form-group">
                        <div class="checkbox">
                            <label>Global override</label> 
                            <input type="checkbox" name="global_override" id="global_override" />
                        </div>
                    </div>
                <input type="hidden" name="hidden_global_override" id="hidden_global_override" value="no" />
                <?php } ?>

                <div class="switch-block" id="switch-block">
                    <div class="row">
                        <div class="col-md-5 col-xs-12" id="total-menu-block">
                            <li class="list-group-item" id="total-menu-list">


                                <div class="row" title="reset to defaults!">
                                    <div class="col-md-12 col-xs-12">
                                        <button type="button" id="reset-hard" name="reset-hard" class="btn btn-success">
                                            <i class="fa fa-refresh" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 col-xs-4">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <label>Is Official?</label> 
                                                <input type="checkbox" name="is_official" id="is_official" checked />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-xs-4">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <label>Test Build</label>
                                                <input type="checkbox" name="test_build" id="test_build" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-xs-4">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <label>Force clean</label>
                                                <input type="checkbox" name="force_clean" id="force_clean" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-xs-4">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <label>Buildtype</label>
                                                <input type="checkbox" name="buildtype" id="buildtype" checked />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-xs-4" title="builds only bootimage!!">
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <label>Bootimage!</label>
                                                <input type="checkbox" name="bootimage" id="bootimage" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="hidden_is_official" id="hidden_is_official" value="yes" />
                <input type="hidden" name="hidden_test_build" id="hidden_test_build" value="no" />
                <input type="hidden" name="hidden_force_clean" id="hidden_force_clean" value="no" />
                <input type="hidden" name="hidden_buildtype" id="hidden_buildtype" value="user" />
                <input type="hidden" name="hidden_bootimage" id="hidden_bootimage" value="no" />
                <br/>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-5 col-xs-12" id="total-menu-block">
                        <li class="list-group-item" id="total-menu-list">

                            <?php if ($_SESSION["cur_device"] != "common_config") { ?>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>Override lunch?</label>
                                    <input type="checkbox" name="override_lunch" id="override_lunch" />
                                </div>
                            </div>
                            <input type="hidden" name="hidden_override_lunch" id="hidden_override_lunch" value="no" />
                            <?php } ?>

                            <div class="config_dev_div" id="config_dev_div">
                                <strong><label name="config_dev_name" id="config_dev_name" ><?php echo ucfirst($_SESSION["cur_device"]); ?> config:</label></strong>
                                <br/>
                            </div>

                            <div class="main_config_menu" id="main_config_menu">
                                <ul class="list-group">
                                    <li class="list-group-item" id="dynamic_field-1-">
                                        <label>Path of repos to delete!</label>
                                        <button type="button" name="add1" id="add1" class="btn btn-success">+</button>
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
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
                                        <button type="button" name="add2" id="add2" class="btn btn-success">+</button>
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
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
                                        <button type="button" name="add3" id="add3" class="btn btn-success">+</button>
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
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
                                        <button type="button" name="add4" id="add4" class="btn btn-success">+</button>
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <div class="form-group">
                                                    <input type="text" name="repopick_changes[]" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>

                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <div class="form-group">
                                                    <label>Changelog:</label>
                                                    <textarea class="form-control" name="changelog" id="changelog" rows="3"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <input type="button" name="submit" id="submit" class="btn btn-info" value="Push changes" />
                        </li>
                    </div>
                </div>
            </div>
        </form>

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
    <script src="assets/js/collect_send_data.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
   
</body>
</html>
