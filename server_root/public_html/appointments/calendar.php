<?php
require __DIR__ . '/../app/init.php';
$general->loggedOutProtect();

$section = "appointments";

try {
	if (isUrlRequestingSingleTutorAppointments()) {
		//TODO: change appointmentId to tutorId view appointments
//		$pageTitle = "Single Appointments";
//		$appointmentId = $_GET['appointmentId'];
//
//		// redirect if user elevation is not that of secretary or admin
//		if ($user->isTutor() && !Tutor::hasAppointmentWithId( $user->getId(), $appointmentId)) {
//			header('Location: ' . BASE_URL . "error-403");
//			exit();
//		}
//
//		$students = Appointment::getAllStudentsWithAppointment( $appointmentId);
//		$course = Course::get( $students[0][AppointmentFetcher::DB_COLUMN_COURSE_ID]);
//		$term = TermFetcher::retrieveSingle( $students[0][AppointmentFetcher::DB_COLUMN_TERM_ID]);
//
//		$tutorName = $students[0][UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_FIRST_NAME] . " " .
//			$students[0][UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_LAST_NAME];
//		$startTime = $students[0][AppointmentFetcher::DB_COLUMN_START_TIME];
//		$endTime = $students[0][AppointmentFetcher::DB_COLUMN_END_TIME];


	} else if (isUrlRequestingAllAppointments()) {
		$pageTitle = "All appointments";

		$terms = TermFetcher::retrieveCurrTerm();

		if ($user->isTutor()) {
			$pageTitle = "All my appointments";
			$requestedTutorId = $user->getId();
		} else {
			$requestedTutorName = $user->getFirstName() . " " . $user->getLastName();
			$requestedTutorId = $user->getId();
		}
	} else {
		header('Location: ' . BASE_URL . "error-403");
		exit();
	}


} catch (Exception $e) {
	$errors[] = $e->getMessage();
}


function isUrlRequestingSingleTutorAppointments() {
	return isset($_GET['appointmentId']) && preg_match("/^[0-9]+$/", $_GET['appointmentId']);
}


