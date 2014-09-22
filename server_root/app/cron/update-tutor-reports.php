<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/18/2014
 * Time: 2:35 PM
 */

require __DIR__ . '/../init.php';
$schedules = AppointmentFetcher::retrieveCompletedWithoutReports($db);
$proccessDone = false;

foreach ($schedules as $appointment) {
	$proccessDone = true;
	$appointment = $schedules[0];

	$reportId = ReportFetcher::insert($db, $appointment[AppointmentHasStudentFetcher::DB_COLUMN_STUDENT_ID],
		$appointment[AppointmentHasStudentFetcher::DB_TABLE . "_" . AppointmentHasStudentFetcher::DB_COLUMN_ID],
		$appointment[AppointmentHasStudentFetcher::DB_COLUMN_INSTRUCTOR_ID]);
	Mailer::sendTutorNewReport($db, $reportId, $appointment[AppointmentFetcher::DB_COLUMN_TUTOR_USER_ID], $appointment[AppointmentFetcher::DB_COLUMN_COURSE_ID]);
	echo "Report with id $reportId created.<br/>Mail sent.";
}

if (!$proccessDone) {
	echo "No appointment have been completed up to this time";
}
