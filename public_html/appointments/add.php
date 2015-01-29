<?php
require __DIR__ . '/../app/init.php';

$general->loggedOutProtect();
$user->denyTutor();

$pageTitle = "New Appointment";
$section = "appointments";

try
{
	$curTerms = TermFetcher::retrieveCurrTerm();
	$coursesForCurTerm = sizeof($curTerms) > 0 ? CourseFetcher::retrieveForTerm($curTerms[0][TermFetcher::DB_COLUMN_ID]) : "";
	$instructors = InstructorFetcher::retrieveAll();
	$students = StudentFetcher::retrieveAll();

	if (isBtnAddStudentPrsd())
	{
		Appointment::add($user, $_POST['dateTimePickerStart'], $_POST['dateTimePickerEnd'], $_POST['courseId'], $_POST['studentsIds'], $_POST['tutorId'], $_POST['instructorIds'], $_POST['termId'], $user->getFirstName() . " " . $user->getLastName());
	}
} catch (Exception $e)
{
	$errors[] = $e->getMessage();
}

function isBtnAddStudentPrsd()
{
	return isset($_POST['hiddenSubmitPrsd']) && empty($_POST['hiddenSubmitPrsd']);
}


function isModificationSuccess()
{
	return isset($_GET['success']) && strcmp($_GET['success'], 'y1!q' === 0);
}

/**
 * http://stackoverflow.com/a/4128377/2790481
 *
 * @param $findId
 * @param $objects
 * @return bool
 */
