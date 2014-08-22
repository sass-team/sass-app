<?php
require '../app/init.php';
$general->loggedOutProtect();

// viewers
$page_title = "Workshops";
$section = "workshops";
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
				Full Workshop Session Calendar
			</h1>

		</div>
		<!-- #content-header -->


		<div id="content-container">

			<div class="portlet">

				<div class="portlet-header">

					<h3>
						<i class="fa fa-calendar"></i>
						Date Picker
					</h3>

				</div>
				<!-- /.portlet-header -->

				<div class="portlet-content">


					<div class="row">

						<div class="col-sm-8">
							<div id="full-calendar"></div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">

								<h4>Workshop Session</h4>

								<div class="form-group">

									<div class="input-group">
										<span class="input-group-addon"><label for="dateTimePickerStart">Starts At</label></span>
										<input id="dateTimePickerStart" name="dateTimePickerStart" type="text"
										       value="08/22/2014 07:08"
										       class="form-control">

							    <span class="input-group-btn">
							     <button id="imgBtnStart" class="btn btn-default" type="button">
								     <i class="fa fa-calendar"></i>
									</div>
								</div>
								<div class="form-group">

									<div class="input-group">
										<span class="input-group-addon"><label for="dateTimePickerEnd">Ends At</label></span>
										<input id="dateTimePickerEnd" name="dateTimePickerEnd" type="text"
										       value="08/22/2014 07:08"
										       class="form-control">

					    <span class="input-group-btn">
					     <button id="imgBtnEnd" class="btn btn-default" type="button">
						     <i class="fa fa-calendar"></i>
									</div>
								</div>
							</div>
						</div>

					</div>

				</div>
				<!-- /.portlet-content -->

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
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/icheck/jquery.icheck.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/tableCheckable/jquery.tableCheckable.js"></script>

<script src="<?php echo BASE_URL; ?>app/assets/js/libs/raphael-2.1.2.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/morris/morris.min.js"></script>

<script src="<?php echo BASE_URL; ?>app/assets/js/demos/charts/morris/area.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/demos/charts/morris/donut.js"></script>

<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/sparkline/jquery.sparkline.min.js"></script>

<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/fullcalendar/fullcalendar.min.js"></script>

<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/simplecolorpicker/jquery.simplecolorpicker.js"></script>

<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/autosize/jquery.autosize.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>

<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/datepicker/bootstrap-datepicker.js"></script>

<script src="<?php echo BASE_URL; ?>app/assets/js/demos/calendar.js"></script>

<script src="<?php echo BASE_URL; ?>app/assets/js/demos/dashboard.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>app/assets/css/jquery.datetimepicker.css"/ >
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/datetimepicker/jquery.datetimepicker.js"></script>

<script type="text/javascript">
	$(function () {

		$('#dpStart').datepicker().on('changeDate', function (e) {
			$('#dpEnd').datepicker('setStartDate', e.date);
		});

		$('#dpEnd').datepicker().on('changeDate', function (e) {
			$('#dpStart').datepicker('setEndDate', e.date)
		});

		$('#dateTimePickerStart').datetimepicker({
			format: 'm/d/Y H:i'
		});

		$('#dateTimePickerEnd').datetimepicker({
			format: 'm/d/Y H:i'
		});

		$('#imgBtnStart').click(function () {
			$('#dateTimePickerStart').datetimepicker('show'); //support hide,show and destroy command
		});

		$('#imgBtnEnd').click(function () {
			$('#dateTimePickerEnd').datetimepicker('show'); //support hide,show and destroy command
		});
	});
</script>