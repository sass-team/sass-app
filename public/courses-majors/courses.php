<?php
require '../app/init.php';
$general->loggedOutProtect();

try {
	$course_db = new Courses($db->getConnection());
	$courses = $course_db->getCourses();
} catch (Exception $e) {
	$errors[] = $e->getMessage();
}

$page_title = "Manage Courses";
$section = "courses-majors";
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
			<h1>All Courses</h1>
		</div>
		<!-- #content-header -->


		<div id="content-container">
			<?php
			if (empty($errors) === false) {
				?>
				<div class="alert alert-danger">
					<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
					<strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>'; ?>
				</div>
			<?php
			} ?>
			<div class="row">

				<div class="col-md-6">
					
					<div class="portlet">

						<div class="portlet-header">

							<h3>
								<i class="fa fa-table"></i>
								View and Manage Courses
							</h3>

						</div>
						<!-- /.portlet-header -->

						<div class="portlet-content">

							<div class="table-responsive">

								<table
									class="table table-striped table-bordered table-hover table-highlight table-checkable"
									data-provide="datatable"
									data-display-rows="10"
									data-info="true"
									data-search="true"
									data-length-change="true"
									data-paginate="true"
									>
									<thead>
									<tr>
										<th data-filterable="true" data-sortable="true" data-direction="desc">Code</th>
										<th data-direction="asc" data-filterable="true" data-sortable="false">Name</th>
										<th>Action</th>
									</tr>
									</thead>
									<tbody>

									<?php
									if (empty($errors) === true) {
										foreach ($courses as $course) {
											include(ROOT_PATH . "app/views/partials/courses-table-data-view.html.php");

										}
									} ?>
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
				<div class="col-md-3 col-sidebar-right">
					
					<h1>Add a new Course</h1>
					<p class="lead"> You can also add a new course that is not already in the list.</p>
					<a href="javascript:;" class="btn btn-danger btn-jumbo btn-block">Add Course</a>

				</div>
			</div>
			<!-- /.row -->


		</div>
		<!-- /#content-container -->

	</div>
	<!-- /.col -->

<?php include ROOT_PATH . "app/views/footer.php"; ?>
</div>
<!-- #wrapper -->

<?php include ROOT_PATH . "app/views/assets/footer_common.php"; ?>

<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/datatables/DT_bootstrap.js"></script>

</body>
</html>