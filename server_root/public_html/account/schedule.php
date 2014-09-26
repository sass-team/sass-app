<?php
require __DIR__ . '/../../app/init.php';
$general->loggedOutProtect();

// viewers
$pageTitle = "New Appointment";
$section = "appointments";

try {
	$terms = TermFetcher::retrieveCurrTerm($db);
	$students = StudentFetcher::retrieveAll($db);

	$appointments = AppointmentFetcher::retrieveAll($db);


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
				Schedules
			</h1>

		</div>
		<!-- #content-header -->

		<div id="content-container">
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
			} ?>

			<div class="portlet">
				<div class="row">
					<div class="col-md-12">
						<div class="portlet-header">

							<h3>
								<i class="fa fa-calendar"></i>Details
								<span id="calendar-title"><i class='fa fa-circle-o-notch fa-spin'></i></span>
								<span class="label label-secondary">Working Hours</span>
							</h3>

							<div class="col-md-6">
								<select id="termId" name="termId" class="form-control" required>
									<?php
									foreach ($terms as $term) {
										include(ROOT_PATH . "app/views/partials/term/select-options-view.html.php");
									}
									?>
								</select>
							</div>
						</div>
						<!-- /.portlet-header -->

						<div class="portlet-content" id="calendar-portlet">

							<div id="appointments-schedule-calendar"></div>
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


<?php include ROOT_PATH . "app/views/assets/footer_common.php"; ?>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/spin/spin.min.js"></script>

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

		var $termId = $("#termId");
		var $calendarTitle = $('#calendar-title');
		var $calendar = $("#appointments-schedule-calendar");
		var $maximizeChoices = $("#maximize-inputs");
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

		$maximizeChoices.on("click", function () {

		});

		$termId.select2();
		$termId.click(function () {
			reloadCalendar();
		});


		$("#show-only-working-hours").on('click', function () {
			reloadCalendar("working_hours_only");
		});

		$("#show-only-appointments").on('click', function () {
			reloadCalendar("appointments_only");
		});

		function reloadCalendar(choice) {
			var calendar = document.getElementById('appointments-schedule-calendar');
			var spinner = new Spinner(opts).spin(calendar);

			$calendarTitle.text("");
			$calendarTitle.append("<i class='fa fa-circle-o-notch fa-spin'></i>");

			if ($termId.val() === null || !$termId.select2('val').match(/^[0-9]+$/)) throw new Error("Term is missing");

			var data = [];
			var singleTutorScheduleCalendar = {
				url: "<?php echo "http://" . $_SERVER['SERVER_NAME']; ?>/api/schedules",
				type: 'GET',
				dataType: "json",
				data: {
					action: 'single_tutor_working_hours',
					tutorId: <?php echo $user->getId(); ?>,
					termId: $termId.select2('val')
				},
				error: function (xhr, status, error) {
					$calendarTitle.text("there was an error while retrieving schedules");
					console.log(xhr.responseText);
				}
			};

			$calendar.fullCalendar('removeEventSource', singleTutorScheduleCalendar);
			$calendar.fullCalendar('addEventSource', singleTutorScheduleCalendar);
			$calendar.fullCalendar('refetchEvents');

			spinner.stop();
			$calendarTitle.text("");
		}

		function loadAllCalendars() {
			try {
				reloadCalendar();
			} catch (err) {
				$calendarTitle.text(err);
			}
		}

		$calendar.fullCalendar({
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

		loadAllCalendars();

	});
</script>

</body>
</html>