<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once($path . '/utils/session.php');
?>

<div id="page-wrapper">
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

        <input type="hidden" id="username" value="<?php echo $_SESSION['login_user'] ?>" />
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
    <script src="assets/js/profile_data.js"></script>
</div>