<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) <year> <copyright holders>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * @author Rizart Dokollari
 * @author George Skarlatos
 * @since 8/16/14.
 */

?>
<?php
require __DIR__ . '/../../app/init.php';
$general->loggedOutProtect();


try {

// protect again any sql injections on url
	if (isset($_GET['id']) && preg_match("/^[0-9]+$/", $_GET['id'])) {
		$userId = $_GET['id'];
		$pageTitle = "Profile";
		if (($data = User::getSingle($db, $userId)) === FALSE) {
			header('Location: ' . BASE_URL . 'error-404');
			exit();
		}

		if (strcmp($data['type'], 'tutor') === 0) {
			$tutor = TutorFetcher::retrieveSingle($db, $userId);
			$curUser = new Tutor($db, $data['id'], $data['f_name'], $data['l_name'], $data['email'], $data['mobile'],
				$data['img_loc'], $data['profile_description'], $data['date'], $data['type'], $data['active'],
				$tutor[MajorFetcher::DB_COLUMN_NAME]);
		} else if (strcmp($data['type'], 'secretary') === 0) {
			$curUser = new Secretary($db, $data['id'], $data['f_name'], $data['l_name'], $data['email'], $data['mobile'],
				$data['img_loc'], $data['profile_description'], $data['date'], $data['type'], $data['active']);
		} else if (strcmp($data['type'], 'admin') === 0) {
			$curUser = new Admin($db, $data['id'], $data['f_name'], $data['l_name'], $data['email'], $data['mobile'],
				$data['img_loc'], $data['profile_description'], $data['date'], $data['type'], $data['active']);
		} else {
			throw new Exception("Something terrible has happened with the database. <br/>The software developers will tremble with fear.");
		}

	} else if (empty($_GET)) {
		$pageTitle = "All Users";
		$users = User::retrieveAll($db);
		$courses = CourseFetcher::retrieveAll($db);
	} else {
		header('Location: ' . BASE_URL . 'error-404');
		exit();
	}


} catch (Exception $e) {
	$errors[] = $e->getMessage();
}


function get($objects, $findId, $column) {
	foreach ($objects as $object) {
		if ($object[$column] === $findId) return $object;
	}

	return false;
}

function isEditBttnPressed() {
	return isset($_GET['id']) && preg_match('/^[0-9]+$/', $_GET['id']);
}

function isModifyBttnPressed() {
	return isset($_POST['hidden_submit_pressed']) && empty($_POST['hidden_submit_pressed']);
}

$section = "staff";
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
			<h1><?php echo $pageTitle; ?></h1>
		</div>
		<!-- #content-header -->


		<?php if (!empty($userId)): ?>
			<div id="content-container">


				<div class="row">

					<div class="col-md-9">

						<div class="row">

							<div class="col-md-4 col-sm-5">

								<div class="thumbnail">
									<img src="<?php echo BASE_URL . $curUser->getAvatarImgLoc(); ?>"
									     alt="Profile Picture"/>
								</div>
								<!-- /.thumbnail -->

								<br/>

							</div>
							<!-- /.col -->


							<div class="col-md-8 col-sm-7">

								<h2><?php echo $curUser->getFirstName() . " " . $curUser->getLastName(); ?></h2>

								<h4>Position: <?php echo ucfirst($curUser->getUserType()) ?></h4>

								<hr/>


								<ul class="icons-list">
									<li><i class="icon-li fa fa-envelope"></i> <?php echo $curUser->getEmail(); ?></li>
									<li><i class="icon-li fa fa-phone"></i>Mobile: <?php echo $curUser->getMobileNum() ?></li>
								</ul>
								<?php if ($curUser->isTutor()) { ?>

									Major: <strong><?php echo $curUser->getMajorId(); ?></strong>

								<?php } ?>
								<br/>
								<br/>

								<h3>About me</h3>

								<p><?php echo $curUser->getProfileDescription() ?></p>

								<hr/>

								<br/>

							</div>

						</div>

					</div>

				</div>
				<!-- /.row -->

			</div>
			<!-- /#content-container -->
		<?php else: ?>

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
										id="usersTable"
										>
										<thead>
										<tr>
											<th class="text-center" data-filterable="true" data-sortable="true"
											    data-direction="desc">
												Name
											</th>
											<th class="text-center" data-direction="asc" data-filterable="true"
											    data-sortable="false">
												Email
											</th>
											<th class="text-center" data-filterable="true" data-sortable="true">Position</th>
											<th class="text-center" data-filterable="true" data-sortable="false">Mobile</th>
											<th class="text-center" data-filterable="false" class="hidden-xs hidden-sm">Profile
											</th>

											<?php if (!$user->isTutor()): ?>
												<th class="text-center" data-filterable="false" class="hidden-xs hidden-sm">Teaching
												</th>
											<?php endif; ?>

											<?php if (!$user->isTutor()): ?>
												<th class="text-center" data-filterable="false" class="hidden-xs hidden-sm">Schedule
												</th>
											<?php endif; ?>


											<?php if ($user->isAdmin()): ?>
												<th class="text-center" data-filterable="false" class="hidden-xs hidden-sm">Data
												</th>
											<?php endif; ?>
										</tr>
										</thead>
										<tbody>

										<?php
										if (empty($errors) === true) {
											foreach (array_reverse($users) as $curUser) {
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
		<?php endif; ?>
	</div>
	<!-- #content -->

	<?php include ROOT_PATH . "app/views/footer.php"; ?>

</div>
<!-- #wrapper -->

<?php include ROOT_PATH . "app/views/assets/footer_common.php"; ?>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/select2/select2.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/icheck/jquery.icheck.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/datatables/DT_bootstrap.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/timepicker/bootstrap-timepicker.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/simplecolorpicker/jquery.simplecolorpicker.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/autosize/jquery.autosize.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/demos/form-extended.js"></script>

</body>
</html>


