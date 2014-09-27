<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/18/2014
 * Time: 2:35 PM
 */

require __DIR__ . '/../init.php';


try {
	date_default_timezone_set('Europe/Athens');

	$curWorkingDate = new DateTime();
	$curWorkingHour = intval($curWorkingDate->format('H'));

	// save resources - only run cron at hours 08:00 - 18:00
	if($curWorkingHour < 8 || $curWorkingHour > 18){
		exit();
	}

	$appointments = AppointmentFetcher::retrieveCmpltWithoutRptsOnCurTerms($db);

	foreach ($appointments as $appointment) {

		$students = AppointmentHasStudentFetcher::retrieveStudentsWithAppointment($db, $appointment[AppointmentFetcher::DB_COLUMN_ID]);
		foreach ($students as $student) {
			$reportId = ReportFetcher::insert($db, $student[AppointmentHasStudentFetcher::DB_COLUMN_STUDENT_ID],
				$student[AppointmentHasStudentFetcher::DB_COLUMN_ID],
				$student[AppointmentHasStudentFetcher::DB_COLUMN_INSTRUCTOR_ID]);
		}

		AppointmentFetcher::updateLabel($db, $appointment[AppointmentFetcher::DB_COLUMN_ID],
			Appointment::LABEL_MESSAGE_PENDING_TUTOR, Appointment::LABEL_COLOR_WARNING);

		Mailer::sendTutorNewReportsCronOnly($db, $appointment);
	}

} catch (Exception $e) {
	Mailer::sendDevelopers($e, __FILE__);
}

