<?php
require __DIR__ . '/../../app/init.php';
$general->loggedOutProtect();

$section = "appointments";

try {
	if (isUrlRequestingSingleAppointment()) {
		$pageTitle = "Single Appointment";
		$appointmentId = $_GET['appointmentId'];

		if ($user->isTutor() && !Tutor::hasAppointmentWithId($db, $user->getId(), $appointmentId)) {
			header('Location: ' . BASE_URL . "error-403");
			exit();
		}

		$appointmentData = Appointment::getAllStudentsWithAppointment($db, $appointmentId);
		$terms = TermFetcher::retrieveAll($db);

		var_dump($appointmentData);
//		$students = Appointment::getAllStudentsWithAppointment($db, $appointmentId);

		$students = StudentFetcher::retrieveAll($db);
		$courses = CourseFetcher::retrieveAll($db);
		$instructors = InstructorFetcher::retrieveAll($db);
		$tutors = TutorFetcher::retrieveAll($db);
		$startTime = new DateTime($appointmentData[0][AppointmentFetcher::DB_COLUMN_START_TIME]);
		$endTime = new DateTime($appointmentData[0][AppointmentFetcher::DB_COLUMN_END_TIME]);
//		$course = Course::get($db, $students[0][AppointmentFetcher::DB_COLUMN_COURSE_ID]);
//		$term = TermFetcher::retrieveSingle($db, $students[0][AppointmentFetcher::DB_COLUMN_TERM_ID]);
//		$tutorName = $students[0][UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_FIRST_NAME] . " " .
//			$students[0][UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_LAST_NAME];
//		$startTime = $students[0][AppointmentFetcher::DB_COLUMN_START_TIME];
//		$endTime = $students[0][AppointmentFetcher::DB_COLUMN_END_TIME];
	} else {
		header('Location: ' . BASE_URL . "error-403");
		exit();
	}

} catch (Exception $e) {
	$errors[] = $e->getMessage();
}


