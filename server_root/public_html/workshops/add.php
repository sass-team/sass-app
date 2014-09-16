<?php
require __DIR__ . '/../../app/init.php';
$general->loggedOutProtect();

// viewers
$page_title = "Workshops";
$section = "workshops";

try {
	$courses = CourseFetcher::retrieveAll($db);
	$terms = TermFetcher::retrieveAll($db);
	$instructors = InstructorFetcher::retrieveAll($db);
	$students = StudentFetcher::retrieveAll($db);
} catch (Exception $e) {
	$errors[] = $e->getMessage();
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
				Add Workshop Session
			</h1>

		</div>
		<!-- #content-header -->


		<div id="content-container">

			<div class="portlet">

				<div class="row">


					<div class="col-md-4">
						<div class="portlet-header">

							<h3>
								<i class="fa fa-calendar"></i>
								New Workshop Session
							</h3>

						</div>
						<!-- /.portlet-header -->

						<div class="portlet-content">

							<div class="form-group">

								<div class="form-group">
									<div class="input-group">
										<div class='input-group date' id='dateTimePickerStart'>
											<span class="input-group-addon"><label for="dateTimePickerStart">Starts
													At</label></span>
											<input type='text' class="form-control"/>
                                 <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
										</div>
									</div>
								</div>

								<div class="form-group">
									<div class="input-group">
										<div class='input-group date' id='dateTimePickerEnd'>
											<span class="input-group-addon"><label for="dateTimePickerEnd">Ends
													At&#32; </label></span>
											<input type='text' class="form-control"/>
                                 <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
										</div>
									</div>
								</div>

								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"><label for="courseId">Course</label></span>
										<select id="courseId" name="courseId" class="form-control">
											<?php foreach ($courses as $course) {
												include(ROOT_PATH . "app/views/partials/course-select-options-view.html.php");
											}
											?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"><label for="studentsIds">Students</label></span>
										<select id="studentsIds" name="studentsIds[]" class="form-control" multiple>

											<?php
											foreach ($students as $student) {
												include(ROOT_PATH . "app/views/partials/students-select-options-view.html.php");
											}
											?>

										</select>
									</div>
								</div>


								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"><label for="instructorId">Instructor</label></span>
										<select id="instructorId" name="instructorId" class="form-control">
											<?php foreach ($instructors as $instructor) {
												include(ROOT_PATH . "app/views/partials/instructor-select-options-view.html.php");
											}
											?>
										</select>
									</div>
								</div>


								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"><label for="termId">Term</label></span>
										<select id="termId" name="termId" class="form-control">
											<?php foreach ($terms as $term) {
												include(ROOT_PATH . "app/views/partials/term-select-options-view.html.php");
											}
											?>
										</select>
									</div>
								</div>

							</div>
						</div>
					</div>


					<div class="col-md-8">
						<div class="portlet-header">

							<h3>
								<i class="fa fa-calendar"></i>
								Date Picker
							</h3>

						</div>
						<!-- /.portlet-header -->

						<div class="portlet-content">

							<div id="full-calendar"></div>
						</div>
					</div>

				</div>
				<!-- /.row -->


			</div>
			<!-- /.portlet -->

		</div>
		<!-- /#content-container -->


	</div>
	<!-- #content -->


	<?php include ROOT_PATH . "app/views/footer.php"; ?>
</div>
<!-- #wrapper<!-- #content -->

</body>
</html>

<?php include ROOT_PATH . "app/views/assets/footer_common.php"; ?>

<!-- dashboard assets -->
<script src="<?php echo BASE_URL; ?>assets/js/plugins/icheck/jquery.icheck.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/tableCheckable/jquery.tableCheckable.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/libs/raphael-2.1.2.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/morris/morris.min.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/demos/charts/morris/area.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/demos/charts/morris/donut.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/sparkline/jquery.sparkline.min.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/fullcalendar/fullcalendar.min.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/simplecolorpicker/jquery.simplecolorpicker.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/autosize/jquery.autosize.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/select2/select2.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/demos/calendar.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/demos/dashboard.js"></script>

<script
	src="<?php echo BASE_URL; ?>assets/js/plugins/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js">
</script>

<script type="text/javascript">
	$(function () {
		// http://momentjs.com/docs/#/manipulating/add/
		// http://eonasdan.github.io/bootstrap-datetimepicker
		moment().format();


		$("#courseId").select2();
		$("#termId").select2();
		$("#instructorId").select2();
		$("#studentsIds").select2();



		$('#dateTimePickerStart').datetimepicker({
			defaultDate: moment(),
			minDate: moment().subtract('1', 'day'),
			minuteStepping: 10,
			daysOfWeekDisabled: [0, 6],
			sideBySide: true
		});
		var $startSessionMoment = moment($('#dateTimePickerStart').data("DateTimePicker").getDate());
		var dateEnd = moment().add(30, 'minutes');

		$('#dateTimePickerEnd').datetimepicker({
			defaultDate: dateEnd,
			minDate: $startSessionMoment,
			minuteStepping: 10,
			daysOfWeekDisabled: [0, 6],
			sideBySide: true
		});
		var $endSessionMoment = moment($('#dateTimePickerEnd').data("DateTimePicker").getDate());

		$("#dateTimePickerStart").on("dp.change", function (e) {
			var momentStart = $endSessionMoment;
			var momentEnd = momentStart.clone();
			var momentMinEnd = momentStart.clone();

			$('#dateTimePickerEnd').data("DateTimePicker").setMinDate(momentMinEnd.add(20, 'minutes'));
			$('#dateTimePickerEnd').data("DateTimePicker").setDate(momentEnd.add(30, 'minutes'));
		});


	});
</script>