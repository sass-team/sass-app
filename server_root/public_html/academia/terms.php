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
 * @since 9/15/2014
 */

require __DIR__ . '/../../app/init.php';
$general->loggedOutProtect();

// redirect if user elevation is not that of secretary or admin
if ($user->isTutor()) {
	header('Location: ' . BASE_URL . "error-403");
	exit();
}


try {
	$terms = TermFetcher::retrieveAllButCur($db);
	if (isBtnUpdatePrsd()) {
		var_dump($_POST);
		$updateDone = FALSE;
		$termId = $_POST['updateTermIdModal'];
		$termName = $_POST['nameUpdate'];

		if (($course = getTerm($termId, $terms)) === FALSE) throw new Exception("Data tempering detected. Aborting.");

		$updateDone = $updateDone || Term::updateName($db, $termId, $_POST['nameUpdate'], $course[TermFetcher::DB_COLUMN_NAME]);
		$updateDone = $updateDone || Term::updateStartingDate($db, $termId, $_POST['dateStartUpdate'], $course[TermFetcher::DB_COLUMN_START_DATE]);
		$updateDone = $updateDone || Term::updateEndingDate($db, $termId, $_POST['dateEndUpdate'], $course[TermFetcher::DB_COLUMN_END_DATE]);

		if (!$updateDone) throw new Exception("No new data inputted. Process aborted.");
		header('Location: ' . BASE_URL . 'academia/terms/success');
		exit();

	} else if (isBtnCreatePrsd()) {

		Term::create($db, $_POST['termName'], $_POST['dateTimePickerStart'], $_POST['dateTimePickerEnd']);
		header('Location: ' . BASE_URL . 'academia/terms/success');
		exit();
	} else if (isBtnDeletePrsd()) {
		Term::delete($db, $_POST['delTermIdModal']);
		header('Location: ' . BASE_URL . 'academia/terms/success');
		exit();
	}

} catch (Exception $e) {
	$errors[] = $e->getMessage();
}

/**
 * http://stackoverflow.com/a/4128377/2790481
 *
 * @param $needle
 * @param $terms
 * @param bool $strict
 * @return bool
 */
function getTerm($needle, $terms, $strict = false) {
	foreach ($terms as $term) {
		if (($strict ? $term === $needle : $term == $needle) ||
			(is_array($term) && getTerm($needle, $term, $strict))
		) {
			return $term;
		}
	}

	return false;
}

function isBtnCreatePrsd() {
	return isset($_POST['hiddenSbmtPrsCreateTerm']) && empty($_POST['hiddenSbmtPrsCreateTerm']);
}

function isModificationSuccessful() {
	return isset($_GET['success']) && strcmp($_GET['success'], 'y1!qp' === 0);
}

function isBtnDeletePrsd() {
	return isset($_POST['hiddenSubmitDeleteTerm']) && empty($_POST['hiddenSubmitDeleteTerm']);
}

function isBtnUpdatePrsd() {
	return isset($_POST['hiddenUpdatePrsd']) && empty($_POST['hiddenUpdatePrsd']);
}

