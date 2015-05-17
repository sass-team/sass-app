<?php
/**
 * @author Rizart Dokolalri <r.dokollari@gmail.com>
 * @since 25/4/2015
 */
require __DIR__ . '/app/init.php';

$general->loggedOutProtect();

$user->allowDoctorKatsas();

$section = "appointments-terms";

$pageTitle = "All Tutors";

if (empty($_GET['term-id']))
{
	$appointments = AppointmentFetcher::retrieveForCurrentTerms();
	$allReports = ReportFetcher::retrieveAllOfCurrTerms();
	$students = AppointmentHasStudentFetcher::retrieveAllOnCurTerm();
	$termTitle = 'current terms';
} else
{
	$termId = $_GET['term-id'];
	$appointments = AppointmentFetcher::retrieveForTerm($termId);
	$allReports = ReportFetcher::findWithTermId($termId);
	$students = AppointmentHasStudentFetcher::retrieveForTerm($termId);
	$termTitle = $appointments[0][TermFetcher::DB_TABLE . "_" . TermFetcher::DB_COLUMN_NAME];
}

$terms = TermFetcher::retrieveAll();

function getStudentsIds($students, $appointmentId)
{
	$studentsIds = "";
	foreach ($students as $student)
	{
		if (strcmp($student[AppointmentHasStudentFetcher::DB_COLUMN_APPOINTMENT_ID], $appointmentId) === 0)
		{
			$studentsIds = $student[StudentFetcher::DB_COLUMN_STUDENT_ID] . ", " . $studentsIds;
		}
	}

	return rtrim($studentsIds, ", ");
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
				<?php echo "Appointments - <strong>" . $pageTitle . "</strong>"; ?>
			</h1>
		</div>
		<!-- #content-header -->

		<div id="content-container">

			<div class="row">

				<div class="col-md-12">
					<?php
					if ( ! empty($successMessage)):
						?>
						<div class="alert alert-danger">
							<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
							<?php echo '<p>' . implode('</p><p>', $successMessage) . '</p>'; ?>
						</div>
					<?php endif; ?>
					<div class="portlet">

						<div class="portlet-header">

							<h3>
								<i class="fa fa-table"></i>
								All appointments for <?php echo $termTitle ?>
							</h3>

							<div class="portlet-tools pull-right">
								<div class="btn-group ">
									<button class="btn btn-default btn-sm dropdown-toggle" type="button"
									        data-toggle="dropdown">
										<i class="fa fa-group"></i> &nbsp;
										Select Term <span class="caret"></span>
									</button>

									<ul class="dropdown-menu" role="menu">
										<?php foreach ($terms as $term): ?>
											<li value="<?php echo $term[TermFetcher::DB_COLUMN_ID]; ?>">
												<a href="<?php echo BASE_URL . 'appointments-terms.php?term-id=' .
													$term[TermFetcher::DB_COLUMN_ID] ?>">
													<?php echo $term[TermFetcher::DB_COLUMN_NAME]; ?>
												</a>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							</div>


						</div>
						<!-- /.portlet-header -->

						<div class="portlet-content">
							<div class="table-responsive">
								<table
									class="table table-striped table-bordered table-hover table-highlight"
									data-provide="datatable"
									data-info="true"
									data-search="true"
									data-length-change="true"
									data-paginate="false"
									id="usersTable"
									>
									<thead>
									<tr>
										<th class="text-center" data-direction="desc" data-filterable="true"
										    data-sortable="true">ID
										</th>
										<th class="text-center" data-filterable="true" data-sortable="true">Tutor
										</th>
										<th class="text-center" data-filterable="true" data-sortable="true">Student(s)
											ID
										</th>
										<th class="text-center" data-filterable="true" data-sortable="true">Status
										</th>
										<th class="text-center" data-filterable="true" data-sortable="true">Report(s)
											Status
										</th>
										<th class="text-center" data-filterable="false" data-sortable="false">Page
										</th>
										<th class="text-center" data-direction="desc" data-filterable="true"
										    data-sortable="true">Duration
										</th>
										<th class="text-center" data-filterable="true" data-sortable="true">Course
										</th>
										<th class="text-center" data-filterable="true" data-sortable="true">Term
										</th>

									</tr>
									</thead>
									<tbody>

									<?php foreach ($appointments as $appointment):
										$studentsIds = getStudentsIds($students,
											$appointment[AppointmentFetcher::DB_COLUMN_ID]);

										$reports = Report::getWithAppointmentId($allReports, $appointment[AppointmentFetcher::DB_COLUMN_ID]);
										$dateStart = new DateTime($appointment[AppointmentFetcher::DB_COLUMN_START_TIME]);
										$dateEnd = new DateTime($appointment[AppointmentFetcher::DB_COLUMN_END_TIME]);
										?>

										<tr>
											<td class="text-center">
												<?php echo $appointment[AppointmentFetcher::DB_COLUMN_ID]; ?></td>
											<td class="text-center">
												<?php echo htmlentities($appointment[UserFetcher::DB_COLUMN_FIRST_NAME]) .
													" " . htmlentities($appointment[UserFetcher::DB_COLUMN_LAST_NAME]); ?></td>
											<td class="text-center"><?php echo $studentsIds; ?></td>
											<td class="text-center">
											<span
												class="label label-<?php echo $appointment[AppointmentFetcher::DB_COLUMN_LABEL_COLOR]; ?>">
											<?php echo $appointment[AppointmentFetcher::DB_COLUMN_LABEL_MESSAGE]; ?></span>
											</td>
											<td class="text-center">
												<?php foreach ($reports as $report): ?>
													<span
														class="label label-<?php echo $report[ReportFetcher::DB_COLUMN_LABEL_COLOR]; ?>">
		<?php echo $report[ReportFetcher::DB_COLUMN_LABEL_MESSAGE]; ?></span>
												<?php endforeach; ?>
											</td>
											<td class="text-center">
												<a data-toggle="modal"
												   href="<?php echo BASE_URL . "appointments/" .
													   $appointment[AppointmentFetcher::DB_COLUMN_ID]; ?>"
												   class="btn btn-default btn-sm center-block">
													<i class="fa fa-edit"></i> View
												</a>
											</td>
											<td class="text-center"><?php echo $dateStart->format('H:i') . " - " . $dateEnd->format('H:i, jS F Y'); ?></td>
											<td class="text-center"><?php echo htmlentities($appointment[CourseFetcher::DB_COLUMN_CODE]) . " " . htmlentities($appointment[CourseFetcher::DB_TABLE . "_" . CourseFetcher::DB_COLUMN_NAME]); ?>
											</td>
											<td class="text-center">
												<?php echo htmlentities($appointment[TermFetcher::DB_TABLE . "_" . TermFetcher::DB_COLUMN_NAME]); ?>
											</td>
										</tr>
									<?php endforeach; ?>

									</tbody>
								</table>
							</div>
							<!-- /.table-responsive -->

						</div>
						<!-- /.portlet-content -->


					</div>
					<!-- /.portlet -->

				</div>
				<!-- /.col -->

			</div>
			<!-- /.row -->


		</div>
		<!-- #content-container -->
	</div>
	<!-- #content -->

	<?php include ROOT_PATH . "views/footer.php"; ?>
</div>
<!-- #wrapper -->

<?php include ROOT_PATH . "views/assets/footer_common.php"; ?>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/select2/select2.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/datatables/DT_bootstrap.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/timepicker/bootstrap-timepicker.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/simplecolorpicker/jquery.simplecolorpicker.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/autosize/jquery.autosize.min.js"></script>

<script>
	$(document).ready(function () {
		var oTable = $('#usersTable').dataTable();

		// filter data from redirect
		<?php if (requestRequiresPendingAppointmentsAndReports()): ?>
		oTable.fnFilter('pending');
		<?php elseif(requestRequiresPendingAppointments()): ?>
		oTable.fnFilter('pending', 2);
		<?php elseif(requestRequiresPendingReports()): ?>
		oTable.fnFilter('pending fill', 3);
		<?php endif; ?>

	});
</script>
</body>
</html>
