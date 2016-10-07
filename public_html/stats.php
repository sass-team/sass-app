<?php
require __DIR__ . '/app/init.php';
$general->loggedOutProtect();
// redirect if user elevation is not that of admin
if (!$user->isAdmin()) {
	header('Location: ' . BASE_URL . "error-403");
	exit();
}
try
{
	date_default_timezone_set('Europe/Athens');
    $currentTerms = TermFetcher::retrieveCurrTerm();
    $currentTermIds = array_column($currentTerms, 'id');
	$now = new DateTime($currentTerms[0]['start_date']);
    $end = new DateTime($currentTerms[0]['end_date']);
	$nowString = $currentTerms[0]['start_date'];
    $endString = $currentTerms[0]['end_date'];

    $totalAppointments = AppointmentFetcher::countForTermids($currentTermIds);
    $achievedAppointments = AppointmentFetcher::countForTermids($currentTermIds, ['complete']);
    $canceledAppointments = AppointmentFetcher::countForTermids($currentTermIds, ['canceled by student', 'disabled by admin', 'canceled by tutor']);
    $hourlyAppointments = AppointmentFetcher::retrieveByGroupedDateForTermIds($currentTermIds, 'hour');
    $dailyAppointments = AppointmentFetcher::retrieveByGroupedDateForTermIds($currentTermIds, 'date');

    foreach ( $hourlyAppointments as $appointment ){
        $date = date("h:i", strtotime($appointment['date']));
        $hourlyAppointmentsJson[] = ['period' => $date, 'total' => $appointment['total'], 'achieved' => 0, 'canceled' => 0];
    }
    $hourlyAppointmentsJson = json_encode($hourlyAppointmentsJson);

    foreach ( $dailyAppointments as $appointment ){
        $date = date("Y-m-d", strtotime($appointment['date']));
        $dailyAppointmentsJson[] = ['period' => $date, 'total' => $appointment['total'], 'achieved' => 0, 'canceled' => 0];
    }
    $dailyAppointmentsJson = json_encode($dailyAppointmentsJson);


} catch (Exception $e)
{
	$errors[] = $e->getMessage();
}

