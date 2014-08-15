<?php

require "../app/init.php";
$general->logged_out_protect();


$errors = array();

if (isset($_POST['form_action_profile_settings'])) {

	try {
		$update_result = $users->update_profile_data($_POST['first-name'], $_POST['last-name'],
			$user->getMobileNum(), $_POST['mobile'], $_POST['profile-description'], $user->getEmail());

	} catch (Exception $e) {
		$errors[] = $e->getMessage();
	}
	// decide if file upload needed.
	$change_avatar_img = (file_exists($_FILES["fileupload-avatar"]['tmp_name']) &&
		is_uploaded_file($_FILES["fileupload-avatar"]['tmp_name'])) ?
		true : false;

	// TODO: use OOP instead of procedural programming for file upload
	if ($change_avatar_img === true) {

		if ($_FILES['fileupload-avatar']['error'] == 1) {
			$errors[] = "File size exceeded";
		} else {
			$uploaddir = ROOT_PATH . "app/assets/img/avatars/";
			$uploadfile = $uploaddir . basename($_FILES['fileupload-avatar']['name']);

			$path = $_FILES['fileupload-avatar']['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);

			if (move_uploaded_file($_FILES['fileupload-avatar']['tmp_name'], $uploadfile)) {
				$img_web_loc = $uploaddir . "avatar_img_" . $user->getId() . "." . $ext;
				rename($uploadfile, $img_web_loc);

				$avatar_img_loc = "app/assets/img/avatars/avatar_img_" . $user->getId() . "." . $ext;
				if (true !== ($update_avatar_img_response = $users->update_avatar_img($avatar_img_loc, $user->getId()))) {
					$errors[] = "Error storing img loc to database. Please try again later.";
				}
			} else {
				$errors[] = "Error saving image. Please try again later";
			}
		}
	}

	if (empty($errors) === true) {
		header('Location: ' . BASE_URL . 'account/settings/success');
		exit();
	}
} else if (isset($_POST['form_action_update_password'])) {

	try {
		$users->update_password($user->getId(), $_POST['old-password'], $_POST['new-password-1'], $_POST['new-password-2']);
	} catch (Exception $e) {
		$errors[] = $e->getMessage();
	}

	if (empty($errors) === true) {
		header('Location: ' . BASE_URL . 'account/settings/success');
		exit();
	}
}

$page_title = "My Account - Settings";
$section = "account";

require ROOT_PATH . 'app/views/head.php';
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

		<form action="<?php echo BASE_URL; ?>account/settings" class="form-horizontal" method="post" enctype="multipart/form-data">

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
														class="fileupload-new">Select image</span><span class="fileupload-exists">
														Change</span><input name="fileupload-avatar" type="file"/></span>
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
					       class="form-control" disabled/>
				</div>
				<!-- /.col -->

			</div>
			<!-- /.form-group -->

			<div class="form-group">

				<label class="col-md-3">First Name</label>

				<div class="col-md-7">
					<input type="text" name="first-name" value="<?php echo $user->getFirstName(); ?>"
					       class="form-control"/>
				</div>
				<!-- /.col -->

			</div>
			<!-- /.form-group -->

			<div class="form-group">

				<label class="col-md-3">Last Name</label>

				<div class="col-md-7">
					<input type="text" name="last-name" value="<?php echo $user->getLastName(); ?>"
					       class="form-control"/>
				</div>
				<!-- /.col -->

			</div>
			<!-- /.form-group -->

			<div class="form-group">

				<label class="col-md-3">Mobile</label>

				<div class="col-md-7">
					<input type="text" name="mobile" value="<?php echo $user->getMobileNum(); ?>" class="form-control"/>
				</div>
				<!-- /.col -->

			</div>
			<!-- /.form-group -->

			<div class="form-group">

				<label class="col-md-3">Short Description</label>

				<div class="col-md-7">
					<textarea id="about-textarea" name="profile-description" rows="6"
					          class="form-control"><?php echo $user->getProfileDescription() ?></textarea>
				</div>
				<!-- /.col -->

			</div>
			<!-- /.form-group -->

			<br/>

			<div class="form-group">

				<input type="hidden" name="form_action_profile_settings">

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

				<label class="col-md-3">Old Password</label>

				<div class="col-md-7">
					<input type="password" name="old-password" class="form-control"/>
				</div>
				<!-- /.col -->

			</div>
			<!-- /.form-group -->


			<hr/>


			<div class="form-group">

				<label class="col-md-3">New Password</label>

				<div class="col-md-7">
					<input type="password" name="new-password-1" class="form-control"/>
				</div>
				<!-- /.col -->

			</div>
			<!-- /.form-group -->

			<div class="form-group">

				<label class="col-md-3">New Password Confirm</label>

				<div class="col-md-7">
					<input type="password" name="new-password-2" class="form-control"/>
				</div>
				<!-- /.col -->

			</div>
			<!-- /.form-group -->

			<br/>

			<div class="form-group">

				<div class="col-md-7 col-md-push-3">
					<button type="submit" class="btn btn-primary">Save Changes</button>
					<input type="hidden" name="form_action_update_password">
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


</div> <!-- #wrapper -->

<?php include ROOT_PATH . "app/views/footer.php"; ?>
</div>
</body>
</html>

<?php include ROOT_PATH . "app/views/assets/footer_common.php"; ?>

<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/fileupload/bootstrap-fileupload.js"></script>
