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
 * @author Rizart Dokollari & George Skarlatos
 * @since 6/29/14.
 */

?>

<?php
ob_start();
// TODO: Add option-functionality to resend email if password forgot
// TODO: sql make 'img' of database to NOT NULL & refactor name to 'img_location'
require '../inc/init.php';

// if there is an active log in process redirect to edit.php; load page only if no
// logged in user exists
$general->logged_in_protect();
$page_title = "Log In";


/**
 * @return bool
 */
function isVerified() {
	return isset($_GET['success']) === true && empty ($_GET['success']);
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
	<link rel="shortcut icon" href="<?php echo BASE_URL; ?>img/logos/logo-login.png">

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
		<a href="<?php echo BASE_URL; ?>login">
			<img src="<?php echo BASE_URL; ?>img/logos/logo-login.png" alt="Logo"/>
		</a>
	</div>

	<div id="login">
		<?php
		if (isVerified()) {
			?>
			<h4>Thank you, we've send you a randomly generated password in your email.</h4>
			<h3><a href="<?php echo BASE_URL; ?>login">Log In</a></h3>
		<?php
		} else if (isset ($_GET['email'], $_GET['gen_string']) === true) {
			$email = trim($_GET['email']);
			$string = trim($_GET['gen_string']);

			try {
				if ($users->email_exists($email) === false || $users->recover($email, $string) === false) {
					$errors[] = 'Sorry, something went wrong and we couldn\'t recover your password.';
				} // end if
			} catch (Exception $e) {
				$errors[] = $e->getMessage();
			}

			if (isset($errors) && empty($errors) === false) {
				echo '<p>' . implode('</p><p>', $errors) . '</p>';
			} else {
				#redirect the user to recover.php?success if recover() function does not return false.
				header('Location: ' . BASE_URL . 'login/recover.php?success');
				exit();
			} // end else if
		} else {
			$general->logged_in_protect();
			exit();
		} // end else
		?>
	</div>
</div>
<!-- /#forgot-container -->

<script src="<?php echo BASE_URL; ?>js/libs/jquery-1.9.1.min.js"></script>
<script src="<?php echo BASE_URL; ?>js/libs/jquery-ui-1.9.2.custom.min.js"></script>
<script src="<?php echo BASE_URL; ?>js/libs/bootstrap.min.js"></script>

<script src="<?php echo BASE_URL; ?>js/App.js"></script>

<script src=".<?php echo BASE_URL; ?>s/Login.js"></script>
</body>
</html>

<?php
// TODO: create UI to change password
?>