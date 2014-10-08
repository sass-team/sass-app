<?php
require __DIR__ . '/../../app/init.php';
$general->loggedOutProtect();

$section = "appointments";

/**
 * @param $studentsAppointmentData
 * @return bool
 */
function reportsHaveBeenCrtd($studentsAppointmentData) {
	return $studentsAppointmentData[0][AppointmentHasStudentFetcher::DB_COLUMN_REPORT_ID] !== NULL;
}

try {
	if (!isUrlValid() || ($user->isTutor() && !Tutor::hasAppointmentWithId($db, $user->getId(), $_GET['appointmentId']))) {
		header('Location: ' . BASE_URL . "error-403");
		exit();
	}

	$pageTitle = "Single Appointment";
	$appointmentId = $_GET['appointmentId'];
	$studentsAppointmentData = Appointment::getAllStudentsWithAppointment($db, $appointmentId);
	$terms = TermFetcher::retrieveAll($db);
	$students = StudentFetcher::retrieveAll($db);
	$courses = CourseFetcher::retrieveAll($db);
	$instructors = InstructorFetcher::retrieveAll($db);
	$tutors = TutorFetcher::retrieveAll($db);
	$startDateTime = new DateTime($studentsAppointmentData[0][AppointmentFetcher::DB_COLUMN_START_TIME]);
	$endDateTime = new DateTime($studentsAppointmentData[0][AppointmentFetcher::DB_COLUMN_END_TIME]);
	$nowDateTime = new DateTime();


	// load reports if they have been created
	if (reportsHaveBeenCrtd($studentsAppointmentData)) $reports = Report::getAllWithAppointmentId($db, $appointmentId);


	if (isBtnUpdateReportPrsd() || isBtnCompleteReportPrsd()) {
		$formReportId = isset($_POST['form-update-report-id']) ? $_POST['form-update-report-id'] : '';
		$reportUpdate = getReport($formReportId, $reports);
		$projectTopicOtherNew = isset($_POST['project-topic-other']) ? $_POST['project-topic-other'] : '';
		$otherTextArea = isset($_POST['other_text_area']) ? $_POST['other_text_area'] : '';
		$studentsConcernsTextArea = isset($_POST['students-concerns-textarea']) ? $_POST['students-concerns-textarea'] : '';
		$studentBroughtAlong = isset($_POST['student-brought-along']) ? $_POST['student-brought-along'] : NULL;
		$conclusionAdditionalComments = isset($_POST['conclusion-additional-comments']) ? $_POST['conclusion-additional-comments'] : '';
		$studentBroughtAlongNew = isset($_POST['student-brought-along']) ? $_POST['student-brought-along'] : NULL;
		$relevantFeedbackGuidelines = isset($_POST['relevant-feedback-guidelines']) ? $_POST['relevant-feedback-guidelines'] : NULL;


		$studentBroughtAlongOld = array(
			StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_GRADED => $reportUpdate[StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_GRADED],
			StudentBroughtAlongFetcher::DB_COLUMN_DRAFT => $reportUpdate[StudentBroughtAlongFetcher::DB_COLUMN_DRAFT],
			StudentBroughtAlongFetcher::DB_COLUMN_INSTRUCTORS_FEEDBACK => $reportUpdate[StudentBroughtAlongFetcher::DB_COLUMN_INSTRUCTORS_FEEDBACK],
			StudentBroughtAlongFetcher::DB_COLUMN_TEXTBOOK => $reportUpdate[StudentBroughtAlongFetcher::DB_COLUMN_TEXTBOOK],
			StudentBroughtAlongFetcher::DB_COLUMN_NOTES => $reportUpdate[StudentBroughtAlongFetcher::DB_COLUMN_NOTES],
			StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_SHEET => $reportUpdate[StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_SHEET],
			StudentBroughtAlongFetcher::DB_COLUMN_EXERCISE_ON => $reportUpdate[StudentBroughtAlongFetcher::DB_COLUMN_EXERCISE_ON],
			StudentBroughtAlongFetcher::DB_COLUMN_OTHER => $reportUpdate[StudentBroughtAlongFetcher::DB_COLUMN_OTHER]
		);


		if (isBtnUpdateReportPrsd()) {
			$updateDone = Report::updateProjectTopicOtherText($db, $reportUpdate[ReportFetcher::DB_COLUMN_ID],
				$reportUpdate[ReportFetcher::DB_COLUMN_PROJECT_TOPIC_OTHER], $projectTopicOtherNew);
			$updateDone = (Report::updateOtherText($db, $reportUpdate[ReportFetcher::DB_COLUMN_ID],
					$reportUpdate[ReportFetcher::DB_COLUMN_OTHER_TEXT_AREA], $otherTextArea)) || $updateDone;
			$updateDone = (Report::updateStudentsConcerns($db, $reportUpdate[ReportFetcher::DB_COLUMN_ID],
					$reportUpdate[ReportFetcher::DB_COLUMN_STUDENT_CONCERNS], $studentsConcernsTextArea)) || $updateDone;
			$updateDone = (Report::updateRelevantFeedbackGuidelines($db, $reportUpdate[ReportFetcher::DB_COLUMN_ID],
					$reportUpdate[ReportFetcher::DB_COLUMN_RELEVANT_FEEDBACK_OR_GUIDELINES], $relevantFeedbackGuidelines)) || $updateDone;
			$updateDone = (Report::updateStudentBroughtAlong($db, $reportUpdate[ReportFetcher::DB_COLUMN_ID],
					$studentBroughtAlongNew, $studentBroughtAlongOld)) || $updateDone;
			$updateDone = (Report::updateAdditionalComments($db, $reportUpdate[ReportFetcher::DB_COLUMN_ID],
					$reportUpdate[ReportFetcher::DB_COLUMN_ADDITIONAL_COMMENTS], $conclusionAdditionalComments)) || $updateDone;
		} else {
			$updateDone = Report::updateAllFields($db, $reportUpdate[ReportFetcher::DB_COLUMN_ID], $projectTopicOtherNew,
				$otherTextArea, $studentsConcernsTextArea, $relevantFeedbackGuidelines, $studentBroughtAlongNew, $studentBroughtAlongOld, $conclusionAdditionalComments);
			// user is tutor requesting fill report
			if ($user->isTutor()) {
				ReportFetcher::updateLabel($db, $formReportId, Report::LABEL_MESSAGE_PENDING_VALIDATION, Report::LABEL_COLOR_WARNING);
			} else {
				// user is secretary confirming report
				ReportFetcher::updateLabel($db, $formReportId, Report::LABEL_MESSAGE_COMPLETE, Report::LABEL_COLOR_SUCCESS);

			}
		}

		if (!$updateDone) throw new Exception("No new data inserted.");
		header('Location: ' . BASE_URL . 'appointments/' . $appointmentId . '/success');
		exit();
	}

	if (isUrlRqstngManualReportEnable()) {
		if (($studentsAppointmentData[0][AppointmentHasStudentFetcher::DB_COLUMN_REPORT_ID] === NULL) &&
			($nowDateTime > $startDateTime)
		) {
			$students = AppointmentHasStudentFetcher::retrieveStudentsWithAppointment($db, $appointmentId);
			$appointment = Appointment::getSingle($db, $appointmentId);
			foreach ($students as $student) {
				$reportId = ReportFetcher::insert($db, $student[AppointmentHasStudentFetcher::DB_COLUMN_STUDENT_ID],
					$student[AppointmentHasStudentFetcher::DB_COLUMN_ID],
					$student[AppointmentHasStudentFetcher::DB_COLUMN_INSTRUCTOR_ID]);
			}
			AppointmentFetcher::updateLabel($db, $appointmentId,
				Appointment::LABEL_MESSAGE_COMPLETE, Appointment::LABEL_COLOR_SUCCESS);


			if (!$user->isTutor()) Mailer::sendTutorNewReportsCronOnly($db, $appointment);
		}

		header('Location: ' . BASE_URL . 'appointments/' . $appointmentId . '/success');
		exit();
	} else if (isUrlRqstngAppointmentCancelByStudent()) {
		AppointmentFetcher::updateLabel($db, $appointmentId,
			Appointment::LABEL_MESSAGE_STUDENT_CANCELED, Appointment::LABEL_COLOR_CANCELED);
		header('Location: ' . BASE_URL . 'appointments/' . $appointmentId . '/success');
		exit();
	} else if (isBtnUpdateAppointmentPrsd()) {
//		var_dump($studentsAppointmentData);

		$updateDone = Appointment::updateStudents($db, $appointmentId, $studentsAppointmentData, $_POST['studentsIds']);
		header('Location: ' . BASE_URL . 'appointments/' . $appointmentId . '/success');
		exit();
	} else if (isUrlRqstngAppointmentCancelByTutor()) {
		AppointmentFetcher::updateLabel($db, $appointmentId,
			Appointment::LABEL_MESSAGE_TUTOR_CANCELED, Appointment::LABEL_COLOR_CANCELED);
		header('Location: ' . BASE_URL . 'appointments/' . $appointmentId . '/success');
		exit();
	} else if (isUrlRqstngAppointmentNoShowByStudent()) {
		AppointmentFetcher::updateLabel($db, $appointmentId,
			Appointment::LABEL_MESSAGE_STUDENT_NO_SHOW, Appointment::LABEL_COLOR_CANCELED);
		header('Location: ' . BASE_URL . 'appointments/' . $appointmentId . '/success');
		exit();
	} else if (isUrlRqstngAppointmentNoShowByTutor()) {
		AppointmentFetcher::updateLabel($db, $appointmentId,
			Appointment::LABEL_MESSAGE_TUTOR_NO_SHOW, Appointment::LABEL_COLOR_CANCELED);
		header('Location: ' . BASE_URL . 'appointments/' . $appointmentId . '/success');
		exit();
	} else if (isUrlRqstngAppointmentEnable()) {
		AppointmentFetcher::updateLabel($db, $appointmentId,
			Appointment::LABEL_MESSAGE_PENDING, Appointment::LABEL_COLOR_PENDING);
		header('Location: ' . BASE_URL . 'appointments/' . $appointmentId . '/success');
		exit();
	}

} catch (Exception $e) {
	$errors[] = $e->getMessage();
}


