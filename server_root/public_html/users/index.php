<?php
require __DIR__ . '/../../app/init.php';
$general->loggedOutProtect();

// protect again any sql injections on url
if (!isset($_GET['id']) || !preg_match("/^[0-9]+$/", $_GET['id'])) {
	header('Location: ' . BASE_URL . 'error-404');
	exit();
} else {
	$userId = $_GET['id'];
}

try {

	if (($data = User::retrieve($db, $userId)) === FALSE) {
		header('Location: ' . BASE_URL . 'error-404');
		exit();
	}

	if (strcmp($data['type'], 'tutor') === 0) {
		$tutor = TutorFetcher::retrieve($db, $userId);
		$curUser = new Tutor($db, $data['id'], $data['f_name'], $data['l_name'], $data['email'], $data['mobile'], $data['img_loc'], $data['profile_description'], $data['date'], $data['type'], $data['active'], $tutor[TutorFetcher::DB_COLUMN_MAJOR_ID]);
	} else if (strcmp($data['type'], 'secretary') === 0) {
		$curUser = new Secretary($db, $data['id'], $data['f_name'], $data['l_name'], $data['email'], $data['mobile'], $data['img_loc'], $data['profile_description'], $data['date'], $data['type'], $data['active']);
	} else if (strcmp($data['type'], 'admin') === 0) {
		$curUser = new Admin($db, $data['id'], $data['f_name'], $data['l_name'], $data['email'], $data['mobile'], $data['img_loc'], $data['profile_description'], $data['date'], $data['type'], $data['active']);
	} else {
		throw new Exception("Something terrible has happened with the database. <br/>The software developers will tremble with fear.");
	}

} catch (Exception $e) {
	$errors[] = $e->getMessage();
}


$page_title = "Profile";
$section = "users";
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
								<img src="<?php echo BASE_URL . $curUser->getAvatarImgLoc(); ?>"
								     alt="Profile Picture"/>
							</div>
							<!-- /.thumbnail -->

							<br/>

						</div>
						<!-- /.col -->


						<div class="col-md-8 col-sm-7">

							<h2><?php echo $curUser->getFirstName() . " " . $curUser->getLastName(); ?></h2>

							<h4>Position: <?php echo ucfirst($curUser->getUserType()) ?></h4>

							<hr/>

							<p>
								<a href="javascript:;" class="btn btn-primary">Follow Rod</a>
								&nbsp;&nbsp;
								<a href="javascript:;" class="btn btn-secondary">Send Message</a>
							</p>

							<hr/>


							<ul class="icons-list">
								<li><i class="icon-li fa fa-envelope"></i> <?php echo $curUser->getEmail(); ?></li>
								<li><i class="icon-li fa fa-phone"></i> <?php echo $curUser->getMobileNum() ?></li>
							</ul>
							<?php if ($curUser->isTutor()) { ?>

								Major: <strong><?php echo $curUser->getMajorId(); ?></strong>

							<?php } ?>
							<br/>
							<br/>

							<h3>About me</h3>

							<p><?php echo $curUser->getProfileDescription() ?></p>

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

