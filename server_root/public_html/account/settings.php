<?php

require __DIR__ . '/../../app/init.php';
$general->loggedOutProtect();


$errors = array();

/**
 * @param $user
 * @throws Exception
 */
function uploadAvatarImage($user) {
	if ($_FILES['fileupload-avatar']['error'] == 1) {
		throw new Exception("File size exceeded");
	} else {
		$uploaddir = ROOT_PATH . "public_html/assets/img/avatars/";
		$uploadfile = $uploaddir . basename($_FILES['fileupload-avatar']['name']);

		$path = $_FILES['fileupload-avatar']['name'];
		$allowed = array('gif', 'png', 'jpg');
		$ext = pathinfo($path, PATHINFO_EXTENSION);

		if (!in_array($ext, $allowed)) {
			throw new Exception("Only gif, png and jpg files are allowed");
		}

		if (move_uploaded_file($_FILES['fileupload-avatar']['tmp_name'], $uploadfile)) {
			$imgWebLoc = $uploaddir . "avatar_img_" . $user->getId() . "." . $ext;
			rename($uploadfile, $imgWebLoc);

			$avatarImgLoc = "assets/img/avatars/avatar_img_" . $user->getId() . "." . $ext;
			if (true !== ($updateAvatarImgResponse = $user->updateAvatarImg($avatarImgLoc, $user->getId()))) {
				throw new Exception("Error storing img loc to database. Please try again later.");
			}
		} else {
			throw new Exception("Error saving image. Please try again later");
		}
	}
}

/**
 * @return bool
 */
function isNewAvatarImageUploadedTemp() {
	return (file_exists($_FILES["fileupload-avatar"]['tmp_name']) &&
		is_uploaded_file($_FILES["fileupload-avatar"]['tmp_name'])) ?
		true : false;
}

/**
 * @return bool
 */
function isBtnUpdateProfilePrsd() {
	return isset($_POST['form_action_profile_settings']) && empty($_POST['form_action_profile_settings']);
}

/**
 * @return bool
 */
function isBtnUpdatePasswordPrsd() {
	return isset($_POST['form_action_update_password']) && empty($_POST['form_action_update_password']);
}

if (isBtnUpdateProfilePrsd()) {

	try {
		$newUpdate = false;

		$prevFirstName = $user->getFirstName();
		$prevLastName = $user->getLastName();
		$prevProfileDescription = $user->getProfileDescription();
		$prevMobileNumber = $user->getMobileNum();

		if ($user->isAdmin()) {
			$newFirstName = $_POST['firstName'];
			$newLastName = $_POST['lastName'];
		}

		$newProfileDescription = $_POST['profileDescription'];
		$newMobileNumber = $_POST['newMobileNum'];

		// check if new changes are required. if to update process
		if ($user->isAdmin() && strcmp($prevFirstName, $newFirstName) !== 0) {
			User::updateName($db, $user->getId(), User::DB_COLUMN_FIRST_NAME, $newFirstName);
			$newUpdate = true;
		}

		if ($user->isAdmin() && strcmp($prevLastName, $newLastName) !== 0) {
			if (!$user->isAdmin()) {
				throw new Exception("You're trying to hack this app. Process aborted.");
			}
			User::updateName($db, $user->getId(), User::DB_COLUMN_LAST_NAME, $newLastName);
			$newUpdate = true;
		}

		if (strcmp($prevProfileDescription, $newProfileDescription) !== 0) {
			User::updateProfileDescription($db, $user->getId(), $newProfileDescription);
			$newUpdate = true;
		}

		if (strcmp($prevMobileNumber, $newMobileNumber) !== 0) {
			User::updateMobileNumber($db, $user->getId(), $newMobileNumber);
			$newUpdate = true;
		}

		// TODO: use OOP instead of procedural programming for file upload
		if (isNewAvatarImageUploadedTemp()) {
			uploadAvatarImage($user);
			$newUpdate = true;
		}

		$newEmailAdmin = "";

		if (!$newUpdate) {
			throw new Exception("No new data were added. No changes were made.");
		}

		header('Location: ' . BASE_URL . 'account/settings/success');
		exit();
	} catch (Exception $e) {
		$errors[] = $e->getMessage();
	}

} else if (isBtnUpdatePasswordPrsd()) {

	try {
		User::updatePassword($db, $user->getId(), $_POST['oldPassword'], $_POST['newPassword1'], $_POST['newPassword2']);
	} catch (Exception $e) {
		$errors[] = $e->getMessage();
	}

	if (empty($errors) === true) {
		header('Location: ' . BASE_URL . 'account/settings/success');
		exit();
	}
}

$pageTitle = "Account - Settings";
$section = "account";

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
<?php require ROOT_PATH . 'app/views/head.php'; ?>
<body>
<div id="wrapper">
<?php
require ROOT_PATH . 'app/views/header.php';
require ROOT_PATH . 'app/views/sidebar.php';
?>


<div id="content">

<div id="content-header">
	<h1>Settings</h1>
</div>
<!-- #content-header -->


<div id="content-container">

<div class="row">

<div class="col-md-3 col-sm-4">

	<ul id="myTab" class="nav nav-pills nav-stacked">
		<li class="active">
			<a href="#profile-tab" data-toggle="tab">
				<i class="fa fa-user"></i>
				&nbsp;&nbsp;Profile Settings
			</a>
		</li>
		<li>
			<a href="#password-tab" data-toggle="tab">
				<i class="fa fa-lock"></i>
				&nbsp;&nbsp;Change Password
			</a>
		</li>
	</ul>

</div>
<!-- /.col -->

<div class="col-md-9 col-sm-8">

