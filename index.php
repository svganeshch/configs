<?php
session_start();
require('connect_moi.php');

if (isset($_POST['username']) and isset($_POST['password'])){
	$username = $_POST['username'];

	$password = $_POST['password'];

	$_SESSION["login_user"] = "$username";

	$password_hash_query = "SELECT `admin_password` FROM `admin_login` WHERE `admin_username`='$username'";
	$password_hash_res = mysqli_query($db, $password_hash_query) or die(mysqli_error($db));
	$pass_hash = mysqli_fetch_assoc($password_hash_res);
	$pass_hash = $pass_hash['admin_password'];

	if (password_verify($password, $pass_hash)){
		$_SESSION['username'] = $username;
	}else{
		$fmsg = "Invalid Login Credentials.";
	}
}

if (isset($_SESSION['username'])){
    header("Location: dashboard.php");
    exit();
}else{
?>
<html>
<head>
<title>Jenkins Login</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >

<link rel="stylesheet" href="/assets/css/styles.css" >

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
      <form class="form-signin" method="POST">
      <?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
        <h2 class="form-signin-heading">Please Login</h2>
        <div class="input-group">
	  <span class="input-group-addon" id="basic-addon1">@</span>
	  <input type="text" name="username" class="form-control" placeholder="Username" required>
	</div>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
      </form>
</div>

</body>

</html>
<?php } ?>
