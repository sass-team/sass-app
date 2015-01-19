<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/26/2014
 * Time: 10:41 AM
 */
?>

<?php
require __DIR__ . '/../app/init.php';
$general->loggedOutProtect();

$section = "appointments";
if (isset($_SESSION['success'])) {
	$successMessage[] = $_SESSION['success'];
	unset($_SESSION['success']);
}

if ($user->isTutor()) {
	$pageTitle = "" . $user->getFirstName() . " " . $user->getLastName();
	$appointments = AppointmentFetcher::retrieveAllOfCurrTermsByTutor($user->getId());
	$allReports = ReportFetcher::retrieveAllOfCurrTermsByTutor($user->getId());

	// TODO: add sepearate retrieval function for tutor. currently retrieves all students \/
	$students = AppointmentHasStudentFetcher::retrieveAllOnCurTerm();

} else {
	$pageTitle = "All Tutors";
	$appointments = AppointmentFetcher::retrieveAllOfCurrTerms();
	$allReports = ReportFetcher::retrieveAllOfCurrTerms();
	$students = AppointmentHasStudentFetcher::retrieveAllOnCurTerm();
}

function getStudentsIds($students, $appointmentId) {
	$studentsIds = "";
	foreach ($students as $student) {
		if (strcmp($student[AppointmentHasStudentFetcher::DB_COLUMN_APPOINTMENT_ID], $appointmentId) === 0) {
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
					if (!empty($successMessage)):
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
								All Appointments of Current Terms
							</h3>

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
										<th class="text-center" data-direction="desc" data-filterable="true" data-sortable="true">ID
										</th>
										<?php if (!$user->isTutor()) { ?>
											<th class="text-center" data-filterable="true" data-sortable="true">Tutor
											</th>
										<?php } ?>
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

									<?php



									if (empty($successMessage) === true) {
										if ($user->isTutor()) {
											foreach ($appointments as $appointment) {
												$studentsIds = getStudentsIds($students,
													$appointment[AppointmentFetcher::DB_TABLE . "_" .
													AppointmentFetcher::DB_COLUMN_ID]);

												$reports = Report::getWithAppointmentId($allReports, $appointment[AppointmentFetcher::DB_TABLE . "_" . AppointmentFetcher::DB_COLUMN_ID]);
												include(ROOT_PATH . "views/partials/appointments/table-data-by-tutor-view.html.php");
											}
										} else {
											foreach ($appointments as $appointment) {
												$studentsIds = getStudentsIds($students,
													$appointment[AppointmentFetcher::DB_TABLE . "_" .
													AppointmentFetcher::DB_COLUMN_ID]);
												$reports = Report::getWithAppointmentId($allReports, $appointment[AppointmentFetcher::DB_TABLE . "_" . AppointmentFetcher::DB_COLUMN_ID]);
												include(ROOT_PATH . "views/partials/appointments/table-data-view.html.php");
											}
										}
									}?>
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

</body>
</html>