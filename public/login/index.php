<?php
ob_start();
require '../app/init.php';

// if there is an active log in process redirect to overview.php; load page only if no
// logged in user exists
$general->loggedInProtect();
$page_title = "Log In";

?>

	<!-- ************  IF THE LOG IN BUTTON IS SUBMITTED ************** -->
	<!-- 1. It's preferred to create a hidden input for security purposes, as well verifying that this input or another dummy
	is not filled, as to protect from robots/spam.
		2. It's preferred to have the php code on the beginning of the script and the view code afterwards. More neat & organized.-->
<?php

if (isLoginBtnPressed()) {

	try {
		if (!isset($_POST['login_email'])) {
			throw new Exception("The email you entered does not belong to any account.
			<br/>You can login using any email associated with your account. Make sure that it is typed correctly.");
		}

		if (!isset($_POST['login_password'])) {
			throw new Exception("The password you entered is incorrect. Please try again (make sure your caps lock is off).");
		}

		$email = trim($_POST['login_email']);
		$password = trim($_POST['login_password']);

		// check if credentials are correct. If they are not, an exception occurs.
		$id = $db->login($email, $password);
		// destroying the old session id
		//and creating a new one. protect from session fixation attack.
		session_regenerate_id(true);
		// The user's id is now set into the user's session  in the form of $_SESSION['id']
		$_SESSION['id'] = $id;

		// if there is an active log in process redirect to overview.php; load page only if no logged in user exists
		$general->loggedInProtect();
	} catch (Exception $e) {
		$errors[] = $e->getMessage();
	}
}


/**
 * @return bool
 */
function isLoginBtnPressed() {
	return isset($_POST['hidden_submit_pressed']) && empty($_POST['hidden_submit_pressed']);
}

/**
 * @return bool
 */
function isForgotBtnPressed() {
	return isset($_POST['hidden_forgot_pressed']) && empty($_POST['hidden_forgot_pressed']);
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
		<link rel="shortcut icon" href="<?php echo BASE_URL; ?>app/assets/img/logos/favicon.ico">

		<link rel="stylesheet"
		      href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,800italic,400,600,800"
		      type="text/css">
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>app/assets/css/font-awesome.min.css" type="text/css"/>
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>app/assets/css/bootstrap.min.css" type="text/css"/>
		<link rel="stylesheet"
		      href="<?php echo BASE_URL; ?>app/assets/js/libs/css/ui-lightness/jquery-ui-1.9.2.custom.css"
		      type="text/css"/>

		<link rel="stylesheet" href="<?php echo BASE_URL; ?>app/assets/css/App.css" type="text/css"/>
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>app/assets/css/Login.css" type="text/css"/>

		<link rel="stylesheet" href="<?php echo BASE_URL; ?>app/assets/css/custom.css" type="text/css"/>

		<style type="text/css">
			#wrap {
				min-height: 100%;
				height: auto !important;
				margin: 0 auto -60px;
			}

			.video-section .pattern-overlay {
				padding: 110px 0 32px;
				min-height: 496px;
				/* Incase of overlay problems just increase the min-height*/
			}

			.video-section h1, .video-section h3 {
				text-align: center;
				color: #fff;
			}

			.video-section h1 {
				font-size: 110px;
				font-family: 'Buenard', serif;
				font-weight: bold;
				text-transform: uppercase;
				margin: 40px auto 0px;
				text-shadow: 1px 1px 1px #000;
				-webkit-text-shadow: 1px 1px 1px #000;
				-moz-text-shadow: 1px 1px 1px #000;
			}

			.video-section h3 {
				font-size: 25px;
				font-weight: lighter;
				margin: 0px auto 15px;
			}

			.video-section .buttonBar {
				display: none;
			}

			.player {
				font-size: 1px;
			}

		</style>

	</head>

	<body>

	<div id="wrap">
		<div id="login-container">

			<div id="logo">
				<a href="<?php echo BASE_URL; ?>login/">
					<img src="<?php echo BASE_URL; ?>app/assets/img/logos/logo-login.png" alt="Logo"/>
				</a>

			</div>


			<!-- /#login -->
			<div id="login">
				<h4>Welcome to SASS-Management System</h4>
				<h5>Please log in to get access.</h5>

				<form method="post" id="login-form" action="" class="form">
					<div class="form-group">
						<label for="login-email">Username</label>
						<input type="email" class="form-control" id="login-email" name="login_email" placeholder="Email"
						       required>
					</div>

					<div class="form-group">
						<label for="login-password">Password</label>
						<input type="password" class="form-control" id="login-password" name="login_password"
						       placeholder="Password" required>
					</div>

					<div class="form-group">
						<input type="hidden" name="hidden_submit_pressed">
						<button type="submit" id="login-btn" name="login" class="btn btn-primary btn-block">Log In &nbsp; <i
								 class="fa fa-sign-in"></i></button>
					</div>
				</form>

				<div class="form-group text-center">
					<input type="hidden" name="hidden_forgot_pressed">
					<a href="confirm-password" name="forgot" class="btn btn-default">
						Forgot Password?
					</a>
				</div>
				<?php
				if (empty($errors) === false) {
					?>
					<div class="alert alert-danger">
						<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
						<strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>'; ?>
					</div>
				<?php
				}
				?>
			</div>
			<!-- /# -->

		</div>
		<!-- /#login-container -->

		<footer id="footer" class="navbar navbar-fixed-bottom">
			<ul class="nav pull-right">
				<li>
					Copyright &copy; <?php auto_copyright('2014'); // 2010 - 2011 ?>,
					&#60;devs&#62;<a href="https://github.com/rdok" target="_blank">rdok</a> &#38;
					<a href="http://gr.linkedin.com/pub/georgios-skarlatos/70/461/123" target="_blank">geoif</a>&#60;&#47;devs&#62;

				</li>
			</ul>
		</footer>

		<!--Video Section-->
		<section class="content-section video-section">
			<div class="pattern-overlay">
				<a id="bgndVideo" class="player"
				   data-property="{videoURL:'https://www.youtube.com/watch?v=qW5VeFdeYTU', quality:'large', autoPlay:true, mute:true, opacity:1, optimizeDisplay:true, loop:true, vol:1, realfullscreen:true}">bg</a>
			</div>
		</section>
		<!--Video Section Ends Here-->

		<?php function auto_copyright($year = 'auto') { ?>
			<?php if (intval($year) == 'auto') {
				$year = date('Y');
			} ?>
			<?php if (intval($year) == date('Y')) {
				echo intval($year);
			} ?>
			<?php if (intval($year) < date('Y')) {
				echo intval($year) . ' - ' . date('Y');
			} ?>
			<?php if (intval($year) > date('Y')) {
				echo date('Y');
			} ?>
		<?php } ?>


	</div>
	<script src="<?php echo BASE_URL; ?>app/assets/js/libs/jquery-1.9.1.min.js"></script>
	<script src="<?php echo BASE_URL; ?>app/assets/js/libs/jquery-ui-1.9.2.custom.min.js"></script>
	<script src="<?php echo BASE_URL; ?>app/assets/js/libs/bootstrap.min.js"></script>

	<script src="<?php echo BASE_URL; ?>app/assets/js/App.js"></script>

	<script src="<?php echo BASE_URL; ?>app/assets/js/Login.js"></script>

	<!-- Warming Up -->
	<link href='http://fonts.googleapis.com/css?family=Buenard:700' rel='stylesheet' type='text/css'>
	<script src="http://pupunzi.com/mb.components/mb.YTPlayer/demo/inc/jquery.mb.YTPlayer.js"></script>

	<script type="text/javascript">
		$(document).ready(function () {
			setTimeout(function () {
				$(".player").mb_YTPlayer();
			}, 10);
		});</script>

	</body>
	</html>

<?php
//TODO: jquery: validate regex password. result less server requests.
?>