// viewers
$pageTitle = "Stats";
$section = "stats";


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
			<h1>Stats</h1>
		</div>
		<!-- #content-header -->


		<div id="content-container">
			<?php
			$day = clone $now;
			/**
			 * @param $day
			 * @param $i
			 * @param $appointments
			 * @return array
			 */
			function countLabelsStatusAppointments($day, $i, $appointments)
			{
				$weekOfYear = $day->format('W');
				$year = $day->format('Y');
				$day->setISODate($year, $weekOfYear, $i);
				$dateFormatted = $day->format('Y-m-d');

				$appointmentsForDay = getAppointmentsForYearDay($appointments, $day);
				$countAppointmentsForDay = sizeof($appointmentsForDay);
				$countAchievedAppointmentsForDay = Appointment::countWithLabelMessage($appointmentsForDay, Appointment::LABEL_MESSAGE_COMPLETE);
				$canceledLabelMessagesForDay = [Appointment::LABEL_MESSAGE_STUDENT_NO_SHOW,
					Appointment::LABEL_MESSAGE_TUTOR_CANCELED, Appointment::LABEL_MESSAGE_TUTOR_NO_SHOW, Appointment::LABEL_MESSAGE_STUDENT_CANCELED];
				$countCanceledAppointmentsForDay = Appointment::countWithLabelMessages($appointmentsForDay, $canceledLabelMessagesForDay);

				return [$weekOfYear, $year, $dateFormatted, $appointmentsForDay, $countAppointmentsForDay, $countAchievedAppointmentsForDay, $canceledLabelMessagesForDay, $countCanceledAppointmentsForDay];
			}

			?>
			<div>
				<h4 class="heading-inline">Workshop Sessions Stats
					&nbsp;&nbsp;
					<small>For the period of <?php echo $now->format('M d, o') . " - " . $end->format('M d, o'); ?></small>
					&nbsp;&nbsp;
				</h4>

				<?php
				if (empty($errors) === false)
				{
					?>
					<div class="alert alert-danger">
						<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
						<strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>';
						?>
					</div>
				<?php } ?>
			</div>

			<br/>


			<div class="row">

				<!-- /.col-md-3 -->

				<div class="col-md-3 col-sm-6">

					<a href="javascript:;" class="dashboard-stat tertiary">
						<div class="visual">
							<i class="fa fa-clock-o"></i>
						</div>
						<!-- /.visual -->

						<div class="details">
							<span class="content">Planned</span>
							<span class="value"><?php echo $totalAppointments; ?></span>
						</div>
						<!-- /.details -->

						<i class="fa fa-play-circle more"></i>

					</a> <!-- /.dashboard-stat -->

				</div>

				<div class="col-md-3 col-sm-6">

					<a href="javascript:;" class="dashboard-stat primary">
						<div class="visual">
							<i class="fa fa-pencil-square-o"></i>
						</div>
						<!-- /.visual -->

						<div class="details">
							<span class="content">Achieved</span>
							<span class="value"><?php echo $achievedAppointments; ?></span>
						</div>
						<!-- /.details -->

						<i class="fa fa-play-circle more"></i>

					</a> <!-- /.dashboard-stat -->

				</div>
				<!-- /.col-md-3 -->


				<div class="col-md-3 col-sm-6">

					<a href="javascript:;" class="dashboard-stat secondary">
						<div class="visual">
							<i class="fa fa-exclamation-triangle"></i>
						</div>
						<!-- /.visual -->

						<div class="details">
							<span class="content">Canceled</span>
							<span class="value"><?php echo $canceledAppointments; ?></span>
						</div>
						<!-- /.details -->

						<i class="fa fa-play-circle more"></i>

					</a> <!-- /.dashboard-stat -->
				</div>

				<!-- /.col-md-3 -->
				<div class="col-md-3 col-sm-6">
					<a href="javascript:;" class="dashboard-stat fourth-stage">
						<div class="visual">
							<i class="fa fa-cog fa-spin"></i>
						</div>
						<!-- /.visual -->

						<div class="details">
							<span class="content">Server Load</span>
				<span class="value">
					<?php
					if (function_exists('sys_getloadavg'))
					{
						$cpuLoads = sys_getloadavg();
						$averageCpuLoad = 0;
						foreach ($cpuLoads as $cpuLoad)
						{

						}
						echo $cpuLoads[0] . "%";
					} else
					{
						echo "Unsupported.";
					}
					?>
				</span>
						</div>
						<!-- /.details -->

						<i class="fa fa-play-circle more"></i>
					</a> <!-- /.dashboard-stat -->

				</div>
				<!-- /.col-md-9 -->

			</div>
			<!-- /.row -->


			<div class="row">
				<div class="col-md-12">
					<div class="portlet">
						<div class="portlet-header">
							<h3><i class="fa fa-bar-chart-o"></i>Hourly</h3>
						</div>
						<div class="portlet-content">
							<div id="area-chart-appointments" class="chart-holder" style="height: 500"></div>
						</div>
					</div>
				</div>
            </div>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet">
						<div class="portlet-header">
							<h3><i class="fa fa-bar-chart-o"></i>Daily</h3>
						</div>
						<div class="portlet-content">
							<div id="appointments-date" class="chart-holder" style="height: 500"></div>
						</div>
					</div>
				</div>
            </div>
		</div>
		<!-- /#content-container -->

	</div>
	<!-- #content -->


	<?php include ROOT_PATH . "views/footer.php"; ?>
</div>
<!-- #wrapper #content -->

<?php include ROOT_PATH . "views/assets/footer_common.php"; ?>

<!-- dashboard assets -->
<script src="<?php echo BASE_URL; ?>assets/js/plugins/icheck/jquery.icheck.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/tableCheckable/jquery.tableCheckable.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/libs/raphael-2.1.2.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/morris/morris.min.js"></script>

<!--<script src="--><?php //echo BASE_URL; ?><!--assets/js/demos/charts/morris/area.js"></script>-->
<script src="<?php echo BASE_URL; ?>assets/js/demos/charts/morris/donut.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/sparkline/jquery.sparkline.min.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/fullcalendar/fullcalendar.min.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/demos/calendar.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/demos/dashboard.js"></script>

<script src="<?php echo BASE_URL; ?>assets/packages/pnotify/pnotify.custom.min.js"></script>

<script type="text/javascript">
$(function ()
{
    var hourlyAppointments = <?php echo $hourlyAppointmentsJson; ?>;
    var dailyAppointments = <?php echo $dailyAppointmentsJson; ?>;

    area(hourlyAppointments, 'area-chart-appointments');
    area(dailyAppointments, 'appointments-date');

    $(window).resize(App.debounce(area, 500));
});

function area(renderData, elementId)
{
    var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    $('#' + elementId).empty();
    Morris.Area({
        element       : elementId,
        behaveLikeLine: true,
        data          : renderData,
        xkey          : 'period',
        ykeys         : ['total'],
        labels        : ['total'],
        pointSize     : 3,
        hideHover     : 'auto',
        lineColors    : [App.chartColors[4], '#3fa67a', '#f0ad4e'],
        parseTime     : false
    });
}
</script>

</body>
</html>
