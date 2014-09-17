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
	$instructors = InstructorFetcher::retrieveAll($db);

	if (isBtnUpdatePrsd()) {
		$updateDone = 0;
		$instructorId = trim($_POST['updateInstructorIdModal']);

		$newInstructorFname = trim($_POST['instructorFnameUpdate']);
		$newInstructorLname = trim($_POST['instructorLnameUpdate']);
		$updateDone = false;

		if (($instructor = getInstructor($instructorId, $instructors)) !== false) {
			$oldInstructorFname = $instructor[InstructorFetcher::DB_FIRST_NAME];
			$oldInstructorLname = $instructor[InstructorFetcher::DB_LAST_NAME];


			$updateDone = $updateDone || Instructor::updateLname($db, $instructorId, $newInstructorLname, $oldInstructorLname);


			if (strcmp($newInstructorFname, $oldInstructorFname) !== 0) {
				$updateDone = true;
				Instructor::updateFname($db, $instructorId, $newInstructorFname);
			}
			if (strcmp($newInstructorLname, $oldInstructorLname) !== 0) {
				$updateDone = true;
				Instructor::updateLname($db, $instructorId, $newInstructorLname);
			}

			if (!$updateDone) {
				throw new Exception("No new data inputted. Process aborted.");
			}

			//
			header('Location: ' . BASE_URL . 'academia/instructors/success');

		} else {
			throw new Exception("Either you're trying to hack this app or something wrong went. In either case the
            developers we just notified about this");
		}

	} else if (isBtnSavePrsd()) {
		$newInstructorFname = trim($_POST['f_name']);
		$newInstructorLname = trim($_POST['l_name']);


		Instructor::create($db, $newInstructorFname, $newInstructorLname);
		header('Location: ' . BASE_URL . 'academia/instructors/success');
		exit();
	} else if (isBtnDeletePrsd()) {
		Instructor::delete($db, $_POST['delInstructorIdModal']);
		header('Location: ' . BASE_URL . 'academia/instructors/success');
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
function getInstructor($needle, $instructors, $strict = false) {
	foreach ($instructors as $instructor) {
		if (($strict ? $instructor === $needle : $instructor == $needle) ||
			(is_array($instructor) && getInstructor($needle, $instructor, $strict))
		) {
			return $instructor;
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
	return isset($_POST['hiddenSubmitDeleteInstructor']) && empty($_POST['hiddenSubmitDeleteInstructor']);
}

function isBtnUpdatePrsd() {
	return isset($_POST['hiddenUpdatePrsd']) && empty($_POST['hiddenUpdatePrsd']);
}

$page_title = "Manage Instructors";
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
		<h1>All Instructors</h1>
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
							<i class="fa fa-users"></i>
							View and Manage Instructors
						</h3>

						<ul class="portlet-tools pull-right">
							<li>
								<a data-toggle="modal" href="#addInstructorModal" class="btn btn-sm btn-default">Add Instructor</a>
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
									<th class="text-center" data-filterable="true" data-sortable="true">First Name</th>
									<th class="text-center" data-filterable="true" data-sortable="false">Last Name</th>
									<th class="text-center">Action</th>
								</tr>
								</thead>
								<tbody>

								<?php
								foreach (array_reverse($instructors) as $instructor) {
									include(ROOT_PATH . "app/views/partials/instructor/table-data-view.html.php");
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

<div id="addInstructorModal" class="modal modal-styled fade">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<form method="post" id="create-form" action="<?php echo BASE_URL . 'academia/instructors'; ?>" class="form">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title">Create Instructor</h3>
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
								<strong>Instructor successfully created!</strong> <br/>
							</div>
						<?php } ?>
						<div class="portlet-content">
							<div class="row">
								<div class="col-sm-12">

									<div class="form-group">
										<h5>
											<i class="fa fa-edit"></i>
											<label for="f_name">First Name</label>
										</h5>
										<input type="text" id="f_name" name="f_name" class="form-control"
										       value="<?php if (isset($_POST['f_name'])) echo
										       htmlentities($_POST['f_name']); ?>"
										       autofocus="on" required>
									</div>

									<div class="form-group">
										<h5>
											<i class="fa fa-edit"></i>
											<label for="l_name">Last Name</label>
										</h5>
										<input type="text" id="l_name" name="l_name" class="form-control"
										       value="<?php if (isset($_POST['l_name'])) echo
										       htmlentities($_POST['l_name']); ?>"
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

<div id="deleteInstructor" class="modal modal-styled fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="delete-form" action="<?php echo BASE_URL . 'academia/instructors'; ?>" class="form">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title">Remove Instructor
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
									<strong>Warning!</strong><br/>Are you sure you want to delete this instructor?
								</div>
							</div>

						</div>

					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-tertiary" data-dismiss="modal">Cancel</button>
					<input type="hidden" id="delInstructorIdModal" name="delInstructorIdModal" value=""/>
					<input type="hidden" name="hiddenSubmitDeleteInstructor">
					<button type="submit" class="btn btn-primary">Delete</button>
				</div>
			</form>

		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div id="updateInstructor" class="modal modal-styled fade">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<form method="post" id="create-form" action="<?php echo BASE_URL . 'academia/instructors'; ?>" class="form">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title">Update Instructor</h3>
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
								<strong>Instructor successfully updated!</strong> <br/>
							</div>
						<?php } ?>
						<div class="portlet-content">
							<div class="row">
								<div class="col-sm-12">

									<div class="form-group">
										<h5>
											<i class="fa fa-edit"></i>
											<label for="instructorFnameUpdate">Edit First Name</label>
										</h5>
										<input type="text" id="instructorFnameUpdate" name="instructorFnameUpdate"
										       class="form-control"
										       value="<?php if (isset($_POST['instructorFnameUpdate'])) echo
										       htmlentities($_POST['instructorFnameUpdate']); ?>"
										       autofocus="on" required>
									</div>

									<div class="form-group">
										<h5>
											<i class="fa fa-edit"></i>
											<label for="instructorLnameUpdate">Edit Last Name</label>
										</h5>
										<input type="text" id="instructorLnameUpdate" name="instructorLnameUpdate"
										       class="form-control"
										       value="<?php if (isset($_POST['instructorLnameUpdate'])) echo
										       htmlentities($_POST['instructorLnameUpdate']); ?>"
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
					<input type="hidden" id="updateInstructorIdModal" name="updateInstructorIdModal" value=""/>

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
		$(".btnDeleteInstructor").click(function () {
			$inputVal = $(this).next('input').val();
			$("#delInstructorIdModal").val($inputVal);
		});

		$(".btnUpdateInstructor").click(function () {
			$instructorId = $(this).next().next('input').val();
			$instructorLname = ($(this).parent().prev().text());
			$instructorFname = ($(this).parent().prev().prev().text());

			$("#updateInstructorIdModal").val($instructorId);
			$("#instructorFnameUpdate").val($instructorFname);
			$("#instructorLnameUpdate").val($instructorLname);

		});


	});

</script>

</body>
</html>