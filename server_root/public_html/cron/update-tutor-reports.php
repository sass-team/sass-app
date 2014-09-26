<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/18/2014
 * Time: 2:35 PM
 */

require __DIR__ . '/../../app/init.php';


try {
	$schedules = AppointmentFetcher::retrieveCmpltWithoutRptsOnCurTerms($db);
	var_dump($schedules);
	$proccessDone = false;

	foreach ($schedules as $appointment) {
		$proccessDone = true;

		$reportId = ReportFetcher::insert($db, $appointment[AppointmentHasStudentFetcher::DB_COLUMN_STUDENT_ID],
			$appointment[AppointmentHasStudentFetcher::DB_TABLE . "_" . AppointmentHasStudentFetcher::DB_COLUMN_ID],
			$appointment[AppointmentHasStudentFetcher::DB_COLUMN_INSTRUCTOR_ID]);
//		AppointmentFetcher::updateLabel($db, Appointment::LABEL_MESSAGE_PENDING_TUTOR, Appointment::LABEL_COLOR_WARNING);
		Mailer::sendTutorNewReport($db, $reportId, $appointment[AppointmentFetcher::DB_COLUMN_TUTOR_USER_ID],
			$appointment[AppointmentFetcher::DB_COLUMN_COURSE_ID], $appointment[AppointmentFetcher::DB_COLUMN_TERM_ID]);
		echo "Report with $reportId created.<br/>Mail sent.";
		throw new Exception("stop");
	}

} catch (Exception $e) {
	echo $e->getMessage();
}

//if (!$proccessDone) {
//	echo "No appointment have been completed up to this time";
//}