$pageTitle = "Academia - Terms";
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
		<h1>All Terms</h1>
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
							<i class="fa fa-table"></i>
							View and Manage Terms
						</h3>

						<ul class="portlet-tools pull-right">
							<li>
								<a data-toggle="modal" href="#addTermModal" class="btn btn-sm btn-default">Add Term</a>
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
									<th class="text-center" data-filterable="true" data-sortable="true">Name</th>
									<th class="text-center" data-filterable="false" data-sortable="false">Starting Date</th>
									<th class="text-center" data-filterable="false" data-sortable="false">Ending Date</th>
									<th class="text-center">Action</th>

								</tr>
								</thead>
								<tbody>

								<?php
								foreach ($terms as $term) {
									include(ROOT_PATH . "app/views/partials/term/table-data-view.html.php");
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

<div id="addTermModal" class="modal modal-styled fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="create-form" action="<?php echo BASE_URL . 'academia/terms'; ?>" class="form">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title">Create Term</h3>
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
								<strong>Term successfully created!</strong> <br/>
							</div>
						<?php } ?>
						<div class="portlet-content">
							<div class="row">
								<div class="col-sm-12">

									<div class="form-group col-md-12">
										<h4>Name </h4>
										<input type="text" id="termName" name="termName" class="form-control"
										       value="<?php if (isset($_POST['termName'])) echo
										       htmlentities($_POST['termName']); ?>"
										       required placeholder="e.g. Fall Semester 2014">
									</div>

									<div class="form-group col-md-12">
										<h4>Term Period</h4>

										<div class="input-group">
											<div class='input-group date' id='dateTimePickerStart'>
											<span class="input-group-addon"><label for="dateTimePickerStart">Starts
													At</label></span>
												<input type='text' class="form-control" name="dateTimePickerStart"/>
                                 <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
											</div>
										</div>
									</div>

									<div class="form-group col-md-12">
										<div class="input-group">
											<div class='input-group date' id='dateTimePickerEnd'>
											<span class="input-group-addon"><label for="dateTimePickerEnd">Ends
													At&#32; </label></span>
												<input type='text' class="form-control" name="dateTimePickerEnd"/>
                                 <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
											</div>
										</div>
									</div>


								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-tertiary" data-dismiss="modal">Close</button>
					<input type="hidden" name="hiddenSbmtPrsCreateTerm">
					<button type="submit" class="btn btn-primary">Create</button>
				</div>
			</form>

		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div id="deleteTerm" class="modal modal-styled fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="delete-form" action="<?php echo BASE_URL . 'academia/terms'; ?>" class="form">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title">Remove Term
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
									<strong>Warning!</strong><br/>Are you sure you want to delete this term?
									<br/><i>This delete process will carry on only if no data are used in this term's period.</i>
								</div>
							</div>

						</div>

					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-tertiary" data-dismiss="modal">Cancel</button>
					<input type="hidden" id="delTermIdModal" name="delTermIdModal" value=""/>
					<input type="hidden" name="hiddenSubmitDeleteTerm">
					<button type="submit" class="btn btn-primary">Delete</button>
				</div>
			</form>

		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div id="updateTerm" class="modal modal-styled fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="create-form" action="<?php echo BASE_URL . 'academia/terms'; ?>" class="form">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title">Update Term</h3>
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
								<strong>Term successfully updated!</strong> <br/>
							</div>
						<?php } ?>
						<div class="portlet-content">
							<div class="row">
								<div class="col-sm-12">


									<div class="form-group col-md-12">
										<h4>Name </h4>
										<input type="text" id="nameUpdate" name="nameUpdate" class="form-control"
										       value="<?php if (isset($_POST['nameUpdate'])) echo
										       htmlentities($_POST['nameUpdate']); ?>"
										       required>
									</div>


									<div class="form-group col-md-12">
										<h4>Term Period</h4>


										<div class="form-group">
											<div class="input-group">
												<div class='input-group date' id='dateStartUpdate'>
											<span class="input-group-addon"><label for="dateStartUpdate">Starts
													At&#32; </label></span>
													<input type='text' class="form-control" name="dateStartUpdate"
													       data-date-format="YYYY-MM-DD HH:mm:ss"/>
                                 <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="input-group">
												<div class='input-group date' id='dateEndUpdate'>
											<span class="input-group-addon"><label for="dateEndUpdate">Ends
													At&#32; </label></span>
													<input type='text' class="form-control" name="dateEndUpdate"
													       data-date-format="YYYY-MM-DD HH:mm:ss"/>
                                 <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
												</div>
											</div>
										</div>
									</div>


								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-tertiary" data-dismiss="modal">Close</button>
					<input type="hidden" name="hiddenUpdatePrsd">
					<input type="hidden" id="updateTermIdModal" name="updateTermIdModal" value=""/>

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

<script
	src="<?php echo BASE_URL; ?>assets/js/plugins/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js">
</script>

<script type="text/javascript">
	jQuery(function () {
		// http://momentjs.com/docs/#/manipulating/add/
		// http://eonasdan.github.io/bootstrap-datetimepicker
		moment().format();
		/////////////// CREATE /////////////////////////
		$('#dateTimePickerStart').datetimepicker();
		$('#dateTimePickerEnd').datetimepicker();
		////////// UPDATE /////////////////////////
		$('#dateStartUpdate').datetimepicker();
		$('#dateEndUpdate').datetimepicker();

		// prepare term id for delete on modal
		$(".btnDeleteTerm").click(function () {
			var inputVal = $(this).next('input').val();
			$("#delTermIdModal").val(inputVal);
		});

		$(".btnUpdateTerm").click(function () {
			var termId = $(this).next().next('input').val();
			var termName = ($(this).parent().prev().prev().prev().text());
			var startDate = ($(this).parent().prev().prev().text());
			var endDate = ($(this).parent().prev().text());

			$("#updateTermIdModal").val(termId);
			$("#nameUpdate").val(termName);
			$('#dateStartUpdate').data("DateTimePicker").setDate(startDate);
			$('#dateEndUpdate').data("DateTimePicker").setDate(endDate);
		});


	});

</script>

</body>
</html>