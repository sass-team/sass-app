<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/18/2014
 * Time: 2:35 PM
 */


try {
	require "../public_html/app/config/app.php";
	date_default_timezone_set('Europe/Athens');

	$curWorkingDate = new DateTime();
	$curWorkingHour = intval($curWorkingDate->format('H'));
	// save resources - only run cron at hours 08:00 - 18:00
	if ($curWorkingHour < App::WORKING_HOUR_START || $curWorkingHour > App::WORKING_HOUR_END) exit();


	$appointments = AppointmentFetcher::retrieveCmpltWithoutRptsOnCurTerms();

	foreach ($appointments as $appointment) {

		$students = AppointmentHasStudentFetcher::retrieveStudentsWithAppointment($appointment[AppointmentFetcher::DB_COLUMN_ID]);
		foreach ($students as $student) {
			$reportId = ReportFetcher::insert($student[AppointmentHasStudentFetcher::DB_COLUMN_STUDENT_ID],
				$student[AppointmentHasStudentFetcher::DB_COLUMN_ID], $student[AppointmentHasStudentFetcher::DB_COLUMN_INSTRUCTOR_ID]);
		}

		AppointmentFetcher::updateLabel($appointment[AppointmentFetcher::DB_COLUMN_ID], Appointment::LABEL_MESSAGE_COMPLETE, Appointment::LABEL_COLOR_SUCCESS);

//		Mailer::sendTutorNewReportsCronOnly($appointment);
	}

} catch (Exception $e) {
	Mailer::sendDevelopers($e, __FILE__);
}

require __DIR__ . "/export-appointments-excel.php";

