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
 * @since 9/19/2014
 */
?>

<?php
require __DIR__ . '/../../app/init.php';
$general->loggedOutProtect();


try {
	$tutors = TutorFetcher::retrieveAll($db);
	$currentTerms = TermFetcher::retrieveCurrTerm($db);
	$schedules = ScheduleFetcher::retrieveTutorsOnCurrentTerms($db);


	if (isUrlRequestingAllSchedules($user)) {
		$pageTitle = "All Schedules";
	} else if (isBtnAddSchedulePrsd()) {
		$pageTitle = "Add schedule";
		$days = isset($_POST['day']) ? $_POST['day'] : NULL;
		Schedule::add($db, $_POST['tutorId'], $_POST['termId'], $days, $_POST['startsAt'], $_POST['endsAt']);
		header('Location: ' . BASE_URL . 'staff/schedules/success');
		exit();
	} else if (isBtnDeletePrsd()) {
		Schedule::delete($db, $_POST['delScheduleIdModal']);
		header('Location: ' . BASE_URL . 'staff/schedules/success');
		exit();
	} else if (isModificationSuccessful()) {
		$pageTitle = "Schedule added - Success";
	} else {
		header('Location: ' . BASE_URL . 'error-404');
		exit();
	}


} catch (Exception $e) {
	$errors[] = $e->getMessage();
}

function getSchedule($needle, $schedules, $strict = false) {
	foreach ($schedules as $schedule) {
		if (($strict ? $schedule === $needle : $schedule == $needle) ||
			(is_array($schedule) && getSchedule($needle, $schedule, $strict))
		) {
			return $schedule;
		}
	}

	return false;
}

function isBtnAddSchedulePrsd() {
	return isset($_POST['hiddenSubmitPrsd']) && empty($_POST['hiddenSubmitPrsd']);
}

function isModificationSuccessful() {
	return isset($_GET['success']) && strcmp($_GET['success'], 'y1!qp!' === 0);
}

function isBtnDeletePrsd() {
	return isset($_POST['hiddenSubmitDeleteSchedule']) && empty($_POST['hiddenSubmitDeleteSchedule']);
}

function isBtnUpdatePrsd() {
	return isset($_POST['hiddenUpdatePrsd']) && empty($_POST['hiddenUpdatePrsd']);
}

/**
 * @return bool
 */
function isUrlRequestingSingleSchedule() {
	return isset($_GET['id']) && preg_match("/^[0-9]+$/", $_GET['id']);
}

/**
 * @param $user
 * @return bool
 */
function isUrlRequestingAllSchedules($user) {
	return empty($_GET) && !$user->isTutor() && empty($_POST);
}

function get($objects, $findId, $column) {
	foreach ($objects as $object) {
		if ($object[$column] === $findId) return $object;
	}

	return false;
}

$pageTitle = "All schedules";
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
<?php require ROOT_PATH . 'views/head.php'; ?>
<body>
<div id="wrapper">
<?php
require ROOT_PATH . 'views/header.php';
require ROOT_PATH . 'views/sidebar.php';
?>



<div id="content">

	<div id="content-header">
		<h1><?php echo $pageTitle; ?></h1>
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
		} else if (isModificationSuccessful()) {
			?>
			<div class="alert alert-success">
				<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
				<strong>Data successfully modified</strong> <br/>
			</div>
		<?php } ?>
		<div class="row">

			<div class="col-md-12">

				<div class="portlet">

					<div class="portlet-header">

						<h3>
							<i class="fa fa-table"></i>
							Working hours
							<?php
							foreach ($currentTerms as $currentTerm) {
								echo " - " . $currentTerm[TermFetcher::DB_COLUMN_NAME];
							}
							?>
						</h3>
						<ul class="portlet-tools pull-right">
							<li>
								<a data-toggle="modal" href="#addWorkingHoursModal" class="btn btn-sm btn-default">Add
									Working
									Hours</a>
							</li>
						</ul>

					</div>
					<!-- /.portlet-header -->

					<div class="portlet-content">
						<div class="table-responsive">
							<table
								class="table table-striped table-bordered table-hover table-highlight table-checkable"
								data-provide="datatable"
								data-info="true"
								data-search="true"
								data-length-change="true"
								data-paginate="false"
								id="usersTable"
								>
								<thead>
								<tr>
									<th class="text-center" data-filterable="true" data-sortable="true"
									    data-direction="asc">First Name
									</th>
									<th class="text-center" data-direction="asc" data-filterable="true"
									    data-sortable="true">Last Name
									</th>
									<th class="text-center" data-filterable="true" data-sortable="true"
									    data-sortable="true">Days
									</th>
									<th class="text-center" data-filterable="true" data-sortable="false"
									    data-sortable="true">Hours
									</th>
									<th class="text-center" data-filterable="true" data-sortable="true"
									    data-sortable="true">Current Term
									</th>

									<?php if ($user->isAdmin()): ?>
										<th class="text-center" data-filterable="false" class="hidden-xs hidden-sm">
											Action
										</th>
									<?php endif; ?>
								</tr>
								</thead>
								<tbody>

								<?php
								if (empty($errors) === true) {
									foreach (array_reverse($schedules) as $schedule) {
										include(ROOT_PATH . "views/partials/schedule/table-data-view.html.php");
									}
								}
								?>
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

