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
$section = "appointments";

try {
	$terms = TermFetcher::retrieveAllButCur($db);
	$curTerm = TermFetcher::retrieveCurrent($db);
	$courses = CourseFetcher::retrieveAll($db);
	$instructors = InstructorFetcher::retrieveAll($db);
	$students = StudentFetcher::retrieveAll($db);

	$appointments = AppointmentFetcher::retrieveAll($db);

	if (isBtnAddStudentPrsd()) {
		Appointment::add($db, $_POST['dateTimePickerStart'], $_POST['dateTimePickerEnd'], $_POST['courseId'],
			$_POST['studentsIds'], $_POST['tutorId'], $_POST['instructorId'], $_POST['termId']);
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
								<form method="post" id="add-student-form" action="<?php echo BASE_URL . 'appointments/add'; ?>"
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
												<option></option>
												<?php foreach ($courses as $course) {
													include(ROOT_PATH . "app/views/partials/course/select-options-view.html.php");
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
										<div class="input-group">
											<span class="input-group-addon"><label for="studentsIds">Students</label></span>
											<select id="studentsIds" name="studentsIds" class="form-control" required>
												<option></option>
												<?php
												foreach ($students as $student):
													include(ROOT_PATH . "app/views/partials/student/select-options-view.html.php");
												endforeach;
												?>
											</select>
											<span class="input-group-addon"><label for="instructorId">Instructor</label></span>
											<select id="instructorId" name="instructorId" class="form-control" required>
												<option></option>
												<?php foreach ($instructors as $instructor) {
													include(ROOT_PATH . "app/views/partials/instructor/select-options-view.html.php");
												}
												?>
											</select>
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
											<button type="button" class="btn btn-default btn-sm removeButton">Remove</button>
										</div>
									</div>

									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><label for="termId">Term</label></span>
											<select id="termId" name="termId" class="form-control" required>
												<?php
												$term = $curTerm;
												include(ROOT_PATH . "app/views/partials/term/select-options-view.html.php");
												foreach ($terms as $term) {
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

							<div id="appointments-calendar"></div>
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

<script src="<?php echo BASE_URL; ?>assets/js/plugins/autosize/jquery.autosize.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/select2/select2.js"></script>


<script
	src="<?php echo BASE_URL; ?>assets/js/plugins/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js">
</script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/fullcalendar/fullcalendar.min.js"></script>
<script type="text/javascript"
        src="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.1/js/bootstrapValidator.min.js"></script>

<script type="text/javascript">
$(function () {
	// http://momentjs.com/docs/#/manipulating/add/
	// http://eonasdan.github.io/bootstrap-datetimepicker
	moment().format();

	$("#courseId").select2({
		placeholder: "Select a course",
		allowClear: false
	});
	$("#courseId").click(function () {
		var courseId = $(this).select2("val");
		var termId = $("#termId").select2("val");

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

			},
			error: function (e) {
				$('#label-instructor-text').text("Connection erros.");
			}
		});
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
	minimumEndDate.subtract('31', 'minutes')

	$('#dateTimePickerStart').datetimepicker({
		defaultDate: startDateDefault,
		minDate: minimumStartDate,
		maxDate: minimumMaxDate,
		minuteStepping: 30,
		daysOfWeekDisabled: [0, 6],
		sideBySide: true,
		strict: true
	});
	$('#dateTimePickerEnd').datetimepicker({
		defaultDate: endDateDefault,
		minDate: minimumEndDate,
		minuteStepping: 30,
		daysOfWeekDisabled: [0, 6],
		sideBySide: true,
		strict: true
	});
	$("#dateTimePickerStart").on("dp.change", function (e) {
		var newEndDateDefault = $('#dateTimePickerStart').data("DateTimePicker").getDate().clone();

		newEndDateDefault.add('30', 'minutes');
		var newMinimumEndDate = newEndDateDefault.clone();
		newMinimumEndDate.subtract('31', 'minutes')

		$('#dateTimePickerEnd').data("DateTimePicker").setMinDate(newMinimumEndDate);
		$('#dateTimePickerEnd').data("DateTimePicker").setDate(newEndDateDefault);
	});
	$("#termId").select2();
	$("#instructorId").select2({
		placeholder: "Select an instructor"
	});
	$("#studentsIds").select2({
		placeholder: "Select at least one"
	});
	$("#tutorId").select2({
		placeholder: "First select a course"
	});
	$("#tutorId").click(function () {

	});

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
			<?php if(isset($appointments) && !empty($appointments)){
				if(sizeof($appointments) <= 1){
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
			}?>
		],
		timeFormat: 'H(:mm)' // uppercase H for 24-hour clock
	});

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
		$('#defaultForm').bootstrapValidator('addField', $el);

		// Set random value for checkbox and textbox
		if ('checkbox' == $el.attr('type') || 'radio' == $el.attr('type')) {
			$el.val('Choice #' + index)
				.parent().find('span.lbl').html('Choice #' + index);
		} else {
			$el.attr('placeholder', 'Textbox #' + index);
		}

		$row.on('click', '.removeButton', function (e) {
			$('#defaultForm').bootstrapValidator('removeField', $el);
			$row.remove();
		});
	});

	$('#defaultForm')
		.bootstrapValidator({
			message: 'This value is not valid',
			feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			fields: {
				'textbox[]': {
					validators: {
						notEmpty: {
							message: 'The textbox field is required'
						}
					}
				},
				'checkbox[]': {
					validators: {
						notEmpty: {
							message: 'The checkbox field is required'
						}
					}
				},
				'radio[]': {
					validators: {
						notEmpty: {
							message: 'The radio field is required'
						}
					}
				}
			}
		})
		.on('error.field.bv', function (e, data) {
			//console.log('error.field.bv -->', data.element);
		})
		.on('success.field.bv', function (e, data) {
			//console.log('success.field.bv -->', data.element);
		})
		.on('added.field.bv', function (e, data) {
			//console.log('Added element -->', data.field, data.element);
		})
		.on('removed.field.bv', function (e, data) {
			//console.log('Removed element -->', data.field, data.element);
		});

});
</script>

</body>
</html>