<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) <year> <copyright holders>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * @author Rizart Dokollari
 * @author George Skarlatos
 * @since 9/14/2014
 */

ob_start();
require __DIR__ . '/../app/init.php';

// if there is an active log in process redirect to students.class.php; load page only if no
// logged in user exists
$general->loggedInProtect();
$pageTitle = "Log In";


if (isUpdatePasswordBtnPressed()) {


// The email link you clicked does not belong to any account.
	//<br/>Make sure that you did not modified the link you retreived on your email.
	try {


		if (!isUrlOriginal()) {
			throw new Exception("It seems you've modified the email url we send you. Please click the original link to proceed.");
		}

		$newPassword1 = $_POST['new-password-1'];
		$newPassword2 = $_POST['new-password-2'];
		$id = $_GET['id'];
		$genString = $_GET['gen_string'];
		User::addNewPassword($id, $newPassword1, $newPassword2, $genString);
		header('Location: ' . BASE_URL . 'login/set/success');
		exit();
	} catch (Exception $e) {
		$errors[] = $e->getMessage();
	}

	#redirect the user to recover.php?success if recover() function does not return false.
	//header('Location: ' . BASE_URL . 'login/recover/success');
	//exit();
}

function isUpdatePasswordBtnPressed()
{
	return isset($_POST['form_action_update_password']) && empty($_POST['form_action_update_password']);
}

/**
 * @return bool
 */
function isVerified()
{
	return isset($_GET['success']) === true && empty ($_GET['success']);
}

/**
 * @return bool
 */
function isUrlOriginal()
{
	return isset($_GET['id'], $_GET['gen_string']) === true;
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
		<link rel="shortcut icon" href="<?php echo BASE_URL; ?>assets/img/logos/logo-login.png">

		<link rel="stylesheet"
		      href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,800italic,400,600,800"
		      type="text/css">
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/font-awesome.min.css" type="text/css"/>
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/bootstrap.min.css" type="text/css"/>
		<link rel="stylesheet"
		      href="<?php echo BASE_URL; ?>assets/js/libs/css/ui-lightness/jquery-ui-1.9.2.custom.css"
		      type="text/css"/>

		<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/App.css" type="text/css"/>
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/Login.css" type="text/css"/>

		<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/custom.css" type="text/css"/>

	</head>

	<body>
	<div id="login-container">

		<div id="logo">
			<a href="<?php echo BASE_URL; ?>login">
				<img src="<?php echo BASE_URL; ?>assets/img/logos/logo-login.png" alt="Logo"/>
			</a>
		</div>

		<div id="login">
			<?php

			if (isUrlOriginal() || isUpdatePasswordBtnPressed()) {
			?>
			<form
				action="<?php echo BASE_URL; ?>login/set/<?php echo $_GET['id']; ?>/<?php echo $_GET['gen_string']; ?>"
				class="form-horizontal" method="post">
				<?php
				if (empty($errors) === true && isVerified()) {
					?>
					<div class="alert alert-success">
						<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
						<strong>Well done!</strong> Successfully updated password.
					</div>

					<div class="form-group text-center">
						<input type="hidden" name="hidden_forgot_pressed">
						<a href="<?php echo BASE_URL; ?>" name="forgot" class="btn btn-default">
							Back to Log In Page
						</a>
					</div>
					<hr>
				<?php } else if (empty($errors) === false) { ?>
					<div class="alert alert-danger">
						<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
						<strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>'; ?>
						<hr/>
						We recommend to try to request a <a
							href='http://<?php echo $_SERVER['SERVER_NAME']; ?>/login/confirm-password'
							target='_self'>password recovery</a> if errors persists.
					</div>
				<?php } ?>

				<hr>
				<div class="form-group">
					<label class="col-md-4">Set Password</label>

					<div class="col-md-7">
						<input type="password" name="new-password-1" class="form-control">
					</div>
					<!-- /.col -->
				</div>
				<!-- /.form-group -->
				<div class="form-group">
					<label class="col-md-4">Confirm Password</label>

					<div class="col-md-7">
						<input type="password" name="new-password-2" class="form-control">
					</div>
					<!-- /.col -->
				</div>
				<!-- /.form-group -->
				<br>

				<div class="form-group">
					<div class="col-md-7 col-md-push-3">
						<button type="submit" class="btn btn-primary">Update Password</button>
						<input type="hidden" name="form_action_update_password" value="">
						&nbsp;
					</div>
					<!-- /.col -->
				</div>
				<!-- /.form-group -->
			</form>

		</div>
		<?php } else if (!empty($errors) === true) { ?>
			<div class="alert alert-danger">
				<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
				<strong>Oh snap!</strong> It seems you your url for reset your password is malformed
				<hr/>
				We recommend to try to request a <a
					href='http://<?php echo $_SERVER['SERVER_NAME']; ?>/login/confirm-password'
					target='_self'>password recovery</a> if errors persists.
			</div>
		<?php } else { ?>
			<div class="alert alert-success">
				<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
				<strong>Well done!</strong> Successfully updated password.
			</div>
			<hr>
			<div class="form-group text-center">
				<input type="hidden" name="hidden_forgot_pressed">
				<a href="<?php echo BASE_URL; ?>login/" name="forgot" class="btn btn-default">
					Back to Log In Page
				</a>
			</div>
		<?php } ?>
	</div>
	<!-- /#forgot-container -->

	<script src="<?php echo BASE_URL; ?>assets/js/libs/jquery-1.9.1.min.js"></script>
	<script src="<?php echo BASE_URL; ?>assets/js/libs/jquery-ui-1.9.2.custom.min.js"></script>
	<script src="<?php echo BASE_URL; ?>assets/js/libs/bootstrap.min.js"></script>

	<script src="<?php echo BASE_URL; ?>assets/js/App.js"></script>

	<script src="<?php echo BASE_URL; ?>assets/js/Login.js"></script>
	</body>
	</html>

<?php
// TODO: create UI to change password
?>