function isUrlRequestingSingleAppointment() {
	return isset($_GET['appointmentId']) && preg_match("/^[0-9]+$/", $_GET['appointmentId']);
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
		<?php echo $pageTitle; ?>
	</h1>


</div>
<!-- #content-header -->

<div id="content-container">
<div class="row">

<div class="col-md-3 col-sm-4">
	<div class="list-group">

		<a href="#appointment-tab" class="list-group-item active" data-toggle="tab">
			<h5><i class="fa fa-file-text-o"></i>
				&nbsp;&nbsp;A-<?php echo $appointmentData[0][AppointmentFetcher::DB_COLUMN_ID]; ?>
				<span
					class="label label-<?php echo $appointmentData[0][AppointmentFetcher::DB_COLUMN_LABEL_COLOR]; ?>">
							<?php echo $appointmentData[0][AppointmentFetcher::DB_COLUMN_LABEL_MESSAGE]; ?>
						</span>
			</h5>
		</a>

		<?php for ($i = 0; $i < sizeof($appointmentData); $i++) { ?>
			<a href="#report-tab<?php echo $i; ?>" class="list-group-item" data-toggle="tab">
				<h5>
					<i class="fa fa-file-text-o"></i> &nbsp;&nbsp;R

					<?php if ($appointmentData[$i][AppointmentHasStudentFetcher::DB_COLUMN_REPORT_ID] !== NULL): ?>
						<?php echo "-" . $appointmentData[$i][AppointmentHasStudentFetcher::DB_COLUMN_REPORT_ID]; ?>
						<span
							class="label label-<?php echo $appointmentData[$i][AppointmentFetcher::DB_COLUMN_LABEL_COLOR]; ?>">
							<?php echo $appointmentData[$i][AppointmentFetcher::DB_COLUMN_LABEL_MESSAGE]; ?>
						</span>
					<?php else: ?>
						<span class="label label-default">disabled</span>
					<?php endif; ?>
				</h5>
				<?php echo $appointmentData[$i][UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_FIRST_NAME] . " " .
					$appointmentData[$i][UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_LAST_NAME]; ?>
			</a>
		<?php } ?>

	</div>
	<!-- /.list-group -->
</div>

<div class="col-md-9 col-sm-8">
<div class="tab-content stacked-content">

<div class="tab-pane fade in active" id="appointment-tab">

	<div class="portlet">
		<div class="portlet-header">

			<h3>
				<i class="fa fa-file-text"></i>
				Appointment Details
			</h3>

		</div>
		<!-- /.portlet-header -->

		<div class="portlet-content">

			<div class="form-group">
				<form method="post" id="add-student-form"
				      action="<?php echo BASE_URL . 'appointments/add'; ?>"
				      class="form">


					<div class="form-group" id="student-instructor">
						<?php
						for ($i = 0; $i < sizeof($appointmentData); $i++) {
							?>
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon">Student</span>
									<select name="studentsIds[]" id="studentId<?php echo $i; ?>" class="form-control"
									        required>
										<option></option>
										<?php
										foreach ($students as $student):
											include(ROOT_PATH . "app/views/partials/student/select-options-view.html.php");
										endforeach;
										?>
									</select>
								</div>

							</div>
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon">Instructor</span>
									<select name="instructorIds[]" id="instructorId<?php echo $i; ?>"
									        class="form-control" required disabled>
										<option></option>
										<?php foreach ($instructors as $instructor) {
											include(ROOT_PATH . "app/views/partials/instructor/select-options-view.html.php");
										}
										?>
									</select>
								</div>
							</div>
						<?php } ?>
					</div>
			</div>
			<hr/>

			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><label for="courseId">Course</label></span>
					<select id="courseId" name="courseId" class="form-control" required disabled>
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
					<select id="tutorId" name="tutorId" class="form-control" required disabled>
						<?php foreach ($tutors as $tutor) {
							include(ROOT_PATH . "app/views/partials/tutor/select-options-view.html.php");
						}
						?>               </select>
					<input id="value" type="hidden" style="width:300px"/>
				</div>
			</div>


			<div class="form-group">
				<div class='input-group date' id='dateTimePickerStart'>
											<span class="input-group-addon"><label for="dateTimePickerStart">
													Starts At</label></span>
					<input type='text' name='dateTimePickerStart' class="form-control" required disabled/>
                                 <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
				</div>
			</div>


			<div class="form-group">
				<div class='input-group date' id='dateTimePickerEnd'>
                                        <span class="input-group-addon"><label for="dateTimePickerEnd">Ends
		                                        At</label></span>
					<input type='text' name='dateTimePickerEnd' class="form-control" required disabled/>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar">
										</span>
				</div>
			</div>


			<div class="form-group">
				<div class="input-group">
					<button type="button" class="btn btn-default btn-sm addButton"
					        data-template="textbox" disabled>
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
					<select id="termId" name="termId" class="form-control" required disabled>
						<?php
						foreach ($terms as $term) {
							include(ROOT_PATH . "app/views/partials/term/select-options-view.html.php");
						}
						?>
					</select>
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
				} else if (isModificationSuccess()) {
					?>
					<div class="alert alert-success">
						<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
						<strong>Workshop successfully created!</strong> <br/>
					</div>
				<?php } ?>

				<button type="submit" class="btn btn-block btn-primary" disabled>Update</button>
				<input type="hidden" name="hiddenSubmitPrsd" value="">
			</div>
			</form>
		</div>
		<!-- /.form-group -->

	</div>
	<!-- /.form-group -->

</div>


