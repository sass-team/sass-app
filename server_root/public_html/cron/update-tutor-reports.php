<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/18/2014
 * Time: 2:35 PM
 */

require __DIR__ . '/../../app/init.php';


try {
	date_default_timezone_set('Europe/Athens');

	$curWorkingDate = new DateTime();
	$curWorkingHour = intval($curWorkingDate->format('H'));

	if($curWorkingHour < 10 || $curWorkingHour > 18){
		exit();
	}

	$appointments = AppointmentFetcher::retrieveCmpltWithoutRptsOnCurTerms($db);
//	var_dump($appointments);
	$proccessDone = false;
	$message = "";

	foreach ($appointments as $appointment) {
		$proccessDone = true;

		$students = AppointmentHasStudentFetcher::retrieveStudentsWithAppointment($db, $appointment[AppointmentFetcher::DB_COLUMN_ID]);


		foreach ($students as $student) {
			$reportId = ReportFetcher::insert($db, $student[AppointmentHasStudentFetcher::DB_COLUMN_STUDENT_ID],
				$student[AppointmentHasStudentFetcher::DB_COLUMN_ID],
				$student[AppointmentHasStudentFetcher::DB_COLUMN_INSTRUCTOR_ID]);
		}

		AppointmentFetcher::updateLabel($db, $appointment[AppointmentFetcher::DB_COLUMN_ID],
			Appointment::LABEL_MESSAGE_PENDING_TUTOR, Appointment::LABEL_COLOR_WARNING);
		Mailer::sendTutorNewReports($db, $appointment);
		$message .= "Report with $reportId created. Mail sent. <br/>";
	}

} catch (Exception $e) {
	$message = $e;
	Mailer::sendDevelopers($message, __FILE__);
}

