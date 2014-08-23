<?php
require '../app/init.php';
$general->loggedOutProtect();

// redirect if user elevation is not that of secretary or tutor
if (!$user->isAdmin()) {
	header('Location: ' . BASE_URL . "error-403");
	exit();
}

try {
	$courses = Course::retrieveAll($db);
} catch (Exception $e) {
	$errors[] = $e->getMessage();
}

function is_create_bttn_Pressed() {
	return isset($_POST['hidden_submit_pressed']) && empty($_POST['hidden_submit_pressed']);
}

if (isSaveBttnPressed()) {
	$course_code = trim($_POST['course_code']);
	$course_name = trim($_POST['course_name']);

	try {
		$user->createCourse($course_code, $course_name);
	} catch (Exception $e) {
		$errors[] = $e->getMessage();
	}
}


function isSaveBttnPressed() {
	return isset($_POST['hidden_submit_pressed']) && empty($_POST['hidden_submit_pressed']);
}

$page_title = "Manage Courses";
$section = "academia";
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
					<a data-toggle="modal" href="#addCourse" class="btn btn-danger btn-jumbo btn-block">Add Course</a>

				</div>
			</div>
			<!-- /.row -->


		</div>
		<!-- /#content-container -->

	</div>
	<!-- /.col -->

	<div id="addCourse" class="modal modal-styled fade">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<form method="post" id="create-form" action="" class="form">

					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3 class="modal-title">Create Course</h3>
					</div>
					<div class="modal-body">
						<div class="portlet">
							<?php
						if (empty($errors) === false) {
							?>
							<div class="alert alert-danger">
								<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
								<strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>';
								?>
							</div>
						<?php
						} else if (is_create_bttn_Pressed()) {
							?>
							<div class="alert alert-success">
								<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
								<strong>Course successfully created!</strong> <br/>
							</div>
						<?php } ?>
							<div class="portlet-content">
								<div class="row">
									<div class="col-sm-12">

										<div class="form-group">
											<h5>
												<i class="fa fa-edit"></i>
												<label for="course_code">Course Code</label>
											</h5>
											<input type="text" id="course_code" name="course_code" class="form-control"
											value="<?php if (isset($_POST['course_code'])) echo htmlentities($_POST['course_code']); ?>"
											autofocus="on" required>
										</div>

										<div class="form-group">
											<h5>
												<i class="fa fa-edit"></i>
												<label for="course_name">Course Name</label>
											</h5>
											<input type="text" id="course_name" name="course_name" class="form-control"
											value="<?php if (isset($_POST['course_name'])) echo htmlentities($_POST['course_name']); ?>"
											required>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-tertiary" data-dismiss="modal">Close</button>
						<input type="hidden" name="hidden_submit_pressed">
						<button type="submit" class="btn btn-primary">Create</button>
					</div>
				</form>

			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->


<?php include ROOT_PATH . "app/views/footer.php"; ?>
</div>
<!-- #wrapper -->

<?php include ROOT_PATH . "app/views/assets/footer_common.php"; ?>

<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/datatables/DT_bootstrap.js"></script>
</body>
</html>