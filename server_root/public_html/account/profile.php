<?php
require __DIR__ . '/../../app/init.php';
$general->loggedOutProtect();

try {

	$curUser = $user;
	if ($curUser->isTutor()) {
		$teachingCourses = TutorFetcher::retrieveCurrTermTeachingCourses($db, $curUser->getId());
	}
} catch (Exception $e) {
	$errors[] = $e->getMessage();
}
$pageTitle = "Account - Profile";
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

							<?php
							//							TODO: add functionality instant messages
							?>

							<hr/>


							<ul class="icons-list">
								<li><i class="icon-li fa fa-envelope"></i> <?php echo $user->getEmail(); ?></li>
								<li><i class="icon-li fa fa-phone"></i>Mobile: <?php echo $user->getMobileNum(); ?>
									<?php if ($user->isTutor()) { ?>

								<li><i class="icon-li fa fa-book"></i>Major: <strong><?php echo $user->getMajorId(); ?></strong>
								</li>

								<?php } ?>
							</ul>
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

			<?php if ($curUser->isTutor()): ?>
				<!-- /.row -->
				<div class="row">

					<div class="col-md-10">
						<h3 class="heading">Special Information</h3>


						<div class="panel-group accordion" id="accordion">

							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a class="accordion-toggle" data-toggle="collapse" data-parent=".accordion"
										   href="#collapseOne">
											<i class="fa fa-book"></i> Current Teaching Courses
											- <?php
											if (!empty($teachingCourses)):
												echo $teachingCourses[0][TermFetcher::DB_TABLE . "_" . TermFetcher::DB_COLUMN_NAME];
											endif;
											?>
										</a>
									</h4>
								</div>

								<div id="collapseOne" class="panel-collapse collapse in">
									<div class="panel-body">
										<table class="table table-hover">
											<thead>
											<tr>
												<th class="text-center">#</th>
												<th class="text-center">Course Code</th>
												<th class="text-center">Course Name</th>
												<th class="text-center">Status</th>
											</tr>
											</thead>
											<tbody>

											<?php
											if (empty($errors) === true) {
												$counter = 1;
												foreach ($teachingCourses as $course) {
													include(ROOT_PATH . "app/views/partials/course/table-data-profile-view.html.php");
													$counter = $counter + 1;
												}
											} ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<!-- /.panel-default -->

							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a class="accordion-toggle" data-toggle="collapse" data-parent=".accordion"
										   href="#collapseTwo">
											<i class="fa fa-clock-o"></i> Current Schedule
										</a>
									</h4>
								</div>

								<div id="collapseTwo" class="panel-collapse collapse">
									<div class="panel-body">
										<div class="well">
											Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson
											ad
											squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck
											quinoa
											nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid
											single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica,
											craft
											beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher
											vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth
											nesciunt
											you probably haven't heard of them accusamus labore sustainable VHS.
										</div>
									</div>
								</div>
							</div>
							<!-- /.panel-default -->
						</div>
						<!-- /.accordion -->
					</div>

				</div>
			<?php endif; ?>
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