function isUrlValid() {
	return isset($_GET['appointmentId']) && preg_match("/^[0-9]+$/", $_GET['appointmentId']);
}

function isUrlRequestingSingleAppointment() {
	return isset($_GET['appointmentId']) && preg_match("/^[0-9]+$/", $_GET['appointmentId']) && empty($_POST['hiddenCreateReports']);
}

function isUrlRqstngManualReportEnable() {
	return isset($_GET['appointmentId']) && preg_match("/^[0-9]+$/", $_GET['appointmentId']) &&
	isset($_POST['hiddenCreateReports']) && empty($_POST['hiddenCreateReports']);
}


function isUrlRqstngAppointmentCancelByStudent() {
	return isset($_GET['appointmentId']) && preg_match("/^[0-9]+$/", $_GET['appointmentId']) &&
	isset($_POST['hiddenCanceledByStudent']) && empty($_POST['hiddenCanceledByStudent']);
}


function isUrlRqstngAppointmentNoShowByStudent() {
	return isset($_GET['appointmentId']) && preg_match("/^[0-9]+$/", $_GET['appointmentId']) &&
	isset($_POST['hiddenNoShowByStudent']) && empty($_POST['hiddenNoShowByStudent']);
}

function isUrlRqstngAppointmentNoShowByTutor() {
	return isset($_GET['appointmentId']) && preg_match("/^[0-9]+$/", $_GET['appointmentId']) &&
	isset($_POST['hiddenNoShowByTutor']) && empty($_POST['hiddenNoShowByTutor']);
}


