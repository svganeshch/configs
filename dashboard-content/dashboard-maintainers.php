<?php
error_reporting(E_ALL & ~E_NOTICE);

$path = "/var/www/html";
require_once($path . '/utils/session.php');

if (!$_SESSION['is_admin']) {
    header("Location: ../html-static/device404.php");
    exit();
}
?>

<div id="page-wrapper">
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
                <br />
                <input type="button" name="add_maintainer" id="add_maintainer" class="btn btn-info btn-xs pull-right" value="Add Maintainer" />
            </div>

            <br />
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
                                    <th>Weekly Opts</th>
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
    <script src="assets/js/maintainer_data.js"></script>
</div>