function get($objects, $findId, $column)
{
	foreach ($objects as $object)
	{
		if ($object[$column] === $findId)
		{
			return $object;
		}
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
				<div class="col-md-1 col-lg-pull-1 col-lg-1 col-md-pull-1 col-sm-pull-1 pull-right">
					<input checked id="toggle-details-calendar-partial" data-toggle="toggle" type="checkbox"
					       data-on="Details" data-off="Calendar">
				</div>
			</h1>

		</div>
		<!-- #content-header -->
		<div id="content-container">
			<?php
			if (empty($errors) === false)
			{
				?>
				<div class="alert alert-danger">
					<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
					<strong>Oh
						snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>';
					?>
				</div>
			<?php
			} else
			{
				if (isModificationSuccess())
				{
					?>
					<div class="alert alert-success">
						<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
						<strong>Workshop successfully created!</strong> <br/>
					</div>
				<?php }
			} ?>
			<div class="portlet">
				<div class="row" id="portletDetails">
					<div class="col-lg-12 col-md-12">
						<div class="portlet-header">

							<h3 class="col-md-10 pull-left">
								<i class="fa fa-calendar"></i>
								Details

							</h3>

						</div>
						<!-- /.portlet-header -->

						<div class="portlet-content">

							<div class="form-group">
								<form method="post" id="add-student-form"
								      action="<?php echo BASE_URL . 'appointments/add'; ?>"
								      class="form">

									<div class="row">

										<div class="col-lg-6 col-md-12">
											<div class="form-group" id="student-instructor">
												<div class="form-group">
													<div class="input-group">
													<span class="input-group-addon"><label
															for="studentId1">Student</label></span>
														<select id="studentId1" name="studentsIds[]"
														        class="form-control"
														        required>
															<option></option>
															<?php
															foreach ($students as $student):
																include(ROOT_PATH . "views/partials/student/select-options-view.html.php");
															endforeach;
															?>
														</select>
													</div>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-12">
											<div class="form-group">
												<div class="input-group">
														<span class="input-group-addon">
															<label for="instructorId1">Instructor</label>
														</span>
													<select id="instructorId1" name="instructorIds[]"
													        class="form-control"
													        required>
														<option></option>
														<?php foreach ($instructors as $instructor)
														{
															include(ROOT_PATH . "views/partials/instructor/select-options-view.html.php");
														}
														?>
													</select>
												</div>
											</div>
										</div>
									</div>
									<!-- /.row -->

									<div class="row">

										<div class="col-lg-6 col-md-12">
											<div class="form-group">
												<div class="input-group">
													<span class="input-group-addon">
														<label for="courseId" id="label-course-text">Course</label>
													</span>
													<select id="courseId" name="courseId" class="form-control" required>
														<option></option>
														<?php foreach ($coursesForCurTerm as $course)
														{
															include(ROOT_PATH . "views/partials/course/select-options-view.html.php");
														}
														?>
													</select>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-12">
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
										</div>
									</div>
									<!-- /.row -->

									<div class="row">

										<div class="col-lg-6 col-md-12">
											<div class="form-group">
												<div class='input-group date' id='dateTimePickerStart'>
													<span class="input-group-addon"><label for="dateTimePickerStart">
															Starts At</label></span>
													<input type='text' name='dateTimePickerStart' class="form-control"
													       required/>
													 <span class="input-group-addon"><span
															 class="glyphicon glyphicon-calendar"></span>
												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-12">
											<div class="form-group">
												<div class='input-group date' id='dateTimePickerEnd'>
													<span class="input-group-addon"><label for="dateTimePickerEnd">Ends
															At</label></span>
													<input type='text' name='dateTimePickerEnd' class="form-control"
													       required/>
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar">
													</span>
												</div>
											</div>
										</div>

									</div>
									<!-- /.row -->

									<div class="row">
										<div class="col-lg-6 col-md-12 pull-right">
											<div class="form-group">
												<div class="input-group">
													<span class="input-group-addon"><label
															for="termId">Term</label></span>
													<select id="termId" name="termId" class="form-control" required>
														<?php
														foreach ($curTerms as $term)
														{
															include(ROOT_PATH . "views/partials/term/select-options-view.html.php");
														}
														?>
													</select>
												</div>
											</div>
										</div>
									</div>
									<!-- /.row -->
									<div class="row">
										<hr/>
										<div class="col-lg-6 col-md-12 pull-right hide" id="textboxTemplate">
											<div class="form-group pull-right">
												<div class="input-group">
													<button type="button" class="btn btn-default btn-sm removeButton">
														Remove
													</button>
												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-lg-6 col-md-12 pull-right">
											<div class="form-group pull-right">
												<div class="input-group">
													<button type="button" class="btn btn-default btn-sm addButton"
													        data-template="textbox">
														Add One More Student
													</button>
												</div>
											</div>
										</div>
									</div>
									<!-- /.row -->


									<div class="row">
										<div class="col-lg-6 col-lg-push-3 col-md-8 col-md-push-2 col-sm-10 col-sm-push-1">
											<div class="form-group">
												<button type="submit" class="btn btn-block btn-primary">Add</button>
												<input type="hidden" name="hiddenSubmitPrsd" value="">
											</div>
										</div>
									</div>
									<!-- /.row -->
								</form>
							</div>
							<!-- /.form-group -->

						</div>
					</div>

				</div>
				<!-- /.row -->

				<div class="row">

					<div class="col-lg-12 col-md-12" id="portletCalendar">
						<div class="portlet-header">

							<h3>
								<i class="fa fa-calendar"></i>
								<span id="calendar-title">
									<i class='fa fa-circle-o-notch fa-spin'></i>
								</span>
								  
								<button id="show-only-working-hours" type="button"
								        class="btn btn-primary btn-xs btn-secondary">
									Working Hours
								</button>
								<button id="show-only-appointments" type="button" class="btn btn-default btn-xs">
									All Appointments
								</button>
								<button id="show-only-appointments" type="button" class="btn btn-tertiary btn-xs" disabled>
									Pending Appointments
								</button>
								<button id="show-only-appointments" type="button" class="btn btn-success btn-xs" disabled>
									Completed Appointments
								</button>
								<button type="button" class="btn btn-primary btn-xs" disabled>
									Canceled Appointments
								</button>
							</h3>

						</div>
						<!-- /.portlet-header -->

						<div class="portlet-content" id="calendar-portlet">

							<div id="appointments-schedule-calendar"></div>
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

	<?php include ROOT_PATH . "views/footer.php"; ?>
</div>
<!-- #wrapper<!-- #content -->

<!--data for js features.-->
<input type="hidden" id="isAdmin" value="<?php echo $user->isAdmin(); ?>"/>
<input type="hidden" id="userId" value="<?php echo $user->getId(); ?>"/>
<input type="hidden" id="isBtnAddStudentPrsd" value="<?php echo isBtnAddStudentPrsd(); ?>"/>
<input type="hidden" id="tutorIdServerSide" value="<?php echo $_POST['tutorId']; ?>"/>
<input type="hidden" id="courseIdServerSide" value="<?php echo $_POST['courseId']; ?>"/>
<input type="hidden" id="dateTimePickerStartServerSide" value="<?php echo $_POST['dateTimePickerStart']; ?>"/>
<input type="hidden" id="dateTimePickerEndServerSide" value="<?php echo $_POST['dateTimePickerEnd']; ?>"/>
<input type="hidden" id="domainName" value="<?php echo App::getDomainName(); ?>"/>

<!-- Vendor Libraries -->
<?php include ROOT_PATH . "views/assets/footer_common.php"; ?>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/spin/spin.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/autosize/jquery.autosize.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/select2/select2.js"></script>
<script
	src="<?php echo BASE_URL; ?>assets/js/plugins/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js">
</script>
<script
	src="<?php echo BASE_URL; ?>assets/packages/bootstrap-toggle/js/bootstrap-toggle.min.js">
</script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/fullcalendar/fullcalendar.min.js"></script>

<!-- Custom js -->
<script src="<?php echo BASE_URL; ?>assets/js/app/appointments-add.js"></script>
</body>
</html>