function isUrlRqstngAppointmentCancelByTutor() {
	return isset($_GET['appointmentId']) && preg_match("/^[0-9]+$/", $_GET['appointmentId']) &&
	isset($_POST['hiddenCanceledByTutor']) && empty($_POST['hiddenCanceledByTutor']);
}

function isUrlRqstngAppointmentEnable() {
	return isset($_GET['appointmentId']) && preg_match("/^[0-9]+$/", $_GET['appointmentId']) &&
	isset($_POST['hiddenEnableAppointment']) && empty($_POST['hiddenEnableAppointment']);
}


function isBtnUpdateReportPrsd() {
	return isset($_GET['appointmentId']) && preg_match("/^[0-9]+$/", $_GET['appointmentId']) &&
	isset($_POST['form-update-report-id']) && preg_match("/^[0-9]+$/", $_POST['form-update-report-id']) &&
	isset($_POST['btn-update-report']);
}


function isBtnUpdateAppointmentPrsd() {
	return isset($_GET['appointmentId']) && preg_match("/^[0-9]+$/", $_GET['appointmentId']) &&
	isset($_POST['hiddenUpdateAppointmentPrsd']) && empty($_POST['hiddenUpdateAppointmentPrsd']);
}

function isBtnCompleteReportPrsd() {
	return isset($_GET['appointmentId']) && preg_match("/^[0-9]+$/", $_GET['appointmentId']) &&
	isset($_POST['form-update-report-id']) && preg_match("/^[0-9]+$/", $_POST['form-update-report-id']) &&
	isset($_POST['btn-complete-report']);
}

function isModificationSuccess() {
	return isset($_GET['success']) && strcmp($_GET['success'], 'y1!q' === 0);
}


/**
 * http://stackoverflow.com/a/4128377/2790481
 * @param $reportId
 * @param $reports
 * @return mixed
 * @throws Exception
 */
