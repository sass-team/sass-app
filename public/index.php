<?php
require 'app/init.php';
$general->logged_out_protect();

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
<?php require ROOT_PATH . 'app/views/header.php'; ?>
<div id="content">

<div id="content-header">
	<h1>Dashboard</h1>
</div>
<!-- #content-header -->


<div id="content-container">

<div>
	<h4 class="heading-inline">Weekly Workshop Sessions Stats
		&nbsp;&nbsp;
		<small>For the week of Jun 15 - Jun 22, 2011</small>
		&nbsp;&nbsp;</h4>

	<div class="btn-group ">
		<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
			<i class="fa fa-clock-o"></i> &nbsp;
			Change Week <span class="caret"></span>
		</button>
		<ul class="dropdown-menu" role="menu">
			<li><a href="javascript:;">Action</a></li>
			<li><a href="javascript:;">Another action</a></li>
			<li><a href="javascript:;">Something else here</a></li>
			<li class="divider"></li>
			<li><a href="javascript:;">Separated link</a></li>
		</ul>
	</div>
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
				<span class="value">40</span>
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
				<span class="value">36</span>
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
				<span class="value">4</span>
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

<div class="portlet">

	<div class="portlet-header">

		<h3>
			<i class="fa fa-bar-chart-o"></i>
			Workshop Sessions
		</h3>

	</div>
	<!-- /.portlet-header -->

	<div class="portlet-content">

		<div class="pull-left">
			<div class="btn-group" data-toggle="buttons">
				<label class="btn btn-sm btn-default">
					<input type="radio" name="options" id="option1"> Day
				</label>
				<label class="btn btn-sm btn-default">
					<input type="radio" name="options" id="option2"> Week
				</label>
				<label class="btn btn-sm btn-default active">
					<input type="radio" name="options" id="option3"> Month
				</label>
			</div>

			&nbsp;

			<div class="btn-group">
				<button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
					Custom Date
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					<li><a href="javascript:;">Dropdown link</a></li>
					<li><a href="javascript:;">Dropdown link</a></li>
				</ul>
			</div>

		</div>

		<div class="pull-right">
			<div class="btn-group">
				<button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
					<i class="fa fa-cog"></i> &nbsp;&nbsp;<span class="caret"></span>
				</button>
				<ul class="dropdown-menu pull-right" role="menu">
					<li><a href="javascript:;">Action</a></li>
					<li><a href="javascript:;">Another action</a></li>
					<li><a href="javascript:;">Something else here</a></li>
					<li class="divider"></li>
					<li><a href="javascript:;">Separated link</a></li>
				</ul>
			</div>
		</div>

		<div class="clear"></div>
		<hr/>


		<div id="workshop-session-chart" class="chart-holder" style="height: 250px"></div>
		<!-- /#bar-chart -->

	</div>
	<!-- /.portlet-content -->

</div>
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
						<a href="/users/create" name="forgot" class="btn btn-sm btn-default">
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
								<a href="javascript:void(0);" class="btn btn-xs btn-primary" data-original-title="Approve"
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
								<a href="javascript:void(0);" class="btn btn-xs btn-primary" data-original-title="Approve">
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
								<a href="javascript:void(0);" class="btn btn-xs btn-primary" data-original-title="Approve"
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
								<a href="javascript:void(0);" class="btn btn-xs btn-primary" data-original-title="Approve">
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
								<a href="javascript:void(0);" class="btn btn-xs btn-primary" data-original-title="Approve"
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
								<a href="javascript:void(0);" class="btn btn-xs btn-primary" data-original-title="Approve"
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


	<div class="portlet">

		<div class="portlet-header">

			<h3>
				<i class="fa fa-compass"></i>
				Traffic Overview
			</h3>

		</div>
		<!-- /.portlet-header -->

		<div class="portlet-content">


			<div class="progress-stat">

				<div class="stat-header">

					<div class="stat-label">
						% Tutors
					</div>
					<!-- /.stat-label -->

					<div class="stat-value">
						67.7%
					</div>
					<!-- /.stat-value -->

				</div>
				<!-- /stat-header -->

				<div class="progress progress-striped active">
					<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="77" aria-valuemin="0"
					     aria-valuemax="100" style="width: 77%">
						<span class="sr-only">67.74% Tutors</span>
					</div>
				</div>
				<!-- /.progress -->

			</div>
			<!-- /.progress-stat -->

			<div class="progress-stat">

				<div class="stat-header">

					<div class="stat-label">
						% Secretaries
					</div>
					<!-- /.stat-label -->

					<div class="stat-value">
						23.2%
					</div>
					<!-- /.stat-value -->

				</div>
				<!-- /stat-header -->

				<div class="progress progress-striped active">
					<div class="progress-bar progress-bar-tertiary" role="progressbar" aria-valuenow="33"
					     aria-valuemin="0"
					     aria-valuemax="100" style="width: 23%">
						<span class="sr-only">23% Secretaries</span>
					</div>
				</div>
				<!-- /.progress -->

			</div>
			<!-- /.progress-stat -->

			<div class="progress-stat">

				<div class="stat-header">

					<div class="stat-label">
						Administrators
					</div>
					<!-- /.stat-label -->

					<div class="stat-value">
						5.7%
					</div>
					<!-- /.stat-value -->

				</div>
				<!-- /stat-header -->

				<div class="progress progress-striped active">
					<div class="progress-bar progress-bar-secondary" role="progressbar" aria-valuenow="42"
					     aria-valuemin="0"
					     aria-valuemax="100" style="width: 5.7%">
						<span class="sr-only">5.7% Administrators</span>
					</div>
				</div>
				<!-- /.progress -->

			</div>
			<!-- /.progress-stat -->

			<div class="progress-stat">

				<div class="stat-header">

					<div class="stat-label">
						Bounce Rate
					</div>
					<!-- /.stat-label -->

					<div class="stat-value">
						3.4%
					</div>
					<!-- /.stat-value -->

				</div>
				<!-- /stat-header -->

				<div class="progress progress-striped active">
					<div class="progress-bar progress-bar-secondary" role="progressbar" aria-valuenow="42"
					     aria-valuemin="0"
					     aria-valuemax="100" style="width: 3.4%">
						<span class="sr-only">3.4% Administrators</span>
					</div>
				</div>
				<!-- /.progress -->

			</div>
			<!-- /.progress-stat -->

			<br/>

		</div>
		<!-- /.portlet-content -->

	</div>
	<!-- /.portlet -->


	<!-- /.portlet -->

</div>
<!-- /.col -->

</div>
<!-- /.row -->

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