<div id="addWorkingHoursModal" class="modal modal-styled fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="create-form" action="<?php echo BASE_URL . 'staff/schedules'; ?>" class="form">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title">Add Working Hours</h3>
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
								<strong>Course successfully created!</strong> <br/>
							</div>
						<?php } ?>
						<div class="portlet-content">
							<div class="row">
								<div class="col-sm-12">

									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><label for="tutorId">Tutors</label></span>
											<select id="tutorId" name="tutorId" class="form-control" required>
												<?php foreach ($tutors as $tutor) {
													include(ROOT_PATH . "views/partials/tutor/select-options-view.html.php");
												}
												?>
											</select>
										</div>
									</div>

									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><label for="termId">Term</label></span>
											<select id="termId" name="termId" class="form-control" required>
												<?php foreach ($currentTerms as $term) {
													include(ROOT_PATH . "views/partials/term/select-options-view.html.php");
												}
												?>
											</select>
										</div>
									</div>

									<div class="form-group">
										<div class="alert alert-warning">
											<a class="close" data-dismiss="alert" href="#"
											   aria-hidden="true">&times;</a>
											<strong>Warning!</strong><i> Make sure new working hours do not conflict
												with
												others existing one.</i>
										</div>
									</div>

									<div class="form-group">
										<h4>Working days</h4>

										<div class="form-group">
											<label class="checkbox-inline">
												<input type="checkbox" id="inlineCheckbox1" name="day[]" value="1">
												Monday
											</label>
											<label class="checkbox-inline">
												<input type="checkbox" id="inlineCheckbox2" name="day[]" value="2">
												Tuesday
											</label>
											<label class="checkbox-inline">
												<input type="checkbox" id="inlineCheckbox3" name="day[]" value="3">
												Wednesday
											</label>
											<label class="checkbox-inline">
												<input type="checkbox" id="inlineCheckbox3" name="day[]" value="4">
												Thursday
											</label><label class="checkbox-inline">
												<input type="checkbox" id="inlineCheckbox3" name="day[]" value="5">
												Friday
											</label>
										</div>
									</div>
									<hr/>
									<div class="form-group">
										<div class="col-md-6 pull-left">

											<h4>Starts at</h4>

											<div class="input-group bootstrap-timepicker">
												<input id="tp-ex-1" type="text" class="form-control"
												       name="startsAt">
												<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
											</div>
										</div>
										<div class="col-md-6 pull-right">

											<h4>Ends at</h4>

											<div class="input-group bootstrap-timepicker">
												<input id="tp-ex-2" type="text" class="form-control"
												       name="endsAt">
                                                        <span class="input-group-addon"><i
		                                                        class="fa fa-clock-o"></i></span>
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
					<input type="hidden" name="hiddenSubmitPrsd">
					<button type="submit" class="btn btn-primary">Create</button>
				</div>
			</form>

		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<div id="deleteSchedule" class="modal modal-styled fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="post" id="delete-form" action="<?php echo BASE_URL . 'staff/schedules'; ?>" class="form">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title">Remove Schedule
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
									<strong>Warning!</strong><br/>Are you sure you want to delete this schedule?
								</div>
							</div>

						</div>

					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-tertiary" data-dismiss="modal">Cancel</button>
					<input type="hidden" id="delScheduleIdModal" name="delScheduleIdModal" value=""/>
					<input type="hidden" name="hiddenSubmitDeleteSchedule">
					<button type="submit" class="btn btn-primary">Delete</button>
				</div>
			</form>

		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<?php include ROOT_PATH . "views/footer.php"; ?>
</div>
<!-- #wrapper -->

<?php include ROOT_PATH . "views/assets/footer_common.php"; ?>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/select2/select2.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/datatables/DT_bootstrap.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/timepicker/bootstrap-timepicker.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/autosize/jquery.autosize.min.js"></script>
<!--<script src="--><?php //echo BASE_URL; ?><!--assets/js/demos/form-extended.js"></script>-->

<script type="text/javascript">
	$(function () {
		// http://momentjs.com/docs/#/manipulating/add/
		// http://eonasdan.github.io/bootstrap-datetimepicker
		moment().format();

		$('#tp-ex-1').timepicker();
		$('#tp-ex-2').timepicker();

		$("#tutorId").select2();
		$("#termId").select2();
		$("#tutorUpdateId").select2();
		$("#termUpdateId").select2();

		var checkedAtLeastOne = false;
		$('input[type="checkbox"]').each(function () {
			if ($(this).is(":checked")) {
				checkedAtLeastOne = true;
				alert("checked");
			}
		});


		// prepare course id for delete on modal
		$(".btnDeleteSchedule").click(function () {
			var inputVal = $(this).next('input').val();
			$("#delScheduleIdModal").val(inputVal);
		});

		$(".btnUpdateSchedule").click(function () {
			var scheduleId = $(this).next().next('input').val();
			$courseName = ($(this).parent().prev().text());
			alert($courseName);
			$courseCode = ($(this).parent().prev().prev().text());

//			$("#updateCourseIdModal").val($courseId);
//			$("#nameUpdate").val($courseCode);
//			$("#dateStartUpdate").val($courseName);

		});
	});
</script>

</body>
</html>


