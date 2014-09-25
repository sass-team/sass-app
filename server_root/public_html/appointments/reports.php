<?php
require __DIR__ . '/../../app/init.php';
$general->loggedOutProtect();

$pageTitle = "Reports";
$section = "appointments";
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
			<h1>Reports for Appointment id: 25412</h1>
		</div>

		<div id="content-container">


			<div class="row">


				<div class="col-md-4">
					<div class="alert alert-info" role="alert"><strong>Course: </strong>CS1070 <strong>Date: </strong>25/09/2014
					</div>
					<div class="list-group">

						<a href="javascript:;" class="list-group-item">
							<h5><i class="fa fa-file-text-o"></i> &nbsp;&nbsp;R-036582 <span class="label label-success">Submitted</span>
							</h5>
							George Papadopoulos
						</a>

						<a href="javascript:;" class="list-group-item">
							<h5><i class="fa fa-file-text-o"></i> &nbsp;&nbsp;R-0335698 <span class="label label-success">Submitted</span>
							</h5>
							John Pappas
						</a>

						<a href="javascript:;" class="list-group-item">
							<h5><i class="fa fa-file-text-o"></i> &nbsp;&nbsp;R-028756 <span class="label label-warning">Pending</span>
							</h5>
							Rizart the Coder
						</a>

						<a href="javascript:;" class="list-group-item">
							<h5><i class="fa fa-file-text-o"></i> &nbsp;&nbsp;R-0215893 <span class="label label-success">Submitted</span>
							</h5>
							Philip Asshole
						</a>

					</div>

				</div>
				<div class="col-md-8 col-sidebar-right">

					<div class="portlet">

						<div class="portlet-header">

							<h3>
								Conference Details
							</h3>
						</div>
						<!-- /.portlet-header -->

						<div class="portlet-content">

							<div class="row">


								<div class="col-sm-6">
									<div class="form-group">
										<div class='input-group date' id='dateTimePickerStart'>
											<span class="input-group-addon"><label for="dateTimePickerStart">
													Started At</label></span>
											<input type='text' name='dateTimePickerStart' class="form-control" required/>
                                 <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
										</div>
									</div>
								</div>

								<div class="col-sm-6">
									<div class="form-group">
										<div class='input-group date' id='dateTimePickerEnd'>
                                        <span class="input-group-addon"><label for="dateTimePickerEnd">Ended
		                                        At</label></span>
											<input type='text' name='dateTimePickerEnd' class="form-control" required/>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar">
										</span>
										</div>
									</div>
								</div>


							</div>


						</div>

					</div>

				</div>
			</div>
		</div>

		<?php include ROOT_PATH . "app/views/footer.php"; ?>

	</div>
	<!-- #wrapper -->
	<?php include ROOT_PATH . "app/views/assets/footer_common.php"; ?>

	<script src="<?php echo BASE_URL; ?>assets/js/plugins/select2/select2.js"></script>
	<script src="<?php echo BASE_URL; ?>assets/js/plugins/icheck/jquery.icheck.js"></script>

	<script src="<?php echo BASE_URL; ?>assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="<?php echo BASE_URL; ?>assets/js/plugins/datatables/DT_bootstrap.js"></script>

	<script src="<?php echo BASE_URL; ?>assets/js/plugins/datepicker/bootstrap-datepicker.js"></script>
	<script src="<?php echo BASE_URL; ?>assets/js/plugins/timepicker/bootstrap-timepicker.js"></script>
	<script src="<?php echo BASE_URL; ?>assets/js/plugins/simplecolorpicker/jquery.simplecolorpicker.js"></script>
	<script src="<?php echo BASE_URL; ?>assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>
	<script src="<?php echo BASE_URL; ?>assets/js/plugins/autosize/jquery.autosize.min.js"></script>
	<script src="<?php echo BASE_URL; ?>assets/js/demos/form-extended.js"></script>


</body>
</html>