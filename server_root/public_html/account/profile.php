<?php
require __DIR__ . '/../../app/init.php';
$general->loggedOutProtect();

$page_title = "My Account - Profile";
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
			<h1>Profile</h1>
		</div>
		<!-- #content-header -->


		<div id="content-container">


			<div class="row">

				<div class="col-md-9">

					<div class="row">

						<div class="col-md-4 col-sm-5">

							<div class="thumbnail">
								<img src="<?php echo BASE_URL . $user->getAvatarImgLoc(); ?>"
								     alt="Profile Picture"/>
							</div>
							<!-- /.thumbnail -->

							<br/>

						</div>
						<!-- /.col -->


						<div class="col-md-8 col-sm-7">

							<h2><?php echo $user->getFirstName() . " " . $user->getLastName(); ?></h2>

							<h4>Position: <?php echo ucfirst($user->getUserType()) ?></h4>

							<hr/>

							<p>
								<a href="javascript:;" class="btn btn-primary">Follow Rod</a>
								&nbsp;&nbsp;
								<a href="javascript:;" class="btn btn-secondary">Send Message</a>
							</p>

							<hr/>


							<ul class="icons-list">
								<li><i class="icon-li fa fa-envelope"></i> <?php echo $user->getEmail(); ?></li>
								<li><i class="icon-li fa fa-phone"></i> <?php echo $user->getMobileNum() ?></li>
							</ul>
							<?php if ($user->isTutor()) { ?>

								Major: <strong><?php echo $user->getMajorId(); ?></strong>

							<?php } ?>
							<br/>
							<br/>

							<p>

							<h3>About me</h3></p>
							<p><?php echo $user->getProfileDescription() ?></p>

							<hr/>

							<br/>

						</div>

					</div>

				</div>

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

