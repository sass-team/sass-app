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

//		$students = Appointment::getAllStudentsWithAppointment($db, $appointmentId);

		$students = StudentFetcher::retrieveAll($db);
		$courses = CourseFetcher::retrieveAll($db);
		$instructors = InstructorFetcher::retrieveAll($db);

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

		<div class="col-md-3">
			<div class="list-group">

				<a href="javascript:;" class="list-group-item active">
					<h5><i class="fa fa-file-text-o"></i> &nbsp;&nbsp;A-12
						<span class="label label-default">pending</span>
						<span class="label label-warning">pending tutor</span>
						<span class="label label-warning">pending secretary</span>
						<span class="label label-success">completed</span>
					</h5>
				</a>
				<a href="javascript:;" class="list-group-item">
					<h5><i class="fa fa-file-text-o"></i> &nbsp;&nbsp;R-036582
						<span class="label label-default">disabled</span>
						<span class="label label-warning">pending fill</span>
						<span class="label label-warning">pending approval</span>
						<span class="label label-success">completed</span>

					</h5>
					George Papadopoulos
				</a>
			</div>

		</div>
		<div class="col-md-9 col-sidebar-right">

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
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon">Student</span>
										<select name="studentsIds[]" class="form-control students" required>
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
										<select name="instructorIds[]" class="form-control instructors" required>
											<option></option>
											<?php foreach ($instructors as $instructor) {
												include(ROOT_PATH . "app/views/partials/instructor/select-options-view.html.php");
											}
											?>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon">Student</span>
									<select name="studentsIds[]" class="form-control students" required>
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
									<select  name="instructorIds[]" class="form-control instructors" required>
										<option></option>
										<?php foreach ($instructors as $instructor) {
											include(ROOT_PATH . "app/views/partials/instructor/select-options-view.html.php");
										}
										?>
									</select>
								</div>
							</div>
					</div>
							<hr/>

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

								<button type="submit" class="btn btn-block btn-primary">Update</button>
								<input type="hidden" name="hiddenSubmitPrsd" value="">
							</div>
						</form>
					</div>
					<!-- /.form-group -->

				</div>
				<!-- /.form-group -->

			</div>

		</div>
	</div>

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
		var $courseId = $("#courseId");
		var $termId = $("#termId");
		var $tutorId = $("#tutorId");
		var $dateTimePickerStart = $('#dateTimePickerStart');
		var $dateTimePickerEnd = $('#dateTimePickerEnd');
		var $dateTimePickerEnd2 = $('#dateTimePickerEnd');
		var $instructors = $(".instructors");
		var $students = $(".students");

		$students.select2();
		$instructors.select2();
	});
</script>

</body>
</html>