<?php
require __DIR__ . '/../../app/init.php';
$general->loggedOutProtect();

try {

	$curUser = $user;
	if ($curUser->isTutor()) {
		$teachingCourses = TutorFetcher::retrieveCurrTermTeachingCourses($db, $curUser->getId());
		$schedules = ScheduleFetcher::retrieveCurrWorkingHours($db, $curUser->getId());
		$currentTerms = TermFetcher::retrieveCurrTerm($db);
	}
} catch (Exception $e) {
	$errors[] = $e->getMessage();
}
$pageTitle = "Account - Profile";
$section = "account";

$mobileNum = $user->getMobileNum();

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
<?php require ROOT_PATH . 'views/head.php'; ?>
<body>
<div id="wrapper">
<?php
require ROOT_PATH . 'views/header.php';
require ROOT_PATH . 'views/sidebar.php';
?>



<div id="content">

<div id="content-header">
	<h1>
		<i class="fa fa-cloud"></i>
		Profile
	</h1>
</div>
<!-- #content-header -->

<div id="content-container">


<div class="row">
	<?php
	if ($mobileNum == '') {
		echo '<div class="alert alert-warning" role="alert"><strong>Warning!</strong> Please fill in your mobile number!</div>';
	}
	?>
	<div class="col-md-9">

		<div class="row">

			<div class="col-md-4 col-sm-5">

				<div class="thumbnail">
					<img src="<?php echo BASE_URL . $user->getAvatarImgLoc(); ?>"
					     alt="Profile Picture"/>
				</div>
				<!-- /.thumbnail -->

				<br/>

				<?php
				if (empty($errors) === false) {
					?>
					<div class="alert alert-danger">
						<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
						<strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>';
						?>
					</div>
				<?php } ?>
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
		<!-- /.row -->
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
								<i class="fa fa-book"></i>
								<?php
								if (empty($teachingCourses)) {
									echo 'The list of Teaching Courses for the current terms is empty!';
								} else {
									echo 'Facilitated Courses';
									foreach ($currentTerms as $currentTerm) {
										echo " - " . $currentTerm[TermFetcher::DB_COLUMN_NAME];
									}
								}
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
										include(ROOT_PATH . "views/partials/course/table-data-profile-view.html.php");
										$counter = $counter + 1;
									}
								} ?>
								</tbody>
							</table>
						</div>
					</div>
					<!-- #collapseOne -->
				</div>
				<!-- /.panel-default -->

				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a class="accordion-toggle" data-toggle="collapse" data-parent=".accordion"
							   href="#collapseTwo">
								<i class="fa fa-clock-o"></i>
								<?php
								if (empty($schedules)) {
									echo 'Current schedule is empty!';
								} else {
									echo 'Current Schedule';
									foreach ($currentTerms as $currentTerm) {
										echo " - " . $currentTerm[TermFetcher::DB_COLUMN_NAME];
									}
								}
								?>
							</a>
						</h4>
					</div>

					<div id="collapseTwo" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="row">

								<div class="col-md-10">

									<div class="table-responsive">
										<table class="table table-hover">

											<thead>
											<tr>
												<th class="text-center" data-filterable="true" data-sortable="true"
												    data-sortable="true">Days
												</th>
												<th class="text-center" data-filterable="true" data-sortable="false"
												    data-sortable="true">Start - End
												</th>
												<th class="text-center" data-filterable="true" data-sortable="false"
												    data-sortable="true">Term
												</th>
												<th class="text-center">Status</th>
											</tr>
											</thead>
											<tbody>

											<?php
											if (empty($errors) === true) {
												foreach ($schedules as $schedule) {
													include(ROOT_PATH . "views/partials/schedule/profile-table-data-view.html.php");
												}
											}
											?>
											</tbody>
										</table>
									</div>
									<!-- /.table-responsive -->


								</div>
								<!-- /.col -->


								<!-- <div class="col-md-7">
									<div class="portlet-header">

									</div> -->
								<!-- /.portlet-header -->

								<!-- <div class="portlet-content">

									<div id="appointments-calendar"></div>
								</div>
							</div> -->


							</div>
							<!-- /.row -->

						</div>
						<!-- /.panel-default -->
					</div>
					<!-- #collapseTwo -->
				</div>
				<!-- /.panel-default -->
			</div>
			<!-- /.accordion -->
		</div>

	</div>
<?php endif; ?>
</div>
<!-- /#content-container -->

<div id="push"></div>

</div>
<!-- #content -->

<?php include ROOT_PATH . "views/footer.php"; ?>

</div>
<!-- #wrapper -->

<?php include ROOT_PATH . "views/assets/footer_common.php"; ?>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/autosize/jquery.autosize.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/select2/select2.js"></script>

<script
	src="<?php echo BASE_URL; ?>assets/js/plugins/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js">
</script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/fullcalendar/fullcalendar.min.js"></script>

<script type="text/javascript">
	$(function () {
		$("#appointments-calendar").fullCalendar({
			header: {
				left: 'prev,next',
				center: 'title',
				right: 'agendaWeek,month,agendaDay'
			},
			weekends: false, // will hide Saturdays and Sundays
			defaultView: "agendaWeek",
			editable: false,
			droppable: false,
			events: [
				<?php if($curUser->isTutor()){
					if(sizeof($schedules) <= 1){
						foreach($schedules as $schedule){
							include(ROOT_PATH . "views/partials/schedule/fullcalendar-single.php");
						}
					 }else{
					   for($i = 0; $i < (sizeof($schedules) - 1); $i++){
					      include(ROOT_PATH . "views/partials/schedule/fullcalendar-multi.php");
						}
						$lastScheduleIndex = sizeof($schedules)-1;
						$schedule = $schedules[$lastScheduleIndex];
						include(ROOT_PATH . "views/partials/schedule/fullcalendar-multi.php");
					}
				}
				?>
			],
			timeFormat: 'H(:mm)' // uppercase H for 24-hour clock
		});

	});
</script>
</body>
</html>

