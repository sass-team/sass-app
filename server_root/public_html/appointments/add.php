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
	$courses = CourseFetcher::retrieveAll($db);
	$terms = TermFetcher::retrieveAllButCur($db);
	$curTerm = TermFetcher::retrieveCurrent($db);
	$instructors = InstructorFetcher::retrieveAll($db);
	$students = StudentFetcher::retrieveAll($db);
	$tutors = TutorFetcher::retrieveAll($db);
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


<script type="text/javascript">
	$(function () {
		// http://momentjs.com/docs/#/manipulating/add/
		// http://eonasdan.github.io/bootstrap-datetimepicker
		moment().format();

		$("#courseId").select2({
			placeholder: "Select a Course",
			allowClear: false
		});
		$("#courseId").click(function () {
			var courseId = $(this).select2("val");
			var termId = $("#termId").select2("val");

			var data = {
				"action": "tutor_has_courses",
				"courseId": courseId,
				"termId": termId
			}
			data = $(this).serialize() + "&" + $.param(data);

			var dataSel = [{id: 0, tag: 'enhancement'}, {id: 1, tag: 'bug'}, {id: 2, tag: 'duplicate'}, {
				id: 3,
				tag: 'invalid'
			}, {id: 4, tag: 'wontfix'}];


			$.ajax({
				type: "GET",
				dataType: "json",
				url: "<?php echo "http://" . $_SERVER['SERVER_NAME']; ?>/api/courses",
				data: data,
				success: function (data) {
					console.log(data);

					$("#e10").select2({
//							data: [
//								$.each(data, function (idx, obj) {
//									{
//										id: obj['id'], text:obj['f_name'] + ' ' + obj['l_name']
//									}
////									$.each(obj, function (key, value) {
////										console.log(key + ": " + value);
////									});
//								});
//						]
					})


				},
				error: function (e) {

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
		$("#instructorId").select2();
		$("#studentsIds").select2();
		$("#tutorId").select2();
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

	});
</script>

</body>
</html>