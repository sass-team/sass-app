<?php
require __DIR__ . '/app/init.php';
$general->loggedOutProtect();
try
{
	date_default_timezone_set('Europe/Athens');
	$now = new DateTime();

	if (isBtnChangeNxtWeekPrsd())
	{
		$now->modify('+1 week');
	} else
	{
		if (isBtnChangeWeekAfterNxtWeekPrsd())
		{
			$now->modify('+2 week');
		} else
		{
			if (isBtnChangePrevWeekPrsd())
			{
				$now->modify('-1 week');
			}
		}
	}

	$startWeekDate = getWorkingDates($now->format('Y'), $now->format('W'));
	$endWeekDate = getWorkingDates($now->format('Y'), $now->format('W'), false);
	$courses = CourseFetcher::retrieveAll();
	//TODO: do not fetch all tutors when a tutors is viewing this page.
	$tutors = TutorFetcher::retrieveAll();

	if (!$user->isTutor())
	{
		$appointments = AppointmentFetcher::retrieveBetweenDates($startWeekDate, $endWeekDate);
		$countAppointmentsForCurWeek = sizeof($appointments);
		$countAchievedAppointmentsForCurWeek = Appointment::countWithLabelMessage($appointments, Appointment::LABEL_MESSAGE_COMPLETE);
		$canceledLabelMessagesForWeek = [Appointment::LABEL_MESSAGE_STUDENT_NO_SHOW,
			Appointment::LABEL_MESSAGE_TUTOR_CANCELED, Appointment::LABEL_MESSAGE_TUTOR_NO_SHOW, Appointment::LABEL_MESSAGE_STUDENT_CANCELED];
		$countCanceledAppointmentsForCurWeek = Appointment::countWithLabelMessages($appointments, $canceledLabelMessagesForWeek);

	} else
	{
		$appointments = AppointmentFetcher::retrieveTutorsBetweenDates($user->getId(), $startWeekDate, $endWeekDate);
		$countAppointmentsForCurWeek = sizeof($appointments);
		$countAchievedAppointmentsForCurWeek = Appointment::countTutorsWithLabelMessage($user->getId(), $appointments,
			Appointment::LABEL_MESSAGE_COMPLETE);
		$canceledLabelMessagesForWeek = [Appointment::LABEL_MESSAGE_STUDENT_NO_SHOW,
			Appointment::LABEL_MESSAGE_TUTOR_CANCELED, Appointment::LABEL_MESSAGE_TUTOR_NO_SHOW, Appointment::LABEL_MESSAGE_STUDENT_CANCELED];
		$countCanceledAppointmentsForCurWeek = Appointment::countTutorsWithLabelMessageS($user->getId(), $appointments, $canceledLabelMessagesForWeek);

	}

} catch (Exception $e)
{
	$errors[] = $e->getMessage();
}

// viewers
$pageTitle = "Dashboard";
$section = "dashboard";


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


function getAppointmentsForYearDay($appointments, $findYearDay)
{
	$appointmentsPerDay = [];

	foreach ($appointments as $appointment)
	{
		$tempDate = new DateTime($appointment[AppointmentFetcher::DB_COLUMN_START_TIME]);
		if ($tempDate->format('z') == $findYearDay->format('z'))
		{
			$appointmentsPerDay[] = $appointment;
		}
	}

	return $appointmentsPerDay;
}

/**
 * http://blog.ekini.net/2009/07/09/php-get-start-and-end-dates-of-a-week-from-datew/
 *
 * @param $year
 * @param $week
 * @param bool $start
 * @return bool|string
 */
function getWorkingDates($year, $week, $start = true)
{
	$from = date(Dates::DATE_FORMAT_IN, strtotime("{$year}-W{$week}-1")); //Returns the date of monday in week
	$to = date(Dates::DATE_FORMAT_IN, strtotime("{$year}-W{$week}-6"));   //Returns the date of saturday in week

	if ($start)
	{
		return $from;
	} else
	{
		return $to;
	}
	//return "Week {$week} in {$year} is from {$from} to {$to}.";
}

function isBtnChangeNxtWeekPrsd()
{
	return isset($_POST['hiddenNextWeek']) && empty($_POST['hiddenNextWeek']);
}

function isBtnChangeWeekAfterNxtWeekPrsd()
{
	return isset($_POST['weekAfterNext']) && empty($_POST['weekAfterNext']);
}

