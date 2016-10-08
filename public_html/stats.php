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

    if( isset($_GET['termIds'])  ){
        $termIds = explode(',', $_GET['termIds']);
        $currentTerms = TermFetcher::whereIdIn($termIds);
    } else {
        $currentTerms = TermFetcher::retrieveCurrTerm();
    }
    $allTerms =TermFetcher::retrieveAll();
    $currentTermIds = array_column($currentTerms, 'id');
	$now = new DateTime($currentTerms[0]['start_date']);
    $end = new DateTime($currentTerms[0]['end_date']);
	$nowString = $currentTerms[0]['start_date'];
    $endString = $currentTerms[0]['end_date'];

    $totalAppointments = AppointmentFetcher::countForTermIds($currentTermIds);
    $achievedAppointments = AppointmentFetcher::countForTermids($currentTermIds, ['complete']);
    $canceledAppointments = AppointmentFetcher::countForTermids($currentTermIds, ['canceled by student', 'disabled by admin', 'canceled by tutor']);
    $hourlyAppointments = AppointmentFetcher::retrieveByGroupedDateForTermIds($currentTermIds, ['hour']);
    $dailyAppointments = AppointmentFetcher::retrieveByGroupedDateForTermIds($currentTermIds, ['date']);
    $monthlyAppointments = AppointmentFetcher::retrieveByGroupedDateForTermIds($currentTermIds, ['month']);
    $yearlyAppointments = AppointmentFetcher::retrieveByGroupedDateForTermIds($currentTermIds, ['year']);

    foreach ( $hourlyAppointments as $appointment ){
        $date = date("H:i", strtotime($appointment['date']));
        $hourlyAppointmentsJson[] = ['period' => $date, 'total' => $appointment['total']];
    }
    $hourlyAppointmentsJson = json_encode($hourlyAppointmentsJson);

    foreach ( $dailyAppointments as $appointment ){
        $date = date("Y-m-d", strtotime($appointment['date']));
        $dailyAppointmentsJson[] = ['period' => $date, 'total' => $appointment['total']];
    }
    $dailyAppointmentsJson = json_encode($dailyAppointmentsJson);

    foreach ( $monthlyAppointments as $appointment ){
        $date = date("F", strtotime($appointment['date']));
        $monthlyAppointmentsJson[] = ['period' => $date, 'total' => $appointment['total']];
    }
    $monthlyAppointmentsJson = json_encode($monthlyAppointmentsJson);

    foreach ( $yearlyAppointments as $appointment ){
        $date = date("Y", strtotime($appointment['date']));
        $yearlyAppointmentsJson[] = ['period' => $date, 'total' => $appointment['total']];
    }
    $yearlyAppointmentsJson = json_encode($yearlyAppointmentsJson);


} catch (Exception $e)
{
	$errors[] = $e->getMessage();
}

// viewers
$pageTitle = "Workshop Sessions Stats";
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
<?php require ROOT_PATH . 'views/head-v2.php'; ?>
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
            <div class="row">
                    <div class="col-xs-6">
                        <div class="input-group">
                            <span class="input-group-addon"><label
                                    for="termId">Terms</label></span>
                            <select id="termIds" name="termIds" class="form-control" required  multiple="multiple">
                                <?php
                                foreach ($allTerms as $term)
                                {
                                    include(ROOT_PATH . "views/partials/term/select-options-view.html.php");
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="checkbox">
                            <label>
                            <input type="checkbox" id="select-all-terms-checkbox"> <h5>Select All</h5>
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <a href="#" class="btn btn-block btn-primary" id="btn-load-stats">
                            Load
                        </a>
                    </div>
                </div>
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
			<div class="row">
				<div class="col-md-12">
					<div class="portlet">
						<div class="portlet-header">
							<h3><i class="fa fa-bar-chart-o"></i>Monthly</h3>
						</div>
						<div class="portlet-content">
							<div id="appointments-monthly" class="chart-holder" style="height: 500"></div>
						</div>
					</div>
				</div>
            </div>
			<div class="row">
				<div class="col-md-12">
					<div class="portlet">
						<div class="portlet-header">
							<h3><i class="fa fa-bar-chart-o"></i>Yearly</h3>
						</div>
						<div class="portlet-content">
							<div id="appointments-yearly" class="chart-holder" style="height: 500"></div>
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


<script src="<?php echo BASE_URL; ?>assets/js/libs/raphael-2.1.2.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/morris/morris.min.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/demos/charts/morris/donut.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


<script type="text/javascript">
$(function ()
{
    // Charts
    var hourlyAppointments = <?php echo $hourlyAppointmentsJson; ?>;
    var dailyAppointments = <?php echo $dailyAppointmentsJson; ?>;
    var monthlyAppointments = <?php echo $monthlyAppointmentsJson; ?>;
    var yearlyAppointments = <?php echo $yearlyAppointmentsJson; ?>;

    area(hourlyAppointments, 'area-chart-appointments');
    area(dailyAppointments, 'appointments-date');
    area(monthlyAppointments, 'appointments-monthly');
    area(yearlyAppointments, 'appointments-yearly');

    $(window).resize(App.debounce(area, 500));


    // Terms
	var $termIds = $("#termIds");
	var $selectAllTermsCheckbox = $("#select-all-terms-checkbox");
    var possibleTerms = $('#termIds option').map(function() { return this.value });

	$termIds.select2();
    $selectAllTermsCheckbox.click(function(){
        if($(this).is(':checked') ){
            $termIds.val(possibleTerms).trigger("change");
        }else{
            $termIds.val('').trigger("change");
        }
    });
    var selectedTerms = <?php echo json_encode(isset($_GET['termIds']) ? explode(',', $_GET['termIds']) : array_column($currentTerms, 'id')); ?>;
    $termIds.val(selectedTerms).trigger("change");


    // Load button
    var defaulStatsUlr = '<?php echo BASE_URL; ?>stats';
    var $loadButton = $("#btn-load-stats");
    $loadButton.click(function(){
        termIds = $termIds.val();
        var url = termIds != null ? defaulStatsUlr + '?termIds=' + termIds.join(',') : defaulStatsUlr;
        window.location.href = url;
    });
});

function area(renderData, elementId, parseTime = false, ymin = 'auto')
{
    $('#' + elementId).empty();
    Morris.Area({
        element       : elementId,
        behaveLikeLine: true,
        data          : renderData,
        xkey          : 'period',
        ykeys         : ['total'],
        ymin          : ymin,
        labels        : ['total'],
        pointSize     : 3,
        hideHover     : 'auto',
        lineColors    : [App.chartColors[4], '#3fa67a', '#f0ad4e'],
        parseTime     : parseTime
    });
}
</script>

</body>
</html>
