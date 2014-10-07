<?php
require __DIR__ . '/../app/init.php';
$general->loggedOutProtect();
try {
	date_default_timezone_set('Europe/Athens');
	$now = new DateTime();
	$startWeekDate = getWorkingDates($now->format('Y'), $now->format('W'));
	$endWeekDate = getWorkingDates($now->format('Y'), $now->format('W'), false);

	if (!$user->isTutor()) {
		$courses = CourseFetcher::retrieveAll($db);
		$appointments = AppointmentFetcher::retrieveBetweenDates($db, $startWeekDate,
			$endWeekDate);

		$countAppointmentsForCurWeek = sizeof($appointments);
		$countAchievedAppointmentsForCurWeek = Appointment::countWithLabelMessage($appointments, Appointment::LABEL_MESSAGE_COMPLETE);
		$canceledLabelMessages = array(Appointment::LABEL_MESSAGE_STUDENT_NO_SHOW,
			Appointment::LABEL_MESSAGE_TUTOR_CANCELED, Appointment::LABEL_MESSAGE_TUTOR_NO_SHOW, Appointment::LABEL_MESSAGE_STUDENT_CANCELED);
		$countCanceledAppointmentsForCurWeek = Appointment::countWithLabelMessages($appointments, $canceledLabelMessages);

	} else {
		$appointments = TutorFetcher::retrieveAllAppointments($db, $user->getId());
		$courses = TutorFetcher::retrieveAllAppointments($db, $user->getId());

	}

} catch (Exception $e) {
	$errors[] = $e->getMessage();
}

// viewers
$pageTitle = "Dashboard - SASS App";
$section = "dashboard";


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


/**
 * http://blog.ekini.net/2009/07/09/php-get-start-and-end-dates-of-a-week-from-datew/
 *
 * @param $year
 * @param $week
 * @param bool $start
 * @return bool|string
 */
