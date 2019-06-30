<?php
require_once('connect_moi.php');
session_start();

if (isset($_POST['username']) and isset($_POST['password'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	$username = stripslashes($username);
	$password = stripslashes($password);

	$username_query = "SELECT * from `admin_login` WHERE `admin_username`='$username'";
	$username_query_res = mysqli_query($db, $username_query) or die(mysqli_error($db));
	$user_row_chk = mysqli_num_rows($username_query_res);
	if ($user_row_chk == 1) {
		$password_hash_query = "SELECT `admin_password` FROM `admin_login` WHERE `admin_username`='$username'";
		$password_hash_res = mysqli_query($db, $password_hash_query) or die(mysqli_error($db));
		$pass_hash = mysqli_fetch_assoc($password_hash_res);
		$pass_hash = $pass_hash['admin_password'];

		if (password_verify($password, $pass_hash)){
			$_SESSION['login_user'] = $username;
		}else{
			$fmsg = "Invalid Password!";
		}
	} else {
		$fmsg = "Invalid Username!";
	}
}

if (isset($_SESSION['login_user'])){
    header("Location: dashboard.php");
    exit();
}else{
?>
<html>
<head>
<title>Jenkins Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit" value=" Login ">Login</button>
      </form>
</div>

</body>

</html>
<?php } ?>
