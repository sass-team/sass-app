<?php
require '../app/init.php';
$general->logged_out_protect();

$page_title = "View Users";
$section = "users";

try {
	$all_users = $users->getAll();
} catch (Exception $e) {
	$errors[] = $e->getMessage();
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
<?php require ROOT_PATH . 'app/views/head.php'; ?>
<body>
<div id="wrapper">
<?php
require ROOT_PATH . 'app/views/header.php';
require ROOT_PATH . 'app/views/sidebar.php';
?>


<div id="content">

	<div id="content-header">
		<h1>All Users</h1>
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

			<div class="col-md-12">

				<div class="portlet">

					<div class="portlet-header">

						<h3>
							<i class="fa fa-table"></i>
							Users
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
									<th data-filterable="true" data-sortable="true" data-direction="desc">Name</th>
									<th data-direction="asc" data-filterable="true" data-sortable="false">Email</th>
									<th data-filterable="true" data-sortable="true">Position</th>
									<th data-filterable="true" data-sortable="false">Mobile</th>
									<th data-filterable="false" class="hidden-xs hidden-sm">Profile</th>
									<th data-filterable="false" class="hidden-xs hidden-sm">Schedule</th>
									<th data-filterable="false" class="hidden-xs hidden-sm">Data</th>
								</tr>
								</thead>
								<tbody>

								<?php
								if (empty($errors) === true) {
									foreach (array_reverse($all_users) as $currentUserData) {
										include(ROOT_PATH . "app/views/partials/user-table-data-view.html.php");
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

		</div>
		<!-- /.row -->


	</div>
	<!-- /#content-container -->

</div>
<!-- /#content -->


<div id="styledModal" class="modal modal-styled fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="create-form" action="" class="form">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title">Create User Form</h3>
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
								<strong>User successfully created!</strong> <br/>
								An email is being sent to the email you just specified, with next steps to follow.
							</div>
						<?php } ?>

						<div class="portlet-content">

							<div class="row">


								<div class="col-sm-12">

									<div class="form-group">
										<h5>
											<i class="fa fa-edit"></i>
											<label for="first_name">First Name</label>
										</h5>
										<input type="text" id="first_name" name="first_name" class="form-control"
										       value="<?php if (isset($_POST['first_name'])) echo htmlentities($_POST['first_name']); ?>"
										       autofocus="on" required>
									</div>

									<div class="form-group">
										<h5>
											<i class="fa fa-edit"></i>
											<label for="last_name">Last Name</label>
										</h5>
										<input type="text" id="last_name" name="last_name" class="form-control"
										       value="<?php if (isset($_POST['last_name'])) echo htmlentities($_POST['last_name']); ?>"
										       required>
									</div>

									<div class="form-group">

										<i class="fa fa-envelope"></i>
										<label for="email">Email</label>
										<input type="email" required id="email" name="email" class="form-control"
										       value="<?php if (isset($_POST['email'])) echo htmlentities($_POST['email']); ?>">
									</div>

									<div class="form-group">
										<h5>
											<i class="fa fa-check"></i>
											Type
										</h5>


										<div class="radio" id="id_tutor_div">
											<label>
												<input type="radio" name="user_type" id="id_input_user_type" value="tutor"
												       class="icheck-input"
												       checked data-required="true">
												Tutor
											</label>

										</div>


										<div class="radio">
											<label>
												<input type="radio" name="user_type" value="secretary" class="icheck-input"
												       data-required="true">
												Secretary
											</label>
										</div>

										<div class="radio">
											<label>
												<input type="radio" name="user_type" value="admin" class="icheck-input"
												       data-required="true">
												Admin
											</label>
										</div>
									</div>
									<!-- /.form-group -->

									<div class="form-group">

										<h5>
											<i class="fa fa-tasks"></i>
											<label for="user_major">Tutor's Major</label>
										</h5>
										<select id="user_major" name="user_major" class="form-control">
											<option value="null">I don&#39;t know.</option>
											<?php foreach ($majors as $major) { ?>
												<?php   include(ROOT_PATH . "app/views/partials/majors-select-options-view.html.php");
											}
											?>
										</select>
									</div>


									<div class="form-group">

										<h5>
											<i class="fa fa-tasks"></i>
											<label for="teaching_courses_multi">Tutor's Courses</label>
										</h5>

										<select id="teaching_courses_multi" name="teaching_courses[]" class="form-control"
										        multiple>

											<?php
											foreach ($majors as $major) {
												include(ROOT_PATH . "app/views/partials/courses-select-options-view.html.php");
											}
											?>

										</select>
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
</div>
<!-- #wrapper -->

<?php include ROOT_PATH . "app/views/footer.php"; ?>
<?php include ROOT_PATH . "app/views/assets/footer_common.php"; ?>

<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo BASE_URL; ?>app/assets/js/plugins/datatables/DT_bootstrap.js"></script>

</body>
</html>

