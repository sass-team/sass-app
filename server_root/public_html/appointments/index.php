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

		$students = Appointment::getAllStudentsWithAppointment($db, $appointmentId);
		$course = Course::get($db, $students[0][AppointmentFetcher::DB_COLUMN_COURSE_ID]);
		$term = TermFetcher::retrieveSingle($db, $students[0][AppointmentFetcher::DB_COLUMN_TERM_ID]);
		$tutorName = $students[0][UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_FIRST_NAME] . " " .
			$students[0][UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_LAST_NAME];
		$startTime = $students[0][AppointmentFetcher::DB_COLUMN_START_TIME];
		$endTime = $students[0][AppointmentFetcher::DB_COLUMN_END_TIME];
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

			<div class="portlet">

				<?php if (isUrlRequestingSingleAppointment()) { ?>

					<div class="col-md-12">
						<div class="portlet-header">

							<h3>
								<i class="fa fa-calendar"></i>
								Details
							</h3>

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
													include(ROOT_PATH . "app/views/partials/student/name-table-data-view.html.php");
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
													include(ROOT_PATH . "app/views/partials/instructor/name-table-data-view.html.php");
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
											<input type='text' value="<?php echo $startTime; ?>" class="form-control" disabled/>
										</div>
									</div>
								</div>
								<div class="form-group">

									<div class="row">

										<div class="col-md-6 col-sm-6">
											<h4>Ending Date</h4>
										</div>


										<div class="col-md-6 col-sm-6">
											<input type='text' value="<?php echo $endTime; ?>"
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

				<?php } ?>

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
	});
</script>

</body>
</html>