<?php
require __DIR__ . '/../../app/init.php';
$general->loggedOutProtect();

// redirect if user elevation is not that of secretary or admin
if ($user->isTutor()) {
	header('Location: ' . BASE_URL . "error-403");
	exit();
}

function is_create_bttn_Pressed() {
	return isset($_POST['hidden_submit_pressed']) && empty($_POST['hidden_submit_pressed']);
}

try {
	$majors = MajorFetcher::retrieveMajorsToEdit($db);

	if (isBtnUpdatePrsd()) {
		$updateDone = false;
		$majorId = trim($_POST['updateMajorIdModal']);

		$newMajorCode = trim($_POST['majorCodeUpdate']);
		$newMajorName = trim($_POST['majorNameUpdate']);
		$updateDone = false;

		if (($major = getMajor($majorId, $majors)) !== false) {
			$oldMajorCodeName = $major[MajorFetcher::DB_COLUMN_CODE];
			$oldMajorName = $major[MajorFetcher::DB_COLUMN_NAME];

			if (strcmp($newMajorName, $oldMajorName) !== 0) {
				$updateDone = true;
				Major::updateName($db, $majorId, $newMajorName);
			}

			if (strcmp($newMajorCode, $oldMajorCodeName) !== 0) {
				$updateDone = true;
				Major::updateCode($db, $majorId, $newMajorCode);
			}

			if (!$updateDone) {
				throw new Exception("No new data inputted. Process aborted.");
			}

			//
			header('Location: ' . BASE_URL . 'academia/majors/success');

		} else {
			throw new Exception("Either you're trying to hack this app or something wrong went. In either case the
            developers we just notified about this");
		}

	} else if (isBtnSavePrsd()) {
		$newMajorCode = trim($_POST['major_code']);
		$newMajorName = trim($_POST['major_name']);


		Major::create($db, $newMajorCode, $newMajorName);
		header('Location: ' . BASE_URL . 'academia/majors/success');
		exit();
	} else if (isBtnDeletePrsd()) {
		Major::delete($db, $_POST['delMajorIdModal']);
		header('Location: ' . BASE_URL . 'academia/majors/success');
		exit();
	}

} catch (Exception $e) {
	$errors[] = $e->getMessage();
}

/**
 * http://stackoverflow.com/a/4128377/2790481
 *
 * @param $needle
 * @param $courses
 * @param bool $strict
 * @return bool
 */
function getMajor($needle, $majors, $strict = false) {
	foreach ($majors as $major) {
		if (($strict ? $major === $needle : $major == $needle) ||
			(is_array($major) && getMajor($needle, $major, $strict))
		) {
			return $major;
		}
	}

	return false;
}

function isBtnSavePrsd() {
	return isset($_POST['hidden_submit_pressed']) && empty($_POST['hidden_submit_pressed']);
}

function isModificationSuccessful() {
	return isset($_GET['success']) && strcmp($_GET['success'], 'y1!qp' === 0);
}

function isBtnDeletePrsd() {
	return isset($_POST['hiddenSubmitDeleteMajor']) && empty($_POST['hiddenSubmitDeleteMajor']);
}

function isBtnUpdatePrsd() {
	return isset($_POST['hiddenUpdatePrsd']) && empty($_POST['hiddenUpdatePrsd']);
}

$page_title = "Manage Majors";
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
		<h1>All Majors</h1>
	</div>
	<!-- #content-header -->


	<div id="content-container">
		<?php
		if (empty($errors) === false): ?>
			<div class="alert alert-danger">
				<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
				<strong>Oh snap!</strong>
				<?php echo '<p>' . implode('</p><p>', $errors) . '</p>'; ?>
			</div>
		<?php elseif (isModificationSuccessful()): ?>
			<div class="alert alert-success">
				<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
				<strong>Data successfully modified</strong> <br/>
			</div>
		<?php endif; ?>
		<div class="row">

			<div class="col-md-12">

				<div class="portlet">

					<div class="portlet-header">

						<h3>
							<i class="fa fa-book"></i>
							View and Manage Majors
						</h3>

						<ul class="portlet-tools pull-right">
							<li>
								<a data-toggle="modal" href="#addMajorModal" class="btn btn-sm btn-default">Add Major</a>
							</li>
						</ul>

					</div>
					<!-- /.portlet-header -->

					<div class="portlet-content">

						<div class="table-responsive">

							<table
								class="table table-striped table-bordered table-hover table-highlight"
								data-provide="datatable"
								data-display-rows="10"
								data-info="true"
								data-search="true"
								data-length-change="true"
								data-paginate="true"
								>
								<thead>
								<tr>
									<th class="text-center" data-filterable="true" data-sortable="true">Code</th>
									<th class="text-center" data-filterable="true" data-sortable="false">Name</th>
									<th class="text-center">Action</th>
								</tr>
								</thead>
								<tbody>

								<?php
								foreach ($majors as $major) {
									include(ROOT_PATH . "app/views/partials/major/table-data-view.html.php");
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
<!-- /content -->

<div id="addMajorModal" class="modal modal-styled fade">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<form method="post" id="create-form" action="<?php echo BASE_URL . 'academia/majors'; ?>" class="form">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title">Create Major</h3>
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
						} else if (isModificationSuccessful()) {
							?>
							<div class="alert alert-success">
								<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
								<strong>Major successfully created!</strong> <br/>
							</div>
						<?php } ?>
						<div class="portlet-content">
							<div class="row">
								<div class="col-sm-12">

									<div class="form-group">
										<h5>
											<i class="fa fa-edit"></i>
											<label for="major_code">Major Code</label>
										</h5>
										<input type="text" id="major_code" name="major_code" class="form-control"
										       value="<?php if (isset($_POST['major_code'])) echo
										       htmlentities($_POST['major_code']); ?>"
										       autofocus="on" required>
									</div>

									<div class="form-group">
										<h5>
											<i class="fa fa-edit"></i>
											<label for="major_name">Major Name</label>
										</h5>
										<input type="text" id="major_name" name="major_name" class="form-control"
										       value="<?php if (isset($_POST['major_name'])) echo
										       htmlentities($_POST['major_name']); ?>"
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

<div id="deleteMajor" class="modal modal-styled fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="delete-form" action="<?php echo BASE_URL . 'academia/majors'; ?>" class="form">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title">Remove Major
						<!--                        from --><?php //echo $curUser->getFirstName() . " " . $curUser->getLastName(); ?>
					</h3>
				</div>
				<div class="modal-body">
					<div class="portlet">
						<?php
						if (empty($errors) === false) {
							?>
							<div class="alert alert-danger">
								<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
								<strong>Oh snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>'; ?>
							</div>
						<?php } ?>

						<div class="portlet-content">

							<div class="row">
								<div class="alert alert-warning">
									<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
									<strong>Warning!</strong><br/>Are you sure you want to delete this major?
									<br/><i>A tutor or a student may be connected to this major.</i>
								</div>
							</div>

						</div>

					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-tertiary" data-dismiss="modal">Cancel</button>
					<input type="hidden" id="delMajorIdModal" name="delMajorIdModal" value=""/>
					<input type="hidden" name="hiddenSubmitDeleteMajor">
					<button type="submit" class="btn btn-primary">Delete</button>
				</div>
			</form>

		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div id="updateMajor" class="modal modal-styled fade">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<form method="post" id="create-form" action="<?php echo BASE_URL . 'academia/majors'; ?>" class="form">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title">Update Major</h3>
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
						} else if (isModificationSuccessful()) {
							?>
							<div class="alert alert-success">
								<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
								<strong>Major successfully updated!</strong> <br/>
							</div>
						<?php } ?>
						<div class="portlet-content">
							<div class="row">
								<div class="col-sm-12">

									<div class="form-group">
										<h5>
											<i class="fa fa-edit"></i>
											<label for="majorCodeUpdate">Edit Code</label>
										</h5>
										<input type="text" id="majorCodeUpdate" name="majorCodeUpdate"
										       class="form-control"
										       value="<?php if (isset($_POST['majorCodeUpdate'])) echo
										       htmlentities($_POST['majorCodeUpdate']); ?>"
										       autofocus="on" required>
									</div>

									<div class="form-group">
										<h5>
											<i class="fa fa-edit"></i>
											<label for="majorNameUpdate">Edit Name</label>
										</h5>
										<input type="text" id="majorNameUpdate" name="majorNameUpdate"
										       class="form-control"
										       value="<?php if (isset($_POST['majorNameUpdate'])) echo
										       htmlentities($_POST['majorNameUpdate']); ?>"
										       required>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-tertiary" data-dismiss="modal">Close</button>
					<input type="hidden" name="hiddenUpdatePrsd">
					<input type="hidden" id="updateMajorIdModal" name="updateMajorIdModal" value=""/>

					<button type="submit" class="btn btn-primary">Update</button>
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

<script type="text/javascript">
	jQuery(function () {
		// prepare course id for delete on modal
		$(".btnDeleteMajor").click(function () {
			$inputVal = $(this).next('input').val();
			$("#delMajorIdModal").val($inputVal);
		});

		$(".btnUpdateMajor").click(function () {
			$majorId = $(this).next().next('input').val();
			$majorName = ($(this).parent().prev().text());
			$majorCode = ($(this).parent().prev().prev().text());

			$("#updateMajorIdModal").val($majorId);
			$("#majorCodeUpdate").val($majorCode);
			$("#majorNameUpdate").val($majorName);

		});


	});

</script>

</body>
</html>