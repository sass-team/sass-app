
<?php
#starting the users session
//session_start();
require 'inc/config.php';
require ROOT_PATH . 'inc/db.php';
require ROOT_PATH . 'inc/model/admin.php';

$admins = new Admins($db);
var_dump($_POST);

if (empty($_POST) === false) {


	echo "<h1>debugger</h1>";

	$email = trim($_POST['login-email']);
	$password = trim($_POST['login-password']);

	$query = $db->prepare("SELECT * FROM admin
			WHERE email = '?' AND
			password = '?'");
	$query->bindValue(1, $email, );
	$query->bindValue(2, $password);

	try {
		$query->execute();
		$data = $query->fetchAll();
		var_dump($data);
	} catch (PDOException $e) {
		die($e->getMessage());
	}

//	if (empty($username) === true || empty($password) === true) {
//		$errors[] = 'Sorry, but we need your email and password.';
//	} else {
//		$login = $admins->login($email, $password);
//		echo "<h1>debugger</h1>";
//		var_dump($login);
//
//		if ($login === false) {
//			$errors[] = 'Sorry, that email/password is invalid';
//		} else {
//			// username/password is correct and the login method of the $users object returns the user's id, which is stored in $login.
//
//			// destroying the old session id and creating a new one. protect from session fixation attack.
//			session_regenerate_id(true);
//			// The user's id is now set into the user's session  in the form of $_SESSION['id']
//			$_SESSION['id'] = $login[0]['id'];
//			$_SESSION['is_admin'] = $login[0]['is_admin'];
//
//			if ($_SESSION['is_admin'] === '0') {
//				#Redirect the user to home.
//				header('Location: ' . BASE_URL . 'user/');
//				exit();
//			} else if ($_SESSION['is_admin'] === '1') { // extra check
//				header('Location: ' . BASE_URL . 'admin/');
//				exit();
//			}
//		}
//	}
}

?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>

    <title>Login - Canvas Admin</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="">
	<meta name="author" content="" />

	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,800italic,400,600,800" type="text/css">
	<link rel="stylesheet" href="<?php BASE_URL; ?>css/font-awesome.min.css" type="text/css" />
	<link rel="stylesheet" href="<?php BASE_URL; ?>css/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="<?php BASE_URL; ?>js/libs/css/ui-lightness/jquery-ui-1.9.2.custom.css" type="text/css" />
	
	<link rel="stylesheet" href="<?php BASE_URL; ?>css/App.css" type="text/css" />
	<link rel="stylesheet" href="<?php BASE_URL; ?>css/Login.css" type="text/css" />

	<link rel="stylesheet" href="<?php BASE_URL; ?>css/custom.css" type="text/css" />
	
</head>

<body>

<div id="login-container">

	<div id="logo">
		<a href="<?php BASE_URL; ?>login.php">
			<img src="<?php BASE_URL; ?>img/logos/logo-login.png" alt="Logo" />
		</a>
	</div>

	<div id="login">

		<h4>Welcome to SASS-Management System</h4>

		<h5>Please sign in to get access.</h5>

		<form method="post" id="login-form" action="login.php" class="form">

			<div class="form-group">
				<label for="login-email">Username</label>
				<input type="email" class="form-control" id="login-email" name="login-email" placeholder="Email">
			</div>

			<div class="form-group">
				<label for="login-password">Password</label>
				<input type="password" class="form-control" id="login-password" name="login-password" placeholder="Password">
			</div>

			<div class="form-group">

				<button type="submit" id="login-btn" class="btn btn-primary btn-block">Signin &nbsp; <i class="fa fa-play-circle"></i></button>

			</div>
		</form>


		<a href="javascript:;" class="btn btn-default">Forgot Password?</a>

	</div> <!-- /#login -->

</div> <!-- /#login-container -->

<script src="<?php BASE_URL; ?>js/libs/jquery-1.9.1.min.js"></script>
<script src="<?php BASE_URL; ?>js/libs/jquery-ui-1.9.2.custom.min.js"></script>
<script src="<?php BASE_URL; ?>js/libs/bootstrap.min.js"></script>

<script src="<?php BASE_URL; ?>js/App.js"></script>

<script src=".<?php BASE_URL; ?>s/Login.js"></script>

</body>
</html>