<?php
require __DIR__ . '/../../app/init.php';
$general->loggedOutProtect();

// redirect if user elevation is not that of secretary or admin
if ($user->isTutor()) {
	header('Location: ' . BASE_URL . "error-403");
	exit();
}


// viewers
$pageTitle = "New Workshop";
$section = "workshops";

try {
	$courses = CourseFetcher::retrieveAll($db);
	$terms = TermFetcher::retrieveAll($db);
	$instructors = InstructorFetcher::retrieveAll($db);
	$students = StudentFetcher::retrieveAll($db);
	$tutors = TutorFetcher::retrieveAll($db);
	$appointments = AppointmentFetcher::retrieveAll($db);

	if (isBtnAddStudentPrsd()) {
		Appointment::add($db, $_POST['dateTimePickerStart'], $_POST['dateTimePickerEnd'], $_POST['courseId'],
			$_POST['studentsIds'], $_POST['tutorId'], $_POST['instructorId'], $_POST['termId']);
		header('Location: ' . BASE_URL . 'workshops/add/success');
		exit();
	}
} catch (Exception $e) {
	$errors[] = $e->getMessage();
}

function isBtnAddStudentPrsd() {
	return isset($_POST['hiddenSubmitPrsd']) && empty($_POST['hiddenSubmitPrsd']);
}


function isModificationSuccess() {
	return isset($_GET['success']) && strcmp($_GET['success'], 'y1!q' === 0);
}

/**
 * http://stackoverflow.com/a/4128377/2790481
 *
 * @param $findId
 * @param $objects
 * @return bool
 */
function get($objects, $findId, $column) {
	foreach ($objects as $object) {
		if ($object[$column] === $findId) return $object;
	}

	return false;
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
			} else if (isModificationSuccess()) {
				?>
				<div class="alert alert-success">
					<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
					<strong>Data successfully modified!</strong> <br/>
				</div>
			<?php } ?>
			<div class="portlet">

				<div class="row">


					<div class="col-md-5">
						<div class="portlet-header">

							<h3>
								<i class="fa fa-calendar"></i>
								New Workshop Session
							</h3>

						</div>
						<!-- /.portlet-header -->

						<div class="portlet-content">

							<div class="form-group">
								<form method="post" id="add-student-form" action="<?php echo BASE_URL . 'workshops/add'; ?>"
								      class="form">

									<div class="form-group">
										<div class='input-group date' id='dateTimePickerStart'>
											<span class="input-group-addon"><label for="dateTimePickerStart">
													Starts At</label></span>
											<input type='text' name='dateTimePickerStart' class="form-control" required/>
                                 <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
										</div>
									</div>

									<div class="form-group">
										<div class='input-group date' id='dateTimePickerEnd'>
											<span class="input-group-addon"><label for="dateTimePickerEnd">Ends At</label></span>
											<input type='text' name='dateTimePickerEnd' class="form-control" required/>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar">
										</span>
										</div>
									</div>

									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><label for="courseId">Course</label></span>
											<select id="courseId" name="courseId" class="form-control" required>
												<?php foreach ($courses as $course) {
													include(ROOT_PATH . "app/views/partials/course/select-options-view.html.php");
												}
												?>
											</select>
										</div>
									</div>

									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><label for="tutorId">Tutors</label></span>
											<select id="tutorId" name="tutorId" class="form-control" required>
												<?php foreach ($tutors as $tutor) {
													include(ROOT_PATH . "app/views/partials/tutor/select-options-view.html.php");
												}
												?>
											</select>
										</div>
									</div>

									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><label for="studentsIds">Students</label></span>
											<select id="studentsIds" name="studentsIds[]" class="form-control" multiple required>

												<?php
												foreach ($students as $student):
													include(ROOT_PATH . "app/views/partials/student/select-options-view.html.php");
												endforeach;
												?>

											</select>
										</div>
									</div>

									<div class="form-group">
										<div class="alert alert-warning">
											<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
											<strong>Warning!</strong><i> Make sure to specify instructors order insertion same as
												of that students order insertion.</i>
										</div>
									</div>


									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><label for="instructorId">Instructor</label></span>
											<select id="instructorId" name="instructorId[]" class="form-control" multiple required>
												<?php foreach ($instructors as $instructor) {
													include(ROOT_PATH . "app/views/partials/instructor/select-options-view.html.php");
												}
												?>
											</select>
										</div>
									</div>


									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><label for="termId">Term</label></span>
											<select id="termId" name="termId" class="form-control" required>
												<?php foreach ($terms as $term) {
													include(ROOT_PATH . "app/views/partials/term/select-options-view.html.php");
												}
												?>
											</select>
										</div>
									</div>

									<div class="form-group">
										<button type="submit" class="btn btn-block btn-primary">Add</button>
										<input type="hidden" name="hiddenSubmitPrsd" value="">
									</div>
								</form>
							</div>
							<!-- /.form-group -->

						</div>
					</div>


					<div class="col-md-7">
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
			defaultView: "agendaWeek",
			editable: false,
			droppable: false,
			events: [
				<?php	if(sizeof($appointments) <= 1){
					foreach($appointments as $appointment){
						$course = get($courses, $appointment[AppointmentFetcher::DB_COLUMN_COURSE_ID], CourseFetcher::DB_COLUMN_ID);
						include(ROOT_PATH . "app/views/partials/workshops/fullcalendar-single.php");
					}
				 }else{
				   for($i = 0; $i < (sizeof($appointments) - 1); $i++){
				   $course = get($courses, $appointments[$i][AppointmentFetcher::DB_COLUMN_COURSE_ID], CourseFetcher::DB_COLUMN_ID);
				      include(ROOT_PATH . "app/views/partials/workshops/fullcalendar-multi.php");
					}
					$lastAppointmentIndex = sizeof($appointments)-1;
					$id = $lastAppointmentIndex;
					$course = get($courses, $appointments[$i][AppointmentFetcher::DB_COLUMN_COURSE_ID], CourseFetcher::DB_COLUMN_ID);
					include(ROOT_PATH . "app/views/partials/workshops/fullcalendar-multi.php");

				}
				?>
			],
			timeFormat: 'H(:mm)' // uppercase H for 24-hour clock
		});

		$('#dateTimePickerStart').datetimepicker({
			defaultDate: moment(),
			minDate: moment().subtract('1', 'day'),
			minuteStepping: 10,
			daysOfWeekDisabled: [0, 6],
			sideBySide: true
		});
		var $startSessionMoment = moment($('#dateTimePickerStart').data("DateTimePicker").getDate());
		var dateEnd = moment().add(30, 'minutes');

		$('#dateTimePickerEnd').datetimepicker({
			defaultDate: dateEnd,
			minDate: $startSessionMoment,
			minuteStepping: 10,
			daysOfWeekDisabled: [0, 6],
			sideBySide: true
		});
		var $endSessionMoment = moment($('#dateTimePickerEnd').data("DateTimePicker").getDate());

		$("#dateTimePickerStart").on("dp.change", function (e) {
			var momentStart = $endSessionMoment;
			var momentEnd = momentStart.clone();
			var momentMinEnd = momentStart.clone();

			$('#dateTimePickerEnd').data("DateTimePicker").setMinDate(momentMinEnd.add(20, 'minutes'));
			$('#dateTimePickerEnd').data("DateTimePicker").setDate(momentEnd.add(30, 'minutes'));
		});


	})
	;
</script>