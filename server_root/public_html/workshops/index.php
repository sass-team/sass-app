<?php
require __DIR__ . '/../../app/init.php';
$general->loggedOutProtect();

// protect again any sql injections on url
try {
	if (!isUrlIdValid()) throw new Exception();
	Appointment::validateId($db, $_GET['appointmentId']);
	$appointmentId = $_GET['appointmentId'];
	if (($user->isTutor()) && !AppointmentFetcher::belongsToTutor($db, $appointmentId, $user->getId())) {
		throw new Exception();
	}
} catch (Exception $e) {
	header('Location: ' . BASE_URL . 'error-404');
	exit();
}

$appointment = AppointmentFetcher::retrieveSingle($db, $appointmentId);
$course = CourseFetcher::retrieveSingle($db, $appointment[AppointmentFetcher::DB_COLUMN_COURSE_ID]);
$tutor = UserFetcher::retrieveSingle($db, $appointment[AppointmentFetcher::DB_COLUMN_TUTOR_USER_ID]);
//$students
//$instructors
$term = TermFetcher::retrieveSingle($db, $appointment[AppointmentFetcher::DB_COLUMN_TERM_ID]);
/**
 * @return bool
 */
function isUrlIdValid() {
	return isset($_GET['appointmentId']) && preg_match("/^[0-9]+$/", $_GET['appointmentId']);
}


// viewers
$page_title = "Workshops";
$section = "workshops";


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

			<h1>
				<i class="fa fa-calendar"></i>
				New Workshop Session

			</h1>


		</div>
		<!-- #content-header -->


		<div id="content-container">
			<?php
			if (empty($errors) === false) {
				?>
				<div class="alert alert-danger">
					<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
					<strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>';
					?>
				</div>
			<?php
			} ?>
			<div class="portlet">

				<div class="row">


					<div class="col-md-4">
						<div class="portlet-header">

							<h3>
								<i class="fa fa-calendar"></i>
								New Workshop Session
							</h3>

						</div>
						<!-- /.portlet-header -->

						<div class="portlet-content">

							<div class="form-group">

								<div class="form-group">
									<div class='input-group date' id='dateTimePickerStart'>
											<span class="input-group-addon"><label for="dateTimePickerStart">
													Starts At</label></span>
										<span
											class="form-control"><?php echo $appointment[AppointmentFetcher::DB_COLUMN_START_TIME]; ?></span>
										<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
									</div>
								</div>

								<div class="form-group">
									<div class='input-group date' id='dateTimePickerEnd'>
										<span class="input-group-addon"><label for="dateTimePickerEnd">Ends At</label></span>
										<span
											class="form-control"><?php echo $appointment[AppointmentFetcher::DB_COLUMN_END_TIME]; ?></span>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar">
										</span>
									</div>
								</div>

								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"><label for="courseId">Course</label></span>
										<span name='instructor'
										      class="form-control"><?php echo $course[CourseFetcher::DB_COLUMN_NAME]; ?></span>
									</div>
								</div>

								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"><label for="tutorId">Tutor</label></span>
										<span name='instructor'
										      class="form-control"><?php echo $tutor[UserFetcher::DB_COLUMN_FIRST_NAME] . " " .
												$tutor[UserFetcher::DB_COLUMN_LAST_NAME]; ?></span>
									</div>
								</div>

								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"><label for="studentsIds">Students</label></span>
											<span name='instructor'
											      class="form-control">
					<?php
					//															 instructors
					?>
											</span>
									</div>
								</div>

								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"><label for="instructorId">Instructor</label></span>
														<span class="form-control">
															<?php
															//															 instructors
															?>
											</span>
									</div>
								</div>


								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"><label for="termId">Term</label></span>
										<span class="form-control"><?php echo $term[TermFetcher::DB_COLUMN_NAME]; ?>
											</span>
									</div>
								</div>

							</div>
							<!-- /.form-group -->

						</div>
					</div>


					<div class="col-md-8">
						<div class="portlet-header">

							<h3>
								<i class="fa fa-calendar"></i>
								Date Picker
							</h3>

						</div>
						<!-- /.portlet-header -->

						<div class="portlet-content">

							<div id="workshops-calendar"></div>
						</div>
					</div>

				</div>
				<!-- /.row -->


			</div>
			<!-- /.portlet -->

		</div>
		<!-- /#content-container -->

		<div id="push"></div>
	</div>
	<!-- #content -->

	<?php include ROOT_PATH . "app/views/footer.php"; ?>
</div>
<!-- #wrapper<!-- #content -->

</body>
</html>

<?php include ROOT_PATH . "app/views/assets/footer_common.php"; ?>

<!-- dashboard assets -->
<!--<script src="--><?php //echo BASE_URL; ?><!--assets/js/plugins/icheck/jquery.icheck.min.js"></script>-->
<!--<script src="--><?php //echo BASE_URL; ?><!--assets/js/plugins/tableCheckable/jquery.tableCheckable.js"></script>-->

<!--<script src="--><?php //echo BASE_URL; ?><!--assets/js/libs/raphael-2.1.2.min.js"></script>-->
<!--<script src="--><?php //echo BASE_URL; ?><!--assets/js/plugins/morris/morris.min.js"></script>-->

<!--<script src="--><?php //echo BASE_URL; ?><!--assets/js/demos/charts/morris/area.js"></script>-->
<!--<script src="--><?php //echo BASE_URL; ?><!--assets/js/demos/charts/morris/donut.js"></script>-->

<!--<script src="--><?php //echo BASE_URL; ?><!--assets/js/plugins/sparkline/jquery.sparkline.min.js"></script>-->


<!--<script src="--><?php //echo BASE_URL; ?><!--assets/js/plugins/simplecolorpicker/jquery.simplecolorpicker.js"></script>-->

<script src="<?php echo BASE_URL; ?>assets/js/plugins/autosize/jquery.autosize.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/select2/select2.js"></script>


<script
	src="<?php echo BASE_URL; ?>assets/js/plugins/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js">
</script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/fullcalendar/fullcalendar.min.js"></script>


<script type="text/javascript">
	$(function () {
		// http://momentjs.com/docs/#/manipulating/add/
		// http://eonasdan.github.io/bootstrap-datetimepicker
		moment().format();

		$("#courseId").select2();
		$("#termId").select2();
		$("#instructorId").select2();
		$("#studentsIds").select2();
		$("#tutorId").select2();
		$("#workshops-calendar").fullCalendar({
			header: {
				left: 'prev,next',
				center: 'title',
				right: 'agendaWeek,month,agendaDay'
			},
			weekends: false, // will hide Saturdays and Sundays
			defaultView: "agendaDay",
			editable: false,
			droppable: false,
			events: [
				<?php
				include(ROOT_PATH . "app/views/partials/workshops/fullcalendar-single.php");	?>
			],
			timeFormat: 'H(:mm)' // uppercase H for 24-hour clock
		});


	})
	;
</script>