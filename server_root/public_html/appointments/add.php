<?php
require __DIR__ . '/../app/init.php';
$general->loggedOutProtect();

// redirect if user elevation is not that of secretary or admin
if ($user->isTutor()) {
	header('Location: ' . BASE_URL . "error-403");
	exit();
}


// viewers
$pageTitle = "New Appointment";
$section = "appointments";

try {
	$terms = TermFetcher::retrieveCurrTerm();
	$courses = sizeof($terms) > 0 ? CourseFetcher::retrieveForTerm($terms[0][TermFetcher::DB_COLUMN_ID]) : "";
	$instructors = InstructorFetcher::retrieveAll();
	$students = StudentFetcher::retrieveAll();

	$appointments = AppointmentFetcher::retrieveAll();

	if (isBtnAddStudentPrsd()) {
		$secretaryName = $user->getFirstName() . " " . $user->getLastName();
		Appointment::add($user, $_POST['dateTimePickerStart'], $_POST['dateTimePickerEnd'], $_POST['courseId'], $_POST['studentsIds'], $_POST['tutorId'], $_POST['instructorIds'], $_POST['termId'], $secretaryName);
		header('Location: ' . BASE_URL . 'appointments/add/success');
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
		New Appointment
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
	} else if (isModificationSuccess()) {
		?>
		<div class="alert alert-success">
			<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
			<strong>Workshop successfully created!</strong> <br/>
		</div>
	<?php } ?>

	<div class="portlet">
		<div class="row">

			<div class="col-lg-12 col-md-12">
				<div class="portlet-header">

					<h3 class="col-md-10 pull-left">
						<i class="fa fa-calendar"></i>
						Details

					</h3>

					<div class="col-md-1 pull-right">
						<button type="button" class="btn btn-default btn-xs fa fa-arrows-alt" id="maximize-inputs">
						</button>
					</div>
				</div>
				<!-- /.portlet-header -->

				<div class="portlet-content">

					<div class="form-group">
						<form method="post" id="add-student-form" action="<?php echo BASE_URL . 'appointments/add'; ?>"
						      class="form">


							<div class="form-group" id="student-instructor">
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"><label for="studentId1">Student</label></span>
										<select id="studentId1" name="studentsIds[]" class="form-control" required>
											<option></option>
											<?php
											foreach ($students as $student):
												include(ROOT_PATH . "views/partials/student/select-options-view.html.php");
											endforeach;
											?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class="input-group">

                                        <span class="input-group-addon"><label
		                                        for="instructorId1">Instructor</label></span>
										<select id="instructorId1" name="instructorIds[]" class="form-control" required>
											<option></option>
											<?php foreach ($instructors as $instructor) {
												include(ROOT_PATH . "views/partials/instructor/select-options-view.html.php");
											}
											?>
										</select>
									</div>
								</div>
							</div>
							<hr/>

							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon"><label for="courseId"
									                                       id="label-course-text">Course</label></span>
									<select id="courseId" name="courseId" class="form-control" required>
										<option></option>
										<?php foreach ($courses as $course) {
											include(ROOT_PATH . "views/partials/course/select-options-view.html.php");
										}
										?>
									</select>
								</div>
							</div>


							<div class="form-group">
								<div class="input-group">
										<span class="input-group-addon"><label id="label-instructor-text"
										                                       for="tutorId">Tutors</label></span>
									<select id="tutorId" name="tutorId" class="form-control" required>
										<option></option>
									</select>
									<input id="value" type="hidden" style="width:300px"/>
								</div>
							</div>


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
                                        <span class="input-group-addon"><label for="dateTimePickerEnd">Ends
		                                        At</label></span>
									<input type='text' name='dateTimePickerEnd' class="form-control" required/>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar">
										</span>
								</div>
							</div>


							<div class="form-group">
								<div class="input-group">
									<button type="button" class="btn btn-default btn-sm addButton"
									        data-template="textbox">
										Add One More Student
									</button>
								</div>
							</div>

							<div class="form-group hide" id="textboxTemplate">
								<div class="input-group">
									<button type="button" class="btn btn-default btn-sm removeButton">Remove
									</button>
								</div>
							</div>
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon"><label for="termId">Term</label></span>
									<select id="termId" name="termId" class="form-control" required>
										<?php
										foreach ($terms as $term) {
											include(ROOT_PATH . "views/partials/term/select-options-view.html.php");
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


			<div class="col-lg-12 col-md-12">
				<div class="portlet-header">

					<h3>
						<i class="fa fa-calendar"></i>
							<span id="calendar-title">
								<i class='fa fa-circle-o-notch fa-spin'></i>
							</span>
						<button id="show-only-working-hours" type="button" class="btn btn-primary btn-xs btn-secondary">
							Working Hours
						</button>
						<button id="show-only-appointments" type="button" class="btn btn-primary btn-xs">Appointments
						</button>
					</h3>

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

<?php include ROOT_PATH . "views/footer.php"; ?>
</div>
<!-- #wrapper<!-- #content -->


<?php include ROOT_PATH . "views/assets/footer_common.php"; ?>
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

	var $courseId = $("#courseId");
	var $termId = $("#termId");
	var $tutorId = $("#tutorId");
	var $dateTimePickerStart = $('#dateTimePickerStart');
	var $dateTimePickerEnd = $('#dateTimePickerEnd');
	var $dateTimePickerEnd2 = $('#dateTimePickerEnd');
	var $instructorId = $("#instructorId1");
	var $studentId = $("#studentId1");
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
	$courseId.select2({
		placeholder: "Select a course",
		allowClear: false
	});
	$courseId.click(function () {
		try {
			retrieveTutors();
		}
		catch (err) {
			$tutorId.select2({
				placeholder: err.message
			});
		}
	});
	$termId.click(function () {
		try {
			retrieveCourses();
			retrieveTutors();
			reloadCalendar('term_change');
		}
		catch (err) {
			// clear options
			$tutorId.empty(); // remove old options
			// add new options
			$tutorId.append("<option></option>");
			$tutorId.select2({
				placeholder: err.message
			});
		}
	});

	var startDateDefault;
	if (moment().minute() >= 30) {
		startDateDefault = moment().add('1', 'hours');
		startDateDefault.minutes(0);
	} else {
		startDateDefault = moment();
		startDateDefault.minutes(30);
	}

	var minimumStartDate = startDateDefault.clone();
	minimumStartDate.subtract('31', 'minutes')
	var minimumMaxDate = moment().add('14', 'day');

	var endDateDefault = startDateDefault.clone();
	endDateDefault.add('30', 'minutes');
	var minimumEndDate = endDateDefault.clone();
	minimumEndDate.subtract('31', 'minutes');

	$dateTimePickerStart.datetimepicker({
		defaultDate: startDateDefault,
		<?php if(!$user->isAdmin()): ?>
		minDate: minimumStartDate,
		<?php endif; ?>
		maxDate: minimumMaxDate,
		minuteStepping: 30,
		daysOfWeekDisabled: [0, 6],
		sideBySide: true,
		strict: true
	});
	$dateTimePickerEnd.datetimepicker({
		defaultDate: endDateDefault,
		<?php if(strcmp($user->getId(), "9") !== 0): ?>
		minDate: minimumEndDate,
		<?php endif; ?>
		minuteStepping: 30,
		daysOfWeekDisabled: [0, 6],
		sideBySide: true,
		strict: true
	});
	$dateTimePickerStart.on("dp.change", function (e) {
		var newEndDateDefault = $('#dateTimePickerStart').data("DateTimePicker").getDate().clone();

		newEndDateDefault.add('30', 'minutes');
		var newMinimumEndDate = newEndDateDefault.clone();
		newMinimumEndDate.subtract('31', 'minutes')

		$dateTimePickerEnd2.data("DateTimePicker").setMinDate(newMinimumEndDate);
		$dateTimePickerEnd2.data("DateTimePicker").setDate(newEndDateDefault);
	});
	$termId.select2();
	$instructorId.select2({
		placeholder: "Select an instructor"
	});
	$studentId.select2({
		placeholder: "Select one"
	});
	$tutorId.select2({
		placeholder: "First select a course"
	});

	$tutorId.click(function () {
		try {
			reloadCalendar('single_tutor_appointment_and_schedule');
		} catch (err) {
			// clear options
			$tutorId.empty().append("<option></option>");
			$tutorId.select2({
				placeholder: err.message
			});
		}
	});

	$("#show-only-working-hours").on('click', function () {
		reloadCalendar("working_hours_only");
	});

	$("#show-only-appointments").on('click', function () {
		reloadCalendar("appointments_only");
	});

	function reloadCalendar(choice) {
		var calendar = document.getElementById('appointments-schedule-calendar');
		var spinner;

		$calendarTitle.text("");
//		$calendarTitle.append("<i class='fa fa-circle-o-notch fa-spin'></i>");

		if ($termId.val() === null || !$termId.select2('val').match(/^[0-9]+$/)) throw new Error("Term is missing");

		var data = [];
		var singleTutorScheduleCalendar = {
			url: "<?php echo "http://" . $_SERVER['SERVER_NAME']; ?>/api/schedules",
			type: 'GET',
			dataType: "json",
			data: {
				action: 'single_tutor_working_hours',
				tutorId: $tutorId.select2('val'),
				termId: $termId.select2('val')
			},
			error: function (xhr, status, error) {
				$calendarTitle.text("there was an error while retrieving schedules");
				console.log(xhr.responseText);
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
		var singleTutorAppointmentsCalendar = {
			url: "<?php echo "http://" . $_SERVER['SERVER_NAME']; ?>/api/appointments",
			type: 'GET',
			dataType: "json",
			data: {
				action: 'single_tutor_working_hours',
				tutorId: $tutorId.select2('val'),
				termId: $termId.select2('val')
			},
			error: function (xhr, status, error) {
				$calendarTitle.text("there was an error while fetching tutor's appointments");
				console.log(xhr.responseText);
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
		var allSchedulesCalendar = {
			url: "<?php echo "http://" . $_SERVER['SERVER_NAME']; ?>/api/schedules",
			type: 'GET',
			dataType: "json",
			data: {
				action: 'all_tutors_working_hours',
				termId: $termId.val()
			},
			error: function (xhr, status, error) {
				$('#calendar-title').text("there was an error while retrieving schedules");
				console.log(xhr.responseText);

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
		var allAppointmentsCalendar = {
			url: "<?php echo "http://" . $_SERVER['SERVER_NAME']; ?>/api/appointments",
			type: 'GET',
			dataType: "json",
			data: {
				action: 'all_tutors_appointments',
				termId: $termId.val()
			},
			error: function (xhr, status, error) {
				$('#calendar-title').text("there was an error while fetching all appointments");
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
		$calendar.fullCalendar('removeEventSource', singleTutorScheduleCalendar);
		$calendar.fullCalendar('removeEventSource', singleTutorAppointmentsCalendar);
		$calendar.fullCalendar('removeEventSource', allSchedulesCalendar);
		$calendar.fullCalendar('removeEventSource', allAppointmentsCalendar);

		switch (choice) {
			case 'all_appointments_schedule':
			case 'term_change':
				$calendar.fullCalendar('addEventSource', allAppointmentsCalendar);
				$calendar.fullCalendar('addEventSource', allSchedulesCalendar);
				break;
			case 'single_tutor_appointment_and_schedule':
				if (!$tutorId.select2('val').match(/^[0-9]+$/)) throw new Error("Tutor is missing");
				if (!$courseId.select2("val").match(/^[0-9]+$/)) throw new Error("Course is missing");
				if (!$termId.select2("val").match(/^[0-9]+$/)) throw new Error("Course is missing");
				$calendar.fullCalendar('addEventSource', singleTutorScheduleCalendar);
				$calendar.fullCalendar('addEventSource', singleTutorAppointmentsCalendar);
				break;
			case 'working_hours_only':
				if (!$tutorId.select2('val').match(/^[0-9]+$/)) {
					$calendar.fullCalendar('addEventSource', allSchedulesCalendar);
				} else {
					$calendar.fullCalendar('addEventSource', singleTutorScheduleCalendar);
				}
				break;
			case 'appointments_only':
				if (!$tutorId.select2('val').match(/^[0-9]+$/)) {
					$calendar.fullCalendar('addEventSource', allAppointmentsCalendar);
				} else {
					$calendar.fullCalendar('addEventSource', singleTutorAppointmentsCalendar);
				}
				break;
			default:
				break;
		}

		$calendar.fullCalendar('refetchEvents');

		if (!$tutorId.select2('val').match(/^[0-9]+$/)) {
			$calendarTitle.text("All");
		} else {
			$calendarTitle.text($tutorId.select2('data').text);
		}

	}

	function loadAllCalendars() {
		try {
			reloadCalendar('single_tutor_appointment_and_schedule');
		} catch (err) {
			// clear options
			$tutorId.empty().append("<option></option>");
			$tutorId.select2({
				placeholder: err.message
			});
		}

		try {
			reloadCalendar('all_appointments_schedule');
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

	$('.addButton').on('click', function () {


		var index = $(this).data('index');
		if (!index) {
			index = 1;
			$(this).data('index', 1);
		}
		index++;
		$(this).data('index', index);

		var template = $(this).attr('data-template'),
			$templateEle = $('#' + template + 'Template'),
			$row = $templateEle.clone().removeAttr('id').insertBefore($templateEle).removeClass('hide'),
			$el = $row.find('input').eq(0).attr('name', template + '[]');

		var newStudentId = 'studentId' + index;
		var newInstructorId = 'instructorId' + index;

		var $curStudentRow = $('#student-instructor');
		$studentId.select2("destroy");
		$instructorId.select2("destroy");

		var newRow = $curStudentRow.clone();
		$('#studentId1', newRow).attr('id', newStudentId);
		$('#instructorId1', newRow).attr('id', newInstructorId);

		$row.prepend(newRow);

		$("#studentId1").select2({
			placeholder: "Select one"
		});
		$("#instructorId1").select2({
			placeholder: "Select one"
		});
		$("#" + newStudentId).select2({
			placeholder: "Select one"
		});
		$("#" + newInstructorId).select2({
			placeholder: "Select one"
		});

		$row.on('click', '.removeButton', function (e) {
			$row.remove();
			newRow.remove();
		});
	});

	function retrieveTutors() {
		var courseId = $("#courseId").select2("val");
		var termId = $("#termId").select2("val");


		if (!courseId.match(/^[0-9]+$/)) throw new Error("Course is missing");
		if (!termId.match(/^[0-9]+$/)) throw new Error("Term is missing");

		$('#label-instructor-text').text("");
		$('#label-instructor-text').append("<i class='fa fa-circle-o-notch fa-spin'></i>");

		var data = {
			"action": "tutor_has_courses",
			"courseId": courseId,
			"termId": termId
		}
		data = $(this).serialize() + "&" + $.param(data);

		$.ajax({
			type: "GET",
			dataType: "json",
			url: "<?php echo "http://" . $_SERVER['SERVER_NAME']; ?>/api/courses",
			data: data,
			success: function (inData) {
				// reset label test
				$('#label-instructor-text').text("Tutors");

				// prepare new data for options
				var newTutors = [];
				$.each(inData, function (idx, obj) {
					newTutors.push({
						id: obj.id,
						text: obj.f_name + " " + obj.l_name
					});
				});

				// clear options
				var $el = $("#tutorId");
				$el.empty(); // remove old options

				// add new options
				$el.append("<option></option>");
				$.each(newTutors, function (key, value) {
					$el.append($("<option></option>")
						.attr("value", value.id).text(value.text));
				});

				var placeHolder = jQuery.isEmptyObject(inData) ? "No tutors found" : "Select a tutor"
				$el.select2({
					placeholder: placeHolder,
					allowClear: false
				});
				<?php 	if (isBtnAddStudentPrsd()) : ?>
				$tutorId.select2("val", <?php echo $_POST['tutorId']; ?>);
				<?php endif; ?>
			},
			error: function (e) {
				$('#label-instructor-text').text("Connection errors.");
			}
		});
	}

	function retrieveCourses() {
		var termId = $("#termId").select2("val");
		if (!termId.match(/^[0-9]+$/)) throw new Error("Term is missing");

		$('#label-course-text').text("");
		$('#label-course-text').append("<i class='fa fa-circle-o-notch fa-spin'></i>");

		var data = {
			"action": "courses_on_term",
			"termId": termId
		}
		data = $(this).serialize() + "&" + $.param(data);

		$.ajax({
			type: "GET",
			dataType: "json",
			url: "<?php echo "http://" . $_SERVER['SERVER_NAME']; ?>/api/courses",
			data: data,
			success: function (inData) {
				console.log(inData);
				// reset label test
				$('#label-course-text').text("Courses");

				// prepare new data for options
				var newCourses = [];
				$.each(inData, function (idx, obj) {
					newCourses.push({
						id: obj.id,
						text: obj.code + " - " + obj.name
					});
				});

				// clear options
				var $el = $courseId;
				$el.empty(); // remove old options

				// add new options
				$el.append("<option></option>");
				$.each(newCourses, function (key, value) {
					$el.append($("<option></option>")
						.attr("value", value.id).text(value.text));
				});

				var placeHolder = jQuery.isEmptyObject(inData) ? "No courses found" : "Select one"
				$el.select2({
					placeholder: placeHolder,
					allowClear: false
				});
				<?php 	if (isBtnAddStudentPrsd()) : ?>
				$tutorId.select2("val", <?php echo $_POST['tutorId']; ?>);
				<?php endif; ?>
			},
			error: function (e) {
				$('#label-course-text').text("Connection errors.");
			}
		});
	}

	<?php 	if (isBtnAddStudentPrsd()) : ?>
	$courseId.select2("val", <?php echo $_POST['courseId']; ?>);
	retrieveTutors();
	try {
		reloadCalendar('single_tutor_appointment_and_schedule');
	} catch (err) {
		// clear options
		$tutorId.empty().append("<option></option>");
		$tutorId.select2({
			placeholder: err.message
		});
	}
	$dateTimePickerStart.data("DateTimePicker").setDate('<?php echo $_POST['dateTimePickerStart']; ?>');
	$dateTimePickerEnd.data("DateTimePicker").setDate('<?php echo $_POST['dateTimePickerEnd']; ?>');
	<?php endif; ?>
});
</script>

</body>
</html>