function getReport($reportId, $reports) {
	foreach ($reports as $report) {
		if (strcmp($report[ReportFetcher::DB_COLUMN_ID], $reportId) === 0) return $report;
	}
	throw new Exception("Data have been malfromed");
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
		<i class="fa fa-calendar"></i>
		<?php echo $pageTitle; ?>
	</h1>


</div>
<!-- #content-header -->

<div id="content-container">
<div class="row">

<div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
	<div class="list-group">

		<a href="#appointment-tab" class="list-group-item active" data-toggle="tab">
			<h5><i class="fa fa-file-text-o"></i>
				&nbsp;&nbsp;A-<?php echo $studentsAppointmentData[0][AppointmentFetcher::DB_COLUMN_ID]; ?>
				<span
					class="label label-<?php echo $studentsAppointmentData[0][AppointmentFetcher::DB_COLUMN_LABEL_COLOR]; ?>">
							<?php echo $studentsAppointmentData[0][AppointmentFetcher::DB_COLUMN_LABEL_MESSAGE]; ?>
						</span>
			</h5>
		</a>

		<?php if ($studentsAppointmentData[0][AppointmentHasStudentFetcher::DB_COLUMN_REPORT_ID] === NULL): ?>
			<?php for ($i = 0; $i < sizeof($studentsAppointmentData); $i++) : ?>
				<a href="#report-tab" class="list-group-item" data-toggle="tab">
					<h5>
						<i class="fa fa-file-text-o"></i> &nbsp;&nbsp;R
						<span class="label label-default">disabled</span>
					</h5>
					<?php echo $studentsAppointmentData[$i][StudentFetcher::DB_TABLE . "_" .
						StudentFetcher::DB_COLUMN_FIRST_NAME] . " " .
						$studentsAppointmentData[$i][StudentFetcher::DB_TABLE . "_" .
						StudentFetcher::DB_COLUMN_LAST_NAME]; ?>
				</a>
			<?php endfor; ?>
		<?php else: ?>
			<?php for ($i = 0; $i < sizeof($reports); $i++) { ?>
				<a href="#report-tab<?php echo $i; ?>" class="list-group-item" data-toggle="tab">
					<h5>
						<i class="fa fa-file-text-o"></i> &nbsp;&nbsp;R
						<?php echo "-" . $reports[$i][ReportFetcher::DB_COLUMN_ID]; ?>
						<span
							class="label label-<?php echo $reports[$i][ReportFetcher::DB_COLUMN_LABEL_COLOR]; ?>">
							<?php echo $reports[$i][ReportFetcher::DB_COLUMN_LABEL_MESSAGE]; ?>
						</span>
					</h5>
					<?php echo $reports[$i][StudentFetcher::DB_COLUMN_FIRST_NAME] . " " .
						$reports[$i][StudentFetcher::DB_COLUMN_LAST_NAME]; ?>
				</a>
			<?php } ?>
		<?php endif; ?>


	</div>
	<!-- /.list-group -->
</div>

<div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
<div class="tab-content stacked-content">

<div class="tab-pane fade in active" id="appointment-tab">

<div class="portlet">
<div class="portlet-header">

	<h3>
		<i class="fa fa-file-text"></i>
		Appointment Details
	</h3>


	<ul class="portlet-tools pull-right">
		<li>
			<div class="btn-group">
				<button data-toggle="dropdown" class="btn btn-md btn-primary dropdown-toggle">Modify <span
						class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
					<?php
					if ((strcmp($studentsAppointmentData[0][AppointmentFetcher::DB_COLUMN_LABEL_MESSAGE], Appointment::LABEL_MESSAGE_PENDING) === 0)
					): ?>
						<?php if ($nowDateTime > $startDateTime): ?>
							<li>
								<form method="post"
								      action="<?php echo BASE_URL . 'appointments/' . $appointmentId; ?>"
									>
									<input type="hidden" name="hiddenCreateReports" value="">
									<button type="submit" class="btn btn-block btn-default">
										Complete - Enable Reports
									</button>
								</form>
							</li>
							<li class="divider"></li>
						<?php endif; ?>
						<li>
							<form method="post"
							      action="<?php echo BASE_URL . 'appointments/' . $appointmentId; ?>"
								>
								<input type="hidden" name="hiddenCanceledByStudent" value="">
								<button type="submit" class="btn btn-block btn-default">
									Canceled by student
								</button>
							</form>
						</li>
						<li class="divider"></li>
						<li>
							<form method="post"
							      action="<?php echo BASE_URL . 'appointments/' . $appointmentId; ?>"
								>
								<input type="hidden" name="hiddenCanceledByTutor" value="">
								<button type="submit" class="btn btn-block btn-default">
									Canceled by tutor
								</button>
							</form>
						</li>
						<li class="divider"></li>
						<li>
							<form method="post"
							      action="<?php echo BASE_URL . 'appointments/' . $appointmentId; ?>"
								>
								<input type="hidden" name="hiddenNoShowByStudent" value="">
								<button type="submit" class="btn btn-block btn-default">
									No show by student
								</button>
							</form>
						</li>
						<li class="divider"></li>
						<li>
							<form method="post"
							      action="<?php echo BASE_URL . 'appointments/' . $appointmentId; ?>"
								>
								<input type="hidden" name="hiddenNoShowByTutor" value="">
								<button type="submit" class="btn btn-block btn-default">
									No show by tutor
								</button>
							</form>
						</li>
					<?php elseif (strcmp($studentsAppointmentData[0][AppointmentFetcher::DB_COLUMN_LABEL_MESSAGE], Appointment::LABEL_MESSAGE_COMPLETE) !== 0): ?>
						<li>
							<form method="post"
							      action="<?php echo BASE_URL . 'appointments/' . $appointmentId; ?>"
								>
								<input type="hidden" name="hiddenEnableAppointment" value="">
								<button type="submit" class="btn btn-block btn-default">
									Enable appointment
								</button>
							</form>
						</li>
					<?php endif; ?>

				</ul>
			</div>
		</li>
	</ul>
</div>
<!-- /.portlet-header -->

<div class="portlet-content">

	<!-- Appointment details. -->
	<div class="form-group">
		<form method="post" id="add-student-form"
		      action="<?php echo BASE_URL . 'appointments/' . $appointmentId; ?>"
		      class="form">

			<div class="form-group" id="student-instructor">
				<?php
				for ($i = 0; $i < sizeof($studentsAppointmentData); $i++) {
					?>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon">Student</span>
							<select name="studentsIds[<?php echo $i; ?>]" id="studentId<?php echo $i; ?>"
							        class="form-control">
								<option></option>
								<?php
								foreach ($students as $student):
									include(ROOT_PATH . "views/partials/student/select-options-view.html.php");
								endforeach;
								?>
							</select>
						</div>

					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon">Instructor</span>
							<select name="instructorIds[]" id="instructorId<?php echo $i; ?>"
							        class="form-control" required disabled>
								<option></option>
								<?php foreach ($instructors as $instructor) {
									include(ROOT_PATH . "views/partials/instructor/select-options-view.html.php");
								}
								?>
							</select>
						</div>
					</div>
				<?php } ?>
			</div>

			<hr/>

			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><label for="courseId">Course</label></span>
					<select id="courseId" name="courseId" class="form-control" required disabled>
						<?php foreach ($courses as $course) {
							include(ROOT_PATH . "views/partials/course/select-options-view.html.php");
						}
						?>
					</select>
				</div>
			</div>


			<div class="form-group">
				<div class="input-group">
										<span class="input-group-addon"><label id="label-instructor-text"
										                                       for="tutorId">Tutors</label></span>
					<select id="tutorId" name="tutorId" class="form-control" required disabled>
						<?php foreach ($tutors as $tutor) {
							include(ROOT_PATH . "views/partials/tutor/select-options-view.html.php");
						}
						?>               </select>
					<input id="value" type="hidden" style="width:300px"/>
				</div>
			</div>


			<div class="form-group">
				<div class='input-group date' id='dateTimePickerStart'>
											<span class="input-group-addon"><label for="dateTimePickerStart">
													Starts At</label></span>
					<input type='text' name='dateTimePickerStart' class="form-control" required disabled/>
                                 <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
				</div>
			</div>


			<div class="form-group">
				<div class='input-group date' id='dateTimePickerEnd'>
                                        <span class="input-group-addon"><label for="dateTimePickerEnd">Ends
		                                        At</label></span>
					<input type='text' name='dateTimePickerEnd' class="form-control" required disabled/>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar">
										</span>
				</div>
			</div>


			<div class="form-group">
				<div class="input-group">
					<button type="button" class="btn btn-default btn-sm addButton"
					        data-template="textbox" disabled>
						Add One More Student
					</button>
				</div>
			</div>

			<div class="form-group hide" id="textboxTemplate">
				<div class="input-group">
					<button type="button" class="btn btn-default btn-sm removeButton">Remove
					</button>
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><label for="termId">Term</label></span>
					<select id="termId" name="termId" class="form-control" required disabled>
						<?php
						foreach ($terms as $term) {
							include(ROOT_PATH . "views/partials/term/select-options-view.html.php");
						}
						?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<?php
				if (empty($errors) === false) {
					?>
					<div class="alert alert-danger">
						<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
						<strong>Oh
							snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>';
						?>
					</div>
				<?php
				} else if (isModificationSuccess()) {
					?>
					<div class="alert alert-success">
						<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
						<strong>Data successfully modified!</strong> <br/>
					</div>
				<?php } ?>

				<div class="form-group">

					<button type="submit" class="btn btn-block btn-primary">Update</button>
					<input type="hidden" name="hiddenUpdateAppointmentPrsd" value="">

				</div>
			</div>
		</form>


	</div>
	<!-- /.form-group -->

</div>
<!-- /.form-group -->

</div>

</div>
<?php
if (isset($reports)) {

	for ($i = 0;
	     $i < sizeof($reports);
	     $i++) {
		?>

		<div class="tab-pane fade" id="report-tab<?php echo $i; ?>">

		<form action="<?php echo BASE_URL . "appointments/" . $appointmentId; ?>"
		      class="form-horizontal reports-update-form"
		      method="post" data-parsley-validate>
		<h3>Assignment Details</h3>

		<div class="form-group">
			<div class="form-input">

				<div class="col-md-8">
					<label for="project-topic-other">Project / Topic / Other</label>
					<textarea name="project-topic-other" id="project-topic-other" class="form-control count-textarea"
					          data-parsley-trigger="keyup" data-parsley-minlength="10" data-parsley-maxlength="512"
					          data-parsley-validation-threshold="5"
					          data-parsley-minlength-message="Come on! You need to enter at least a 10 characters long
					          comment..."
					          data-parsley-required="true"><?php echo htmlspecialchars($reports[$i][ReportFetcher::DB_COLUMN_PROJECT_TOPIC_OTHER]); ?></textarea>
				</div>
				<!-- /.col -->
				<div class="col-md-4">
					<label for="other">Other</label>
					<textarea name="other_text_area" id="other_text_area"
					          class="form-control count-textarea"
					          data-parsley-trigger="keyup" data-parsley-minlength="10" data-parsley-maxlength="512"
					          data-parsley-validation-threshold="5"
					          data-parsley-minlength-message="Come on! You need to enter at least a 10 characters long
					          comment..."><?php echo htmlspecialchars($reports[$i][ReportFetcher::DB_COLUMN_OTHER_TEXT_AREA]); ?></textarea>
				</div>
				<!-- /.col -->
			</div>
		</div>
		<!-- /.form-group -->


		<hr/>

		<h3>Focus of Conference</h3>


		<div class="row">
			<div class="col-md-6">
				<label for="students-concerns-textarea">What were the <strong>student&#39;s concerns&#63;</strong>
					(Please
					indicate briefly)</label>
				<textarea name="students-concerns-textarea" id="students-concerns-textarea"
				          class="form-control count-textarea"
				          placeholder="Maximization/Preparation for Final"
				          data-parsley-trigger="keyup" data-parsley-minlength="10" data-parsley-maxlength="512"
				          data-parsley-validation-threshold="5"
				          data-parsley-minlength-message="Come on! You need to enter at least a 10 characters long
					          comment.."
				          data-parsley-required="true"><?php echo htmlspecialchars($reports[$i][ReportFetcher::DB_COLUMN_STUDENT_CONCERNS]); ?></textarea>
			</div>

			<div class="col-md-6">
				<label for="relevant-feedback-guidelines">Briefly mention any <strong>relevant feedback or
						guidelines</strong>
					the instructor has
					provided &#40;if applicable&#41;</label>
				<textarea name="relevant-feedback-guidelines" id="relevant-feedback-guidelines"
				          class="form-control count-textarea"
				          data-parsley-trigger="keyup" data-parsley-minlength="10" data-parsley-maxlength="512"
				          data-parsley-validation-threshold="5"
				          data-parsley-minlength-message="Come on! You need to enter at least a 10 characters long
					          comment.."
				          data-parsley-required="false"><?php echo htmlspecialchars($reports[$i][ReportFetcher::DB_COLUMN_RELEVANT_FEEDBACK_OR_GUIDELINES]); ?></textarea>
			</div>
		</div>


		<div class="row">

			<div class="col-md-6">
				<hr/>
				<label for="student-brought-along[]">What did the <strong>student bring along?</strong></label>

				<div class="checkbox">

					<label>
						<input type="checkbox"
						       name="student-brought-along[<?php echo StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_GRADED; ?>]" <?php echo
						strcmp($reports[$i][StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_GRADED], StudentBroughtAlongFetcher::IS_SELECTED) === 0 ? 'checked' : ''; ?>
						       class=""
						       data-parsley-mincheck="1" data-parsley-required="true">
						Assignment &#40;graded&#41;
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox"
						       name="student-brought-along[<?php echo StudentBroughtAlongFetcher::DB_COLUMN_DRAFT; ?>]" <?php echo
						strcmp($reports[$i][StudentBroughtAlongFetcher::DB_COLUMN_DRAFT], StudentBroughtAlongFetcher::IS_SELECTED) === 0 ? 'checked' : ''; ?>
						       data-parsley-multiple="student-brought-along-parsley-<?php echo $i; ?>">
						Draft
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox"
						       name="student-brought-along[<?php echo StudentBroughtAlongFetcher::DB_COLUMN_INSTRUCTORS_FEEDBACK; ?>]" <?php echo
						strcmp($reports[$i][StudentBroughtAlongFetcher::DB_COLUMN_INSTRUCTORS_FEEDBACK], StudentBroughtAlongFetcher::IS_SELECTED) === 0 ? 'checked' : ''; ?>
							>
						Instructor&#39;s feedback
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox"
						       name="student-brought-along[<?php echo StudentBroughtAlongFetcher::DB_COLUMN_TEXTBOOK; ?>]" <?php echo
						strcmp($reports[$i][StudentBroughtAlongFetcher::DB_COLUMN_TEXTBOOK], StudentBroughtAlongFetcher::IS_SELECTED) === 0 ? 'checked' : ''; ?>>
						Textbook
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox"
						       name="student-brought-along[<?php echo StudentBroughtAlongFetcher::DB_COLUMN_NOTES; ?>]" <?php echo
						strcmp($reports[$i][StudentBroughtAlongFetcher::DB_COLUMN_NOTES], StudentBroughtAlongFetcher::IS_SELECTED) === 0 ? 'checked' : ''; ?>>
						Notes
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox"
						       name="student-brought-along[<?php echo StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_SHEET; ?>]" <?php echo
						strcmp($reports[$i][StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_SHEET], StudentBroughtAlongFetcher::IS_SELECTED) === 0 ? 'checked' : ''; ?>>
						Assignment sheet
					</label>
				</div>
				<div class="checkbox">
					<label class="focus-conference-3-exercise-class">
						<input type="checkbox"
						       name="student-brought-along[<?php echo StudentBroughtAlongFetcher::DB_COLUMN_EXERCISE_ON; ?>]" <?php echo
						$reports[$i][StudentBroughtAlongFetcher::DB_COLUMN_EXERCISE_ON] !== NULL ? 'checked' : ''; ?>>
						Exercise on <input type="text"
						                   name="student-brought-along[<?php echo StudentBroughtAlongFetcher::DB_COLUMN_EXERCISE_ON; ?>text]"
						                   class="form-control" value="<?php echo
						$reports[$i][StudentBroughtAlongFetcher::DB_COLUMN_EXERCISE_ON] === NULL ? "" : $reports[$i][StudentBroughtAlongFetcher::DB_COLUMN_EXERCISE_ON]; ?>"
							<?php echo
							$reports[$i][StudentBroughtAlongFetcher::DB_COLUMN_EXERCISE_ON] === NULL ? "disabled='disabled'" : ''; ?>
							/>
					</label>
				</div>
				<div class="checkbox">
					<label class="focus-conference-3-other-class">
						<input type="checkbox"
						       name="student-brought-along[<?php echo StudentBroughtAlongFetcher::DB_COLUMN_OTHER; ?>]" <?php echo
						$reports[$i][StudentBroughtAlongFetcher::DB_COLUMN_OTHER] !== NULL ? 'checked' : ''; ?>
						       value="other">
						Other <input type="text"
						             name="student-brought-along[<?php echo StudentBroughtAlongFetcher::DB_COLUMN_OTHER; ?>text]"
						             class="form-control" value="<?php echo
						$reports[$i][StudentBroughtAlongFetcher::DB_COLUMN_OTHER] === NULL ? "" : $reports[$i][StudentBroughtAlongFetcher::DB_COLUMN_OTHER]; ?>"<?php echo
						$reports[$i][StudentBroughtAlongFetcher::DB_COLUMN_OTHER] === NULL ? "disabled='disabled'" : ''; ?>
							/>
					</label>
				</div>
			</div>

			<div class="col-md-6">
				<hr/>
				<label for="checkbox-2">What was the <strong>primary focus of the
						conference&#63;</strong></label>

				<div class="checkbox">
					<label>
						<input type="checkbox" name="checkbox-2" class="" data-mincheck="1">
						Discussion of concepts
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="checkbox-2" class="" data-mincheck="1">
						Organization of thoughts/ideas
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="checkbox-2" class="" data-mincheck="1">
						Expression &#40;grammar, syntax, diction, etc.&#41;
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="checkbox-2" class="" data-mincheck="1">
						Exercises
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="checkbox-2" class="" data-mincheck="1">
						Academic skills
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="checkbox-2" class="" data-mincheck="1">
						Citations &#38; Referencing
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="checkbox-2" class="" data-mincheck="1">
						Other
					</label>
				</div>
			</div>
		</div>

		<!-- /.form-group -->


		<hr/>
		<h3>Conclusion/Wrap-up</h3>

		<div class="form-group">
			<div class="col-md-12">
				<label for="conclusion-wrap-up">Overall <strong>outcome of session</strong> &#40;please check all that
					apply
					&#41;</label>

				<div class="checkbox">
					<label>
						<input type="checkbox" name="checkbox-3" data-mincheck="1">
						The student reported that his/her questions/concerns had been addressed
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="checkbox-3" data-mincheck="1">
						The student asked to schedule another session to discuss issues further
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="checkbox-3" data-mincheck="1">
						The student was advised to return after clarifying her/his concerns
					</label>
				</div>
			</div>
			<div class="col-md-12">
				<div class="col-md-6">
					<hr/>
				</div>
				<label class="col-md-12" for="focus-of-conference-1">Additional comments</label>
				<textarea name="conclusion-additional-comments" id="conclusion-additional-comments"
				          class="form-control count-textarea"
				          data-parsley-trigger="keyup" data-parsley-minlength="10" data-parsley-maxlength="512"
				          data-parsley-validation-threshold="5"
				          data-parsley-minlength-message="Come on! You need to enter at least a 10 characters long
					          comment.."><?php echo htmlspecialchars($reports[$i][ReportFetcher::DB_COLUMN_ADDITIONAL_COMMENTS]); ?></textarea>

			</div>
		</div>

		<br/>

		<div class="form-group">

			<div class="col-md-12">
				<?php
				if (($user->isTutor() && strcmp($reports[$i][ReportFetcher::DB_COLUMN_LABEL_MESSAGE], Report::LABEL_MESSAGE_PENDING_FILL) === 0)
					|| !$user->isTutor() && strcmp($reports[$i][ReportFetcher::DB_COLUMN_LABEL_MESSAGE], Report::LABEL_MESSAGE_PENDING_VALIDATION) === 0
				): ?>
					<div class="col-md-3 col-sm-6 col-xs-6">

						<button type="submit" name="btn-update-report"
						        class="btn btn-default temp-save-report-btn ui-tooltip"
						        data-toggle="tooltip"
						        data-placement="bottom"
						        data-trigger="hover"
						        title="Partially fill report. You'll be able to edit the report as much you want up until you complete it.">
							Save Changes
						</button>
					</div>
					<div class="col-md-3 col-sm-6 col-xs-6">

						<button type="submit" name="btn-complete-report" class="btn btn-primary ui-tooltip"
						        data-toggle="tooltip"
						        data-placement="bottom"
						        data-trigger="hover" title="<?php echo $user->isTutor() ? "You won't be able to edit the report anymore. The responsible
				        secretary will have to validate it." : "Report will be finalized, and un-editable."; ?>">
							<?php echo $user->isTutor() ? "Complete Report" : "Validate Report"; ?>
						</button>
					</div>
				<?php endif; ?>

				<input type="hidden" name="form-update-report-id"
				       value="<?php echo $reports[$i][ReportFetcher::DB_COLUMN_ID]; ?>">
			</div>
			<!-- /.col -->

		</div>
		<!-- /.form-group -->

		</form>
		</div>
		<!-- /.tab-pane fade -->

	<?php
	}

}?>

</div>
<!-- ./tab-content -->


</div>
<!-- /.col -->

</div>
<!-- /.row -->
</div>
<!-- /#content-container -->

<div id="push"></div>

</div>
<!-- #content -->

<?php include ROOT_PATH . "views/footer.php"; ?>
</div>
<!-- #wrapper<!-- #content -->


<?php include ROOT_PATH . "views/assets/footer_common.php"; ?>

<script src="<?php echo BASE_URL; ?>assets/js/plugins/autosize/jquery.autosize.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/select2/select2.js"></script>
<script
	src="<?php echo BASE_URL; ?>assets/js/plugins/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js">
</script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/parsley/parsley.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/fullcalendar/fullcalendar.min.js"></script>

<script type="text/javascript">
	$(function () {
		var $dateTimePickerStart = $('#dateTimePickerStart');
		var $courseId = $("#courseId");
		var $termId = $("#termId");
		var $tutorId = $("#tutorId");
		var $dateTimePickerStart = $('#dateTimePickerStart');
		var $dateTimePickerEnd = $('#dateTimePickerEnd');
		var $dateTimePickerEnd2 = $('#dateTimePickerEnd');
		var $instructors = $(".instructors");
		var $students = $(".students");

		$('.temp-save-report-btn').click(function () {
			$(this).closest('.form-horizontal.reports-update-form').parsley().destroy();
		});

		$(".focus-conference-3-other-class, .focus-conference-3-exercise-class").change(function () {
			var $inputCheckbox = $(this).find('input[type=checkbox]');
			var $inputText = $(this).find('input[type=text]');

			if ($inputCheckbox.is(':checked') && $inputText.attr('disabled')) {
				$inputText.removeAttr('disabled');
				$inputText.attr('required', 'required');
				$inputText.focus();
			} else if (!$inputText.val() || !$inputCheckbox.is(':checked')) {
				$inputText.attr('disabled', 'disabled');
				$inputText.removeAttr('required');
				$inputText.val('');
			}

		});


		<?php for($i = 0; $i < sizeof($studentsAppointmentData); $i++){?>
		$("#studentId<?php echo $i;?>").select2();
		$("#instructorId<?php echo $i;?>").select2();
		$("#studentId<?php echo $i;?>").select2("val", '<?php echo $studentsAppointmentData[$i][AppointmentHasStudentFetcher::DB_COLUMN_STUDENT_ID]?>');
		$("#instructorId<?php echo $i;?>").select2("val", '<?php echo $studentsAppointmentData[$i][AppointmentHasStudentFetcher::DB_COLUMN_INSTRUCTOR_ID]?>');
		$("#instructorId<?php echo $i;?>").select2("enable", false);
		<?php } ?>

		<?php if ($studentsAppointmentData[0][AppointmentHasStudentFetcher::DB_COLUMN_REPORT_ID] !== NULL): ?>
		$('.list-group-item').on('click', function () {
			if ($(this).attr('class') != 'list-group-item active') {
				$('.list-group-item.active').removeClass('active');
				$(this).addClass('active');

				$('.form-horizontal.reports-update-form').each(function () {
					$(this).parsley().destroy();
					$(this).parsley();
				});

				$('.count-textarea').each(function () {
					$(this).fadeOut(function () {
						$(this).autosize().show().trigger('autosize.resize');
					});
				});

			}
		});
		$('.count-textarea').each(function () {
			$(this).textareaCount({
				maxCharacterSize: 512,
				warningNumber: 40,
				displayFormat: '#input/#max | #words words'
			});
		});
		<?php endif; ?>


		$courseId.select2();
		$courseId.select2("val", '<?php echo $studentsAppointmentData[0][AppointmentFetcher::DB_COLUMN_COURSE_ID]?>');
		$tutorId.select2();
		$tutorId.select2("val", '<?php echo $studentsAppointmentData[0][UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_ID]?>');


		var startDateDefault;
		if (moment().minute() >= 30) {
			startDateDefault = moment().add('1', 'hours');
			startDateDefault.minutes(0);
		} else {
			startDateDefault = moment();
			startDateDefault.minutes(30);
		}

		var minimumStartDate = startDateDefault.clone();
		minimumStartDate.subtract('31', 'minutes');
		var minimumMaxDate = moment().add('14', 'day');

		var endDateDefault = startDateDefault.clone();
		endDateDefault.add('30', 'minutes');
		var minimumEndDate = endDateDefault.clone();
		minimumEndDate.subtract('31', 'minutes');

		$dateTimePickerStart.datetimepicker({
			defaultDate: '<?php echo $startDateTime->format('m/d/Y g:i A');?>',
			minDate: minimumStartDate,
			maxDate: minimumMaxDate,
			minuteStepping: 30,
			daysOfWeekDisabled: [0, 6],
			sideBySide: true,
			strict: true
		});

		$dateTimePickerEnd.datetimepicker({
			defaultDate: '<?php echo $endDateTime->format('m/d/Y g:i A');?>',
			minDate: minimumEndDate,
			minuteStepping: 30,
			daysOfWeekDisabled: [0, 6],
			sideBySide: true,
			strict: true
		});

	});
</script>

</body>
</html>