<div class="tab-content stacked-content">
<div class="tab-pane fade in active" id="profile-tab">

	<h3 class="">Edit Profile Settings</h3>

	<hr/>

	<br/>

	<form action="<?php echo BASE_URL; ?>account/settings" class="form-horizontal" method="post"
	      enctype="multipart/form-data">

		<?php
		if (empty($errors) !== true) {
			?>
			<div class="alert alert-danger">
				<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
				<strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>'; ?>
			</div>
		<?php } else if (isset($_GET['success'])) { ?>
			<div class="alert alert-success">
				<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
				<strong>Well done!</strong> Successfully updated data.
			</div>
		<?php } ?>
		<div class="form-group">

			<label class="col-md-3">Avatar</label>

			<div class="col-md-7">
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="fileupload-new thumbnail" style="width: 180px; height: 180px;"><img
							src="<?php echo BASE_URL . $user->getAvatarImgLoc() ?>" name="fileupload-avatar"
							alt="Profile Avatar"/></div>
					<div class="fileupload-preview fileupload-exists thumbnail"
					     style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
					<div>
												<span class="btn btn-default btn-file"><span
														class="fileupload-new">Select image</span><span
														class="fileupload-exists">
														Change</span><input name="fileupload-avatar"
												                          type="file"/></span>
						<a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
					</div>
				</div>
			</div>
			<!-- /.col -->

		</div>
		<!-- /.form-group -->

		<div class="form-group">

			<label class="col-md-3">Email</label>

			<div class="col-md-7">
				<input type="text" name="user-name" value="<?php echo $user->getEmail(); ?>"
				       class="form-control" disabled requird/>
			</div>
			<!-- /.col -->

		</div>
		<!-- /.form-group -->

		<div class="form-group">

			<label class="col-md-3" for="firstName">First Name</label>

			<div class="col-md-7">
				<input type="text" name="firstName" id="firstName" value="<?php echo $user->getFirstName(); ?>"
				       class="form-control" required <?php if (!$user->isAdmin()) echo "disabled"; ?>/>
			</div>
			<!-- /.col -->

		</div>
		<!-- /.form-group -->

		<div class="form-group">

			<label class="col-md-3" for="lastName">Last Name</label>

			<div class="col-md-7">
				<input type="text" name="lastName" id="lastName" value="<?php echo $user->getLastName(); ?>"
				       class="form-control" required <?php if (!$user->isAdmin()) echo "disabled"; ?>/>
			</div>
			<!-- /.col -->

		</div>
		<!-- /.form-group -->

		<div class="form-group">

			<label class="col-md-3" for="newMobileNum">Mobile</label>

			<div class="col-md-7">
				<input type="text" name="newMobileNum" id="newMobileNum" value="<?php echo $user->getMobileNum(); ?>"
				       class="form-control"/>
			</div>
			<!-- /.col -->

		</div>
		<!-- /.form-group -->

		<div class="form-group">

			<label class="col-md-3" for="profileDescription">Short Description</label>

			<div class="col-md-7">
				<textarea id="profileDescription" name="profileDescription" rows="6"
				          class="form-control"><?php echo $user->getProfileDescription() ?></textarea>
			</div>
			<!-- /.col -->

		</div>
		<!-- /.form-group -->

		<br/>

		<div class="form-group">

			<input type="hidden" name="form_action_profile_settings" value="">

			<div class="col-md-7 col-md-push-3">
				<button type="submit" class="btn btn-primary">Save Changes</button>
				&nbsp;
				<button type="reset" class="btn btn-default">Cancel</button>
			</div>
			<!-- /.col -->

		</div>
		<!-- /.form-group -->

	</form>


</div>
<div class="tab-pane fade" id="password-tab">

	<h3 class="">Change Your Password</h3>

	<p></p>

	<br/>

	<form action="<?php echo BASE_URL; ?>account/settings" class="form-horizontal" method="post">

		<div class="form-group">

			<label class="col-md-3" for="oldPassword">Old Password</label>

			<div class="col-md-7">
				<input type="password" name="oldPassword" id="oldPassword" class="form-control"/>
			</div>
			<!-- /.col -->

		</div>
		<!-- /.form-group -->


		<hr/>


		<div class="form-group">

			<label class="col-md-3" for="newPassword1">New Password</label>

			<div class="col-md-7">
				<input type="password" name="newPassword1" id="newPassword1" class="form-control"/>
			</div>
			<!-- /.col -->

		</div>
		<!-- /.form-group -->

		<div class="form-group">

			<label class="col-md-3" for="newPassword2">New Password Confirm</label>

			<div class="col-md-7">
				<input type="password" name="newPassword2" id="newPassword2" class="form-control"/>
			</div>
			<!-- /.col -->

		</div>
		<!-- /.form-group -->

		<br/>

		<div class="form-group">

			<div class="col-md-7 col-md-push-3">
				<button type="submit" class="btn btn-primary">Save Changes</button>
				<input type="hidden" name="form_action_update_password" value="">
				&nbsp;
				<button type="reset" class="btn btn-default">Cancel</button>
			</div>
			<!-- /.col -->

		</div>
		<!-- /.form-group -->

	</form>
</div>
</div>

</div>
<!-- /.col -->

</div>
<!-- /.row -->


</div>
<!-- /#content-container -->


</div>
<!-- #content -->




<?php include ROOT_PATH . "app/views/footer.php"; ?>
</div>
<!-- #wrapper -->

</body>
</html>

<?php include ROOT_PATH . "app/views/assets/footer_common.php"; ?>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/fileupload/bootstrap-fileupload.js"></script>
