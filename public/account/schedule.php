<?php
require '../app/init.php';
$general->loggedOutProtect();

// viewers
$page_title = "Dashboard - SASS Management System";
$section = "dashboard";
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
			<h1>Schedule</h1>
		</div>
		<!-- #content-header -->


		<div id="content-container">


			<div class="portlet">

				<div class="portlet-header">

					<h3>
						<i class="fa fa-calendar"></i>
						Full Workshop Session Calendar
					</h3>

				</div>
				<!-- /.portlet-header -->

				<div class="portlet-content">


					<div id="full-calendar"></div>


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
<!-- #wrapper -->

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

<script src="<?php echo BASE_URL; ?>app/assets/js/demos/calendar.js"></script>

<script src="<?php echo BASE_URL; ?>app/assets/js/demos/dashboard.js"></script>

<script type="text/javascript">
	$(function () {
		if (!$('#workshop-chart').length) {
			return false;
		}
		workshop();
		$(window).resize(App.debounce(workshop, 325));
	});

	function workshop() {
		$('#workshop-chart').empty();

		Morris.Donut({
			element: 'workshop-chart',
			data: [
				{label: 'Successful', value: 60 },
				{label: 'Canceled', value: 30 },
				{label: 'Unkown', value: 10 }
			],
			colors: ['#0BA462', '#f0ad4e', '#888888'],
			hideHover: true,
			formatter: function (y) {
				return y + "%"
			}
		});
	}
</script>