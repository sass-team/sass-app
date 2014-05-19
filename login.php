<?php
require 'inc/init.php';
// if there is an active log in process redirect to index.php; load page only if no logged in user exists
$general->logged_in_protect();
$pageTitle = "Log In";
?>

<!-- ************  IF THE LOG IN BUTTON IS SUBMITTED ************** -->
<?php if (isset($_POST['login'])) {

// Define $myusername and $mypassword
	$email = $_POST['login_email'];
	$password = $_POST['login_password'];

// We Will prepare SQL Query
	$STM = $db->prepare("SELECT * FROM user WHERE email = :email AND password = :password");
// bind paramenters, Named paramenters always start with colon(:)
	$STM->bindParam(':email', $email);
	$STM->bindParam(':password', $password);
// For Executing prepared statement we will use below function
	$STM->execute();
// Count no. of records
	$count = $STM->rowCount();
//just fetch. only gets one row. So no foreach loop needed :D
	$row = $STM->fetch();
// User Redirect Conditions will go here
	if ($count == 1) {
		//session to see if s/he is admin - secretary - tutor ( 1 - 2 - 3)
		$_SESSION["user_types_id"] = $row["user_types_id"];
		//session to see if there is an email logged in
		$_SESSION['email'] = $email;

		header("location:index.php");
	} else {
		echo "<script>alert('Wrong email or password!','_self')</script>";
	}
}
?>
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>

	<title>Login - Canvas Admin</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="description" content="">
	<meta name="author" content=""/>

	<link rel="stylesheet"
	      href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,800italic,400,600,800"
	      type="text/css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/font-awesome.min.css" type="text/css"/>
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/bootstrap.min.css" type="text/css"/>
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>js/libs/css/ui-lightness/jquery-ui-1.9.2.custom.css"
	      type="text/css"/>

	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/App.css" type="text/css"/>
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/Login.css" type="text/css"/>

	<link rel="stylesheet" href="<?php echo BASE_URL; ?>css/custom.css" type="text/css"/>

</head>

<body>

<div id="login-container">

	<div id="logo">
		<a href="<?php echo BASE_URL; ?>login.php">
			<img src="<?php echo BASE_URL; ?>img/logos/logo-login.png" alt="Logo"/>
		</a>
	</div>

	<div id="login">

		<h4>Welcome to SASS-Management System</h4>

		<h5>Please sign in to get access.</h5>

		<form method="post" id="login-form" action="login.php" class="form">

			<div class="form-group">
				<label for="login-email">Username</label>
				<input type="email" class="form-control" id="login-email" name="login_email" placeholder="Email">
			</div>

			<div class="form-group">
				<label for="login-password">Password</label>
				<input type="password" class="form-control" id="login-password" name="login_password"
				       placeholder="Password">
			</div>

			<div class="form-group">

				<button type="submit" id="login-btn" name="login" class="btn btn-primary btn-block">Signin &nbsp; <i
						class="fa fa-play-circle"></i></button>

			</div>
		</form>


		<a href="javascript:;" class="btn btn-default">Forgot Password?</a>

	</div>
	<!-- /#login -->

</div>
<!-- /#login-container -->

<script src="<?php echo BASE_URL; ?>js/libs/jquery-1.9.1.min.js"></script>
<script src="<?php echo BASE_URL; ?>js/libs/jquery-ui-1.9.2.custom.min.js"></script>
<script src="<?php echo BASE_URL; ?>js/libs/bootstrap.min.js"></script>

<script src="<?php echo BASE_URL; ?>js/App.js"></script>

<script src=".<?php echo BASE_URL; ?>s/Login.js"></script>
</body>
</html>