<?php
for ($i = 0; $i < sizeof($appointmentData); $i++) {
	?>

	<div class="tab-pane fade" id="report-tab<?php echo $i; ?>">

		<h3 class="">Change Your Password</h3>


		<form action="<?php echo BASE_URL; ?>account/settings" class="form-horizontal" method="post">

			<div class="form-group">

				<label class="col-md-3" for="oldPassword">Old Password</label>

				<div class="col-md-7">
					<input type="password" name="oldPassword" id="oldPassword" class="form-control"/>
				</div>
				<!-- /.col -->

			</div>
			<!-- /.form-group -->


			<hr/>


			<div class="form-group">

				<label class="col-md-3" for="newPassword1">New Password</label>

				<div class="col-md-7">
					<input type="password" name="newPassword1" id="newPassword1" class="form-control"/>
				</div>
				<!-- /.col -->

			</div>
			<!-- /.form-group -->

			<div class="form-group">

				<label class="col-md-3" for="newPassword2">New Password Confirm</label>

				<div class="col-md-7">
					<input type="password" name="newPassword2" id="newPassword2" class="form-control"/>
				</div>
				<!-- /.col -->

			</div>
			<!-- /.form-group -->

			<br/>

			<div class="form-group">

				<div class="col-md-7 col-md-push-3">
					<button type="submit" class="btn btn-primary">Save Changes</button>
					<input type="hidden" name="form_action_update_password" value="">
					&nbsp;
					<button type="reset" class="btn btn-default">Cancel</button>
				</div>
				<!-- /.col -->

			</div>
			<!-- /.form-group -->

		</form>
	</div>
	<!-- /.tab-pane fade -->

<?php } ?>

</div>
<!-- ./tab-content -->


</div>
<!-- /.col -->

</div>
<!-- /.row -->
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
		var $dateTimePickerStart = $('#dateTimePickerStart');
		var $courseId = $("#courseId");
		var $termId = $("#termId");
		var $tutorId = $("#tutorId");
		var $dateTimePickerStart = $('#dateTimePickerStart');
		var $dateTimePickerEnd = $('#dateTimePickerEnd');
		var $dateTimePickerEnd2 = $('#dateTimePickerEnd');
		var $instructors = $(".instructors");
		var $students = $(".students");

		<?php for($i = 0; $i < sizeof($appointmentData); $i++){?>
		$("#studentId<?php echo $i;?>").select2();
		$("#instructorId<?php echo $i;?>").select2();
		$("#studentId<?php echo $i;?>").select2("val", '<?php echo $appointmentData[$i][AppointmentHasStudentFetcher::DB_COLUMN_STUDENT_ID]?>');
		$("#instructorId<?php echo $i;?>").select2("val", '<?php echo $appointmentData[$i][AppointmentHasStudentFetcher::DB_COLUMN_INSTRUCTOR_ID]?>');
		$("#studentId<?php echo $i;?>").select2("enable", false);
		$("#instructorId<?php echo $i;?>").select2("enable", false);
		<?php } ?>

		$('.list-group-item').on('click', function () {
			if ($(this).attr('class') != 'list-group-item active') {
				$('.list-group-item.active').removeClass('active');
				$(this).addClass('active');
			}
		});
		$courseId.select2();
		$courseId.select2("val", '<?php echo $appointmentData[0][AppointmentFetcher::DB_COLUMN_COURSE_ID]?>');
		$tutorId.select2();
		$tutorId.select2("val", '<?php echo $appointmentData[0][UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_ID]?>');


		var startDateDefault;
		if (moment().minute() >= 30) {
			startDateDefault = moment().add('1', 'hours');
			startDateDefault.minutes(0);
		} else {
			startDateDefault = moment();
			startDateDefault.minutes(30);
		}

		var minimumStartDate = startDateDefault.clone();
		minimumStartDate.subtract('31', 'minutes');
		var minimumMaxDate = moment().add('14', 'day');

		var endDateDefault = startDateDefault.clone();
		endDateDefault.add('30', 'minutes');
		var minimumEndDate = endDateDefault.clone();
		minimumEndDate.subtract('31', 'minutes');

		$dateTimePickerStart.datetimepicker({
			defaultDate: '<?php echo $startTime->format('m/d/Y g:i A');?>',
			minDate: minimumStartDate,
			maxDate: minimumMaxDate,
			minuteStepping: 30,
			daysOfWeekDisabled: [0, 6],
			sideBySide: true,
			strict: true
		});

		$dateTimePickerEnd.datetimepicker({
			defaultDate: '<?php echo $endTime->format('m/d/Y g:i A');?>',
			minDate: minimumEndDate,
			minuteStepping: 30,
			daysOfWeekDisabled: [0, 6],
			sideBySide: true,
			strict: true
		});

	});
</script>

</body>
</html>