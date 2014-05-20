<?php
require "../inc/init.php";
$general->logged_out_protect();

$errors = array();

if (isset($_POST['form_action_profile_settings'])) {
	$update_result = $user->update_profile($_POST['first-name'], $_POST['last-name'],
		$_POST['mobile'], $_POST['profile-description'], "/SASS-MS/img/avatars/author.jpg",
		$user_email);


	if ($update_result !== true) {
		$errors = $update_result;
	} else {
		header('Location: ' . BASE_URL . 'my_account/profile-settings.php');
		exit();
	}
}

$page_title = "My Account - Profile";
require ROOT_PATH . 'inc/view/header.php';
require ROOT_PATH . 'inc/view/sidebar.php';

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

			<form action="./profile-settings.php" class="form-horizontal" method="post">

				<?php
				if (empty($errors) !== true) {
					?>
					<div class="alert alert-danger">
						<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
						<strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>'; ?>
					</div>
				<?php } ?>
				<div class="form-group">

					<label class="col-md-3">Avatar</label>

					<div class="col-md-7">
						<div class="fileupload fileupload-new" data-provides="fileupload">
							<div class="fileupload-new thumbnail" style="width: 180px; height: 180px;"><img
									src="<?php echo BASE_URL . $img_loc ?>" alt="Profile Avatar"/></div>
							<div class="fileupload-preview fileupload-exists thumbnail"
							     style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>
							<div>
												<span class="btn btn-default btn-file"><span
														class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input
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
						<input type="text" name="user-name" value="<?php echo $user_email; ?>"
						       class="form-control" disabled/>
					</div>
					<!-- /.col -->

				</div>
				<!-- /.form-group -->

				<div class="form-group">

					<label class="col-md-3">First Name</label>

					<div class="col-md-7">
						<input type="text" name="first-name" value="<?php echo $first_name; ?>"
						       class="form-control"/>
					</div>
					<!-- /.col -->

				</div>
				<!-- /.form-group -->

				<div class="form-group">

					<label class="col-md-3">Last Name</label>

					<div class="col-md-7">
						<input type="text" name="last-name" value="<?php echo $last_name; ?>"
						       class="form-control"/>
					</div>
					<!-- /.col -->

				</div>
				<!-- /.form-group -->

				<div class="form-group">

					<label class="col-md-3">Mobile</label>

					<div class="col-md-7">
						<input type="text" name="mobile" value="<?php echo $mobile_num; ?>" class="form-control"/>
					</div>
					<!-- /.col -->

				</div>
				<!-- /.form-group -->

				<div class="form-group">

					<label class="col-md-3">Short Description</label>

					<div class="col-md-7">
						<textarea id="about-textarea" name="profile-description" rows="6"
						          class="form-control"><?php echo $profile_description ?></textarea>
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

			<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.
				Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes.</p>

			<br/>

			<form action="./page-settings.php" class="form-horizontal">

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

<?php include ROOT_PATH . "inc/view/footer.php"; ?>