function getWorkingDates($year, $week, $start = true) {
	$from = date(Dates::DATE_FORMAT_IN, strtotime("{$year}-W{$week}-1")); //Returns the date of monday in week
	$to = date(Dates::DATE_FORMAT_IN, strtotime("{$year}-W{$week}-6"));   //Returns the date of saturday in week

	if ($start) {
		return $from;
	} else {
		return $to;
	}
	//return "Week {$week} in {$year} is from {$from} to {$to}.";
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
	<h1>Dashboard</h1>
</div>
<!-- #content-header -->

<div id="content-container">

<div>
	<h4 class="heading-inline">Weekly Workshop Sessions Stats
		&nbsp;&nbsp;
		<small>For the week of <?php echo date("M d", strtotime($startWeekDate)) . " - " . date("M d, o",
					strtotime('-1 day', strtotime($endWeekDate))); ?></small>
		&nbsp;&nbsp;</h4>

	<?php
	if (empty($errors) === false) {
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
				<span class="value"><?php echo $countAppointmentsForCurWeek; ?></span>
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
				<span class="value"><?php echo $countAchievedAppointmentsForCurWeek; ?></span>
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
				<span class="value"><?php echo $countCanceledAppointmentsForCurWeek; ?></span>
			</div>
			<!-- /.details -->

			<i class="fa fa-play-circle more"></i>

		</a> <!-- /.dashboard-stat -->
		</a> <!-- /.dashboard-stat -->
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
					if (function_exists('sys_getloadavg')) {
						$load = sys_getloadavg();
						echo $load[0] . "%";
					} else {
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

<div class="col-md-9">

<div class="portlet"></div>
<!-- /.portlet -->


<div class="row">

	<div class="col-md-12">

		<div class="portlet">

			<div class="portlet-header">

				<h3>
					<i class="fa fa-group"></i>
					Recent Signups
				</h3>

				<ul class="portlet-tools pull-right">
					<li>
						<a href="<?php echo BASE_URL . "staff/add"; ?>" name="forgot" class="btn btn-sm btn-default">
							Add User
						</a>

					</li>
				</ul>

			</div>
			<!-- /.portlet-header -->

			<div class="portlet-content">


				<div class="table-responsive">

					<table id="user-signups" class="table table-striped table-checkable">
						<thead>
						<tr>
							<th class="checkbox-column">
								<input type="checkbox" id="check-all" class="icheck-input"/>
							</th>
							<th class="hidden-xs">First Name
							</th>
							<th>Last Name</th>
							<th>Status
							</th>

							<th class="align-center">Email - Approve
							</th>

						</tr>
						</thead>

						<tbody>
						<tr class="">
							<td class="checkbox-column">
								<input type="checkbox" name="actiony" value="joey" class="icheck-input">
							</td>

							<td class="hidden-xs">Joey</td>
							<td>Greyson</td>
							<td><span class="label label-success">Approved</span></td>
							<td class="align-center">
								<a href="javascript:void(0);" class="btn btn-xs btn-primary"
								   data-original-title="Approve"
								   disabled>
									<i class="fa fa-check"></i>
								</a>
							</td>
						</tr>

						<tr class="">
							<td class="checkbox-column">
								<input type="checkbox" name="actiony" value="wolf" class="icheck-input">
							</td>
							<td class="hidden-xs">Wolf</td>
							<td>Bud</td>
							<td><span class="label label-default">Pending</span>
							</td>
							<td class="align-center">
								<a href="javascript:void(0);" class="btn btn-xs btn-primary"
								   data-original-title="Approve">
									<i class="fa fa-check"></i>
								</a>
							</td>
						</tr>


						<tr class="">
							<td class="checkbox-column">
								<input type="checkbox" name="actiony" value="sam" class="icheck-input">
							</td>

							<td class="hidden-xs">Sam</td>
							<td>Mitchell</td>
							<td><span class="label label-success">Approved</span></td>
							<td class="align-center">
								<a href="javascript:void(0);" class="btn btn-xs btn-primary"
								   data-original-title="Approve"
								   disabled>
									<i class="fa fa-check"></i>
								</a>
							</td>
						</tr>
						<tr class="">
							<td class="checkbox-column">
								<input type="checkbox" value="carlos" name="actiony" class="icheck-input">
							</td>
							<td class="hidden-xs">Carlos</td>
							<td>Lopez</td>
							<td><span class="label label-success">Approved</span></td>
							<td class="align-center">
								<a href="javascript:void(0);" class="btn btn-xs btn-primary"
								   data-original-title="Approve">
									<i class="fa fa-check"></i>
								</a>
							</td>
						</tr>


						<tr class="">
							<td class="checkbox-column">
								<input type="checkbox" name="actiony" value="rob" class="icheck-input">
							</td>
							<td class="hidden-xs">Rob</td>
							<td>Johnson</td>
							<td><span class="label label-default">Pending</span></td>
							<td class="align-center">
								<a href="javascript:void(0);" class="btn btn-xs btn-primary"
								   data-original-title="Approve"
								   disabled>
									<i class="fa fa-check"></i>
								</a>
							</td>
						</tr>
						<tr class="">
							<td class="checkbox-column">
								<input type="checkbox" value="mike" name="actiony" class="icheck-input">
							</td>
							<td class="hidden-xs">Mike</td>
							<td>Jones</td>
							<td><span class="label label-default">Pending</span></td>
							<td class="align-center">
								<a href="javascript:void(0);" class="btn btn-xs btn-primary"
								   data-original-title="Approve"
								   disabled>
									<i class="fa fa-check"></i>
								</a>
							</td>
						</tr>

						</tbody>
					</table>


				</div>
				<!-- /.table-responsive -->

				<hr/>

				Apply to Selected: &nbsp;&nbsp;
				<select id="apply-selected" name="table-select" class="ui-select2" style="width: 125px">
					<option value="">Select Action</option>
					<option value="approve">Re-send validation email.</option>

				</select>

			</div>
			<!-- /.portlet-content -->

		</div>
		<!-- /.portlet -->

	</div>
	<!-- /.col-md-4 -->


</div>
<!-- /.row -->

</div>
<!-- /.col-md-9 -->


<div class="col-md-3">
	<div class="portlet">

		<div class="portlet-header">
			<h3>
				<i class="fa fa-bar-chart-o"></i>
				Workshop Session Chart
			</h3>
		</div>
		<!-- /.portlet-header -->

		<div class="portlet-content">
			<div id="workshop-chart" class="chart-holder" style="height: 250px"></div>
		</div>
		<!-- /.portlet-content -->

	</div>
	<!-- /.portlet -->


	<div class="portlet"></div>
	<!-- /.portlet -->


	<!-- /.portlet -->

</div>
<!-- /.col -->

</div>
<!-- /.row -->


</div>
<!-- /#content-container -->


</div>
<!-- #content -->


<?php include ROOT_PATH . "views/footer.php"; ?>
</div>
<!-- #wrapper<!-- #content -->

<?php include ROOT_PATH . "views/assets/footer_common.php"; ?>

<!-- dashboard assets -->
<script src="<?php echo BASE_URL; ?>assets/js/plugins/icheck/jquery.icheck.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/tableCheckable/jquery.tableCheckable.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/libs/raphael-2.1.2.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/morris/morris.min.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/demos/charts/morris/area.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/demos/charts/morris/donut.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/sparkline/jquery.sparkline.min.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/fullcalendar/fullcalendar.min.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/demos/calendar.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/demos/dashboard.js"></script>

<script type="text/javascript">
	$(function () {
		if (!$('#workshop-chart').length) {
			return false;
		}
		workshop();
		$(window).resize(App.debounce(workshop, 325));

		function workshop() {
			$('#workshop-chart').empty();

			Morris.Donut({
				element: 'workshop-chart',
				data: [
					{
						label: 'Successful',
						value: <?php echo (int)(($countAchievedAppointmentsForCurWeek * 100 / $countAppointmentsForCurWeek)); ?>
					},
					{
						label: 'Canceled',
						value: <?php echo ((int)$countCanceledAppointmentsForCurWeek) === 0 ? 0 :
						(int)($countCanceledAppointmentsForCurWeek * 100 / $countAppointmentsForCurWeek); ?>

					},
					{
						label: 'Pending',
						value: <?php echo ((int)$countCanceledAppointmentsForCurWeek) === 0 ? 0 :
						(int)((($countAppointmentsForCurWeek-
						$countCanceledAppointmentsForCurWeek-$countAchievedAppointmentsForCurWeek) * 100)/$countAppointmentsForCurWeek); ?>
					}
				],
				colors: ['#0BA462', '#f0ad4e', '#888888'],
				hideHover: true,
				formatter: function (y) {
					return y + "%"
				}
			});
		}

	});
</script>

</body>
</html>
