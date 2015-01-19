<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/18/2014
 * Time: 2:35 PM
 */


try
{
	require "../public_html/app/config/app.php";
	date_default_timezone_set('Europe/Athens');


	// run script only during working hours  every two hours
	if ( ! App::isWorkingDateTimeOn())
	{
		exit();
	}

	$appointments = AppointmentFetcher::retrieveCmpltWithoutRptsOnCurTerms();

	foreach ($appointments as $appointment)
	{

		$students = AppointmentHasStudentFetcher::retrieveStudentsWithAppointment($appointment[AppointmentFetcher::DB_COLUMN_ID]);
		foreach ($students as $student)
		{
			$reportId = ReportFetcher::insert($student[AppointmentHasStudentFetcher::DB_COLUMN_STUDENT_ID],
				$student[AppointmentHasStudentFetcher::DB_COLUMN_ID], $student[AppointmentHasStudentFetcher::DB_COLUMN_INSTRUCTOR_ID]);
		}

		AppointmentFetcher::updateLabel($appointment[AppointmentFetcher::DB_COLUMN_ID], Appointment::LABEL_MESSAGE_COMPLETE, Appointment::LABEL_COLOR_SUCCESS);

//		Mailer::sendTutorNewReportsCronOnly($appointment);
	}

} catch (Exception $e)
{
	Mailer::sendDevelopers($e, __FILE__);
}

require __DIR__ . "/export-appointments-excel.php";