function isUrlRequestingAllAppointments() {
	return sizeof($_GET) === 0;
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
		<i class="fa fa-calendar"></i>
		<?php echo $pageTitle; ?>
	</h1>


</div>
<!-- #content-header -->

<div id="content-container">

	<div class="portlet">

		<?php if (isUrlRequestingSingleTutorAppointments()) { ?>

		<div class="col-md-12">
			<div class="portlet-header">

				<h3>
					<i class="fa fa-calendar"></i>
					<span id="calendar-title">
						Details
					</span>
				</h3>


			</div>

		</div>
		<!-- /.portlet-header -->

		<div class="portlet-content">

			<div class="form-group">

				<div class="form-group">

					<div class="row">
						<div class="col-md-6 col-sm-6">
							<h4>Student</h4>

							<table class="table">
								<tbody>
								<?php foreach ($students as $student):
									include(ROOT_PATH . "views/partials/student/name-table-data-view.html.php");
								endforeach; ?>
								</tbody>
							</table>
						</div>
						<!-- /.col -->

						<div class="col-md-6 col-sm-6">

							<h4>Instructor</h4>

							<table class="table">
								<tbody>
								<?php foreach ($students as $student):
									$instructor = $student;
									include(ROOT_PATH . "views/partials/instructor/name-table-data-view.html.php");
								endforeach; ?>
								</tbody>
							</table>
						</div>
						<!-- /.col -->


					</div>
					<!-- /.row -->
				</div>

				<div class="form-group">

					<div class="row">
						<div class="col-md-6 col-sm-6">
							<h4>Course</h4>
						</div>
						<div class="col-md-6 col-sm-6">
							<input type='text' value="<?php echo $course[CourseFetcher::DB_COLUMN_CODE] . " " .
								$course[CourseFetcher::DB_COLUMN_NAME]; ?>" name='dateTimePickerStart' class="
                                       form-control" disabled/>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">

						<div class="col-md-6 col-sm-6">
							<h4>Tutor</h4>
						</div>
						<div class="col-md-6 col-sm-6">
							<input type='text' value="<?php echo $tutorName; ?>" name='dateTimePickerStart'
							       class="
                                       form-control" disabled/>
						</div>
					</div>
				</div>

				<div class="form-group">

					<div class="row">

						<div class="col-md-6 col-sm-6">
							<h4>Starting Date</h4>
						</div>


						<div class="col-md-6 col-sm-6">
							<input type='text' value="<?php echo $startDateTime; ?>" class="form-control" disabled/>
						</div>
					</div>
				</div>
				<div class="form-group">

					<div class="row">

						<div class="col-md-6 col-sm-6">
							<h4>Ending Date</h4>
						</div>


						<div class="col-md-6 col-sm-6">
							<input type='text' value="<?php echo $endDateTime; ?>"
							       class="form-control" disabled/>
						</div>
					</div>
				</div>
				<div class="form-group">

					<div class="row">

						<div class="col-md-6 col-sm-6">
							<h4>Term</h4>
						</div>


						<div class="col-md-6 col-sm-6">
							<input type='text' value="<?php echo $term[TermFetcher::DB_COLUMN_NAME]; ?>" class="
                                       form-control" disabled/>
						</div>
					</div>
				</div>

				<div class="form-group">
					<?php
					if (empty($errors) === false) {
						?>
						<div class="alert alert-danger">
							<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
							<strong>Oh
								snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>';
							?>
						</div>
					<?php
					}  ?>

				</div>
			</div>

		</div>
		<!-- /.form-group -->

	</div>
	<!-- /.portlet -->
	<?php } else { ?>
		<div class="row">

			<div class="col-md-12">
				<div class="portlet-header">
					<h3>
						<i class="fa fa-calendar"></i>

							<span id="calendar-title">
								Details
							</span>

						<span class="label label-danger">Working Hours</span>

					</h3>

					<div class="col-md-6">
						<select id="termId" name="termId" class="form-control" required>
							<?php
							foreach ($terms as $term) {
								include(ROOT_PATH . "views/partials/term/select-options-view.html.php");
							}
							?>
						</select>
					</div>

				</div>
				<!-- /.portlet-header -->

				<div class="portlet-content">

					<div id="appointments-calendar"></div>
				</div>
			</div>
			<!-- ./col -->
		</div>
	<?php } ?>


</div>
<!-- /.portlet -->

</div>
<!-- /#content-container -->

<div id="push"></div>

</div>
<!-- #content -->

<?php include ROOT_PATH . "views/footer.php"; ?>
</div>
<!-- #wrapper<!-- #content -->


<?php include ROOT_PATH . "views/assets/footer_common.php"; ?>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/autosize/jquery.autosize.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/select2/select2.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/spin/spin.min.js"></script>


<script
	src="<?php echo BASE_URL; ?>assets/js/plugins/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js">
</script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/fullcalendar/fullcalendar.min.js"></script>
<script type="text/javascript"
        src="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.1/js/bootstrapValidator.min.js"></script>

<script type="text/javascript">
	// http://momentjs.com/docs/#/manipulating/add/
	// http://eonasdan.github.io/bootstrap-datetimepicker
	$(function () {
		var $calendar = $("#appointments-calendar");
		var $termId = $('#termId');
		$termId.select2();
		var $calendarTitle = $('#calendar-title');
		var opts = {
			lines: 13, // The number of lines to draw
			length: 20, // The length of each line
			width: 10, // The line thickness
			radius: 30, // The radius of the inner circle
			corners: 1, // Corner roundness (0..1)
			rotate: 0, // The rotation offset
			direction: 1, // 1: clockwise, -1: counterclockwise
			color: '#000', // #rgb or #rrggbb or array of colors
			speed: 2.2, // Rounds per second
			trail: 60, // Afterglow percentage
			shadow: false, // Whether to render a shadow
			hwaccel: false, // Whether to use hardware acceleration
			className: 'spinner', // The CSS class to assign to the spinner
			zIndex: 2e9, // The z-index (defaults to 2000000000)
			top: '50%', // Top position relative to parent
			left: '50%' // Left position relative to parent
		};

		$termId.click(function () {
			reloadCalendar('term_change');
		});

		function loadAllCalendars() {
			try {
				reloadCalendar();
			} catch (err) {
				$calendarTitle.text(err);
			}
		}

		function reloadCalendar(choice) {
			var spinner;
			var calendar = document.getElementById('appointments-calendar');

			if ($termId.val() === null || !$termId.select2('val').match(/^[0-9]+$/)) throw new Error("Term is missing");

			var singleTutorAppointmentsCalendar = {
				url: "<?php echo "http://" . $_SERVER['SERVER_NAME']; ?>/api/appointments",
				type: 'GET',
				dataType: "json",
				data: {
					action: 'single_tutor_appointments',
					tutorId: <?php echo $requestedTutorId; ?>,
					termId: $termId.select2('val')
				},
				error: function (xhr, status, error) {
					$calendarTitle.text("Could not retrieve appointments.");
					console.log(error);
				},
				success: function () {
					$calendarTitle.text();
				},
				beforeSend: function () {
					if (spinner == null) {
						spinner = new Spinner(opts).spin(calendar);
					}

				},
				complete: function () {
					if (spinner != null) {
						spinner.stop();
						spinner = null;
					}
				}
			};

			var allTutorsAppointmentsCalendar = {
				url: "<?php echo "http://" . $_SERVER['SERVER_NAME']; ?>/api/appointments",
				type: 'GET',
				dataType: "json",
				data: {
					action: 'all_tutors_appointments',
					termId: $termId.select2('val')
				},
				error: function (xhr, status, error) {
					$('#calendar-title').text("Could not retrieve appointments.");
					console.log(xhr.responseText);
				},
				success: function () {
					$calendarTitle.text("All tutors appointments");
				},
				beforeSend: function () {
					if (spinner == null) {
						spinner = new Spinner(opts).spin(calendar);
					}

				},
				complete: function () {
					if (spinner != null) {
						spinner.stop();
						spinner = null;
					}
				}
			};

			$calendar.fullCalendar('removeEventSource', allTutorsAppointmentsCalendar);
			$calendar.fullCalendar('removeEventSource', singleTutorAppointmentsCalendar);

			switch (choice) {
				case 'all_appointments':
				case 'term_change':
					$calendar.fullCalendar('addEventSource', allTutorsAppointmentsCalendar);
					break;
				case 'single_tutor_appointment':
					$calendar.fullCalendar('addEventSource', singleTutorAppointmentsCalendar);
					break;
				default:
					break;
			}
			$calendar.fullCalendar('refetchEvents');

		}


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
			eventSources: []
		});

		<?php if(isUrlRequestingAllAppointments()){
			if( $user->isTutor()) {?>
		reloadCalendar('single_tutor_appointment');
		<?php }else { ?>
		reloadCalendar('all_appointments');
		<?php }?>
		<?php }?>

	});
</script>

</body>
</html>