function isBtnChangePrevWeekPrsd()
{
	return isset($_POST['prevWeek']) && empty($_POST['prevWeek']);
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
				<h4 class="heading-inline">Weekly Workshop Sessions Stats
					&nbsp;&nbsp;
					<small>For the week of <?php echo date("M d", strtotime($startWeekDate)) . " - " . date("M d, o",
								strtotime('-1 day', strtotime($endWeekDate))); ?></small>
					&nbsp;&nbsp;
				</h4>

				<div class="btn-group ">
					<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
						<i class="fa fa-clock-o"></i> &nbsp;
						Change Week <span class="caret"></span>
					</button>

					<ul class="dropdown-menu" role="menu">
						<li>
							<a class="changeWeek">Previous Week</a>

							<form method="post" action="<?php echo BASE_URL; ?>">
								<input type="hidden" name="prevWeek"/>
							</form>
						</li>
						<li>
							<a class="changeWeek">Current Week</a>

							<form method="post" action="<?php echo BASE_URL; ?>">
								<input type="hidden" name="hiddenCurrentWeek"/>
							</form>
						</li>
						<li>
							<a class="changeWeek">Next Week</a>

							<form method="post" action="<?php echo BASE_URL; ?>">
								<input type="hidden" name="hiddenNextWeek"/>
							</form>
						</li>
						<li>
							<a class="changeWeek">The Week After Next</a>

							<form method="post" action="<?php echo BASE_URL; ?>">
								<input type="hidden" name="weekAfterNext"/>
							</form>
						</li>
					</ul>

				</div>

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

				<div class="col-md-9">

					<div class="portlet">

						<div class="portlet-header">

							<h3>
								<i class="fa fa-bar-chart-o"></i>
								Workshop Sessions - Area Chart
							</h3>

						</div>
						<!-- /.portlet-header -->

						<div class="portlet-content">
							<div id="area-chart-appointments" class="chart-holder" style="height: 250px"></div>
							<!-- /#bar-chart -->

						</div>
						<!-- /.portlet-content -->

					</div>
					<!-- /.portlet -->


					<div class="row">

						<div class="col-md-6">

							<div class="portlet">

								<div class="portlet-header">

									<h3>
										<i class="fa fa-table"></i>
										Recent Appointments
									</h3>

									<?php if (!$user->isTutor()): ?>
										<ul class="portlet-tools pull-right">
											<li>
												<a class="btn btn-sm btn-default"
												   href="<?php echo BASE_URL; ?>appointments/add">
													Add
												</a>
											</li>
										</ul>
									<?php endif; ?>
								</div>
								<!-- /.portlet-header -->

								<div class="portlet-content">

									<div class="table-responsive">
										<table class="table">
											<thead>
											<tr>
												<th>Facilitator</th>
												<th>Course</th>
												<th>Date</th>
												<th></th>
											</tr>
											</thead>
											<tbody>
											<?php
											$totalAllAppointmentsSize = sizeof($appointments);
											$totalRecentAppointments = $totalAllAppointmentsSize > 6 ? 6 : $totalAllAppointmentsSize;
											for ($i = 0; $i < $totalRecentAppointments; $i++):
												$appointmentId = $appointments[$i][AppointmentFetcher::DB_COLUMN_ID];
												$appointmentStartDate = new DateTime($appointments[$i][AppointmentFetcher::DB_COLUMN_START_TIME]);
												$course = get($courses, $appointments[$i][AppointmentFetcher::DB_COLUMN_COURSE_ID], CourseFetcher::DB_COLUMN_ID);
												$tutor = get($tutors, $appointments[$i][AppointmentFetcher::DB_COLUMN_TUTOR_USER_ID], TutorFetcher::DB_COLUMN_USER_ID);
												//09/21/2013
												?>
												<tr>
													<td><?php echo $tutor[UserFetcher::DB_COLUMN_LAST_NAME]; ?></td>
													<td><?php echo $course[CourseFetcher::DB_COLUMN_CODE]; ?></td>
													<td><?php echo $appointmentStartDate->format(App::getDefaultDateFormat()); ?></td>
													<td>
														<a href="<?php echo BASE_URL . "appointments/$appointmentId"; ?>"
														   class="btn btn-xs btn-tertiary">View &nbsp;&nbsp;<i
																class="fa fa-chevron-right"></i></a></td>
												</tr>
											<?php endfor; ?>
											</tbody>
										</table>
									</div>
									<!-- /.table-responsive -->

									<hr/>

									<a href="<?php echo BASE_URL; ?>appointments/list" class="btn btn-sm btn-secondary">View
										All
										Appointments</a>


								</div>
								<!-- /.portlet-content -->

							</div>
							<!-- /.portlet -->


						</div>
						<!-- /.col-md-4 -->


						<div class="col-md-6"></div>
						<!-- /.col-md-4 -->


					</div>
					<!-- /.row -->


				</div>
				<!-- /.row -->

				<div class="col-md-3">
					<div class="portlet">

						<div class="portlet-header">
							<h3>
								<i class="fa fa-bar-chart-o"></i>
								Workshop Sessions - Donut Chart
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
		var stack_bottomright = {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25};
		var pnotifySettingsInfo = {
			title        : 'Tutor Notice',
			text         : '',
			type         : 'info',
			delay        : 13000,
			history      : {history: true, menu: true},
			addclass     : "stack-bottomright", // This is one of the included default classes.
			stack        : stack_bottomright,
			animation    : "slide",
			animate_speed: "slow"
		};
		var pnotifySettingsWarning = {
			title        : 'Tutor Warning',
			text         : '',
			type         : 'error',
			delay        : 7000,
			history      : {history: true, menu: true},
			addclass     : "stack-bottomright", // This is one of the included default classes.
			stack        : stack_bottomright,
			animation    : "slide",
			animate_speed: "slow"
		};
		pnotifySettingsInfo.text = "Please notice tutors daily receive one email if they have any pending appointments/reports. This email contain " +
		"links to their corresponding pending appointments & reports to be filled.<br/> If you had pending " +
		"appointments/reports and didn't receive your email please contact the secretariat or submit a <a href='<?php echo App::getGithubNewIssueUrl()?>'>new issue</a>." ;
		new PNotify(pnotifySettingsInfo);
		$('.changeWeek').on('click', function ()
		{
			var $a = $(this);
			var $form = $a.next();
			$form.submit();
		});

		if (!$('#workshop-chart').length)
		{
			return false;
		}
		if (!$('#area-chart-appointments').length)
		{
			return false;
		}

		workshop();
		area();

		$(window).resize(App.debounce(workshop, 325));
		$(window).resize(App.debounce(area, 250));
	});

	function workshop()
	{
		$('#workshop-chart').empty();

		Morris.Donut({
			element  : 'workshop-chart',
			data     : [
				{
					label: 'Successful',
					value: <?php echo ((int)$countAppointmentsForCurWeek) === 0 ? 0 :
						(int)(($countAchievedAppointmentsForCurWeek * 100 / $countAppointmentsForCurWeek)); ?>
				},
				{
					label: 'Canceled',
					value: <?php echo ((int)$countAppointmentsForCurWeek) === 0 ? 0 :
						(int)($countCanceledAppointmentsForCurWeek * 100 / $countAppointmentsForCurWeek); ?>

				},
				{
					label: 'Pending',
					value: <?php echo ((int)$countAppointmentsForCurWeek) === 0 ? 0 :
						(int)((($countAppointmentsForCurWeek-
						$countCanceledAppointmentsForCurWeek-$countAchievedAppointmentsForCurWeek) * 100)/$countAppointmentsForCurWeek); ?>
				}
			],
			colors   : ['#0BA462', '#f0ad4e', '#888888'],
			hideHover: true,
			formatter: function (y)
			{
				return y + "%"
			}
		});
	}

	function area()
	{
		$('#area-chart-appointments').empty();

		Morris.Area({
			element       : 'area-chart-appointments',
			behaveLikeLine: true,
			data          : [
				<?php
				$day = clone $now;
				$data = "";
				$lastWorkingDay = 5;
				for ($i = 1; $i <= $lastWorkingDay; $i++):
					list($weekOfYear, $year, $dateFormatted, $appointmentsForDay, $countAppointmentsForDay, $countAchievedAppointmentsForDay,
						$canceledLabelMessagesForDay, $countCanceledAppointmentsForDay) = countLabelsStatusAppointments($day, $i, $appointments);
					$data = $data . "{period: '$dateFormatted', planned: $countAppointmentsForDay, achieved: $countAchievedAppointmentsForDay, canceled: $countCanceledAppointmentsForDay},";
				endfor;
				$data = rtrim($data, ",");
				echo $data;
				?>

			],
			xkey          : 'period',
			ykeys         : ['planned', 'achieved', 'canceled'],
			labels        : ['planned', 'achieved', 'canceled'],
			pointSize     : 3,
			hideHover     : 'auto',
			lineColors    : [App.chartColors[4], '#3fa67a', '#f0ad4e'],
			parseTime     : false
		});
	}
</script>

</body>
</html>
