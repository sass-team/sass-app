<?php
require __DIR__ . '/../../app/init.php';
$general->loggedOutProtect();


// viewers
$pageTitle = "Appointments";
$section = "appointments";

try {
	$courses = CourseFetcher::retrieveAll($db);

	if ($user->isTutor()) {
		// retrieve all appoints for tutor
		$appointments = TutorFetcher::retrieveAllAppointments($db, $user->getId());

	} else {
		$terms = TermFetcher::retrieveAllButCur($db);
		$instructors = InstructorFetcher::retrieveAll($db);
		$students = StudentFetcher::retrieveAll($db);
		$tutors = TutorFetcher::retrieveAll($db);
		$appointments = AppointmentFetcher::retrieveAll($db);
	}


} catch (Exception $e) {
	$errors[] = $e->getMessage();
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

// viewers
$pageTitle = "All Appointments";
$section = "appointments";
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

			<div class="portlet">

				<div class="row">
					<!-- /.portlet-header -->

					<?php if (empty($errors) === false): ?>
						<div class="alert alert-danger">
							<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
							<strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>'; ?>
						</div>
					<?php endif; ?>

					<div class="col-md-12">
						<div class="portlet-header">
							<h3>
								<i class="fa fa-calendar"></i>
								Date Picker
							</h3>
						</div>
						<!-- /.portlet-header -->
						<div class="portlet-content">

							<div id="appointments-calendar"></div>
						</div>

					</div>
				</div>
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

<?php include ROOT_PATH . "app/views/assets/footer_common.php"; ?>

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


		$("#appointments-calendar").fullCalendar({
			header: {
				left: 'prev,next',
				center: 'title',
				right: 'agendaWeek,month,agendaDay'
			},
			weekends: false, // will hide Saturdays and Sundays
			defaultView: "month",
			editable: false,
			droppable: false,
			events: [
				<?php	if(sizeof($appointments) <= 1){
					foreach($appointments as $appointment){
						$course = get($courses, $appointment[AppointmentFetcher::DB_COLUMN_COURSE_ID], CourseFetcher::DB_COLUMN_ID);
						include(ROOT_PATH . "app/views/partials/appointments/fullcalendar-single.php");
					}
				 }else{
				   for($i = 0; $i < (sizeof($appointments) - 1); $i++){
				   $course = get($courses, $appointments[$i][AppointmentFetcher::DB_COLUMN_COURSE_ID], CourseFetcher::DB_COLUMN_ID);
				      include(ROOT_PATH . "app/views/partials/appointments/fullcalendar-multi.php");
					}
					$lastAppointmentIndex = sizeof($appointments)-1;
					$id = $lastAppointmentIndex;
					$course = get($courses, $appointments[$i][AppointmentFetcher::DB_COLUMN_COURSE_ID], CourseFetcher::DB_COLUMN_ID);
					include(ROOT_PATH . "app/views/partials/appointments/fullcalendar-multi.php");

				}
				?>
			],
			timeFormat: 'H(:mm)' // uppercase H for 24-hour clock
		});


	})
	;
</script>

</body>
</html>
