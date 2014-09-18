<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/18/2014
 * Time: 2:35 PM
 */

require __DIR__ . '/../../app/init.php';
$appointments = AppointmentFetcher::retrieveCompletedWithoutReports($db);
var_dump($appointments);

foreach ($appointments as $appointment) {
	$appointment = $appointments[0];

	$reportId = ReportFetcher::insert($db, $appointment[AppointmentHasStudentFetcher::DB_COLUMN_STUDENT_ID],
		$appointment[AppointmentHasStudentFetcher::DB_TABLE . "_" . AppointmentHasStudentFetcher::DB_COLUMN_ID],
		$appointment[AppointmentHasStudentFetcher::DB_COLUMN_INSTRUCTOR_ID]);
	Mailer::sendTutorNewReport($db, $reportId, $appointment[AppointmentFetcher::DB_COLUMN_TUTOR_USER_ID], $appointment[AppointmentFetcher::DB_COLUMN_COURSE_ID]);
}
