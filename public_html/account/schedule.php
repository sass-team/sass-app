<?php
require __DIR__ . '/../app/init.php';
$general->loggedOutProtect();

// viewers
$pageTitle = "New Appointment";
$section = "account";

try {
	$terms = TermFetcher::retrieveCurrTerm();
	$students = StudentFetcher::retrieveAll();


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
				Schedules
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
			} ?>

			<div class="portlet">
				<div class="row">
					<div class="col-md-12">
						<div class="portlet-header">

							<h3>
								<i class="fa fa-calendar"></i>Details
								<span id="calendar-title"><i class='fa fa-circle-o-notch fa-spin'></i></span>
								<span class="label label-secondary">Working Hours</span>
							</h3>

							<div class="col-md-6">
								<select id="termId" name="termId" class="form-control" required>
									<?php
									foreach ($terms as $term) {
										include(ROOT_PATH . "views/partials/term/select-options-view.html.php");
									}
									?>
								</select>
							</div>
						</div>
						<!-- /.portlet-header -->

						<div class="portlet-content" id="calendar-portlet">

							<div id="schedule-calendar"></div>
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


<input type="hidden" id="userId" value="<?php echo $user->getId(); ?>"/>
<input type="hidden" id="domainName" value="<?php echo App::getDomainName(); ?>"/>

<?php include ROOT_PATH . "views/assets/footer_common.php"; ?>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/spin/spin.min.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/autosize/jquery.autosize.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/select2/select2.js"></script>
<script
	src="<?php echo BASE_URL; ?>assets/js/plugins/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js">
</script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/fullcalendar/fullcalendar.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/packages/pnotify/pnotify.custom.min.js"></script>

<!-- Custom js -->
<script src="<?php echo BASE_URL; ?>assets/js/app/schedule.js"></script>

</body>
</html>
