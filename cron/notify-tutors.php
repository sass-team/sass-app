<?php

/**
 * Notify tutors with pending appointments and or reports.
 */


require __DIR__ . "/../public_html/app/config/app.php";

// run script only during working hours
//if (!App::isWorkingDateTimeOn())
//{
//	exit();
//}

$pendingAppointments = AppointmentFetcher::retrieveCmpltWithoutRptsOnCurTerms();

// retrieve all tutors
// foreach tutor store his/her pending appointments
// foreach tutor store his/her pending report

$tutors = TutorFetcher::retrieveActive();

foreach ($tutors as $tutor)
{
	try
	{
		$pendingAppointments = AppointmentFetcher::retrievePendingForCurrentTerms($tutor[TutorFetcher::DB_COLUMN_USER_ID]);

		$pendingReports = ReportFetcher::retrievePendingForCurrentTerms($tutor[TutorFetcher::DB_COLUMN_USER_ID]);

		$existPendingAppointments = !empty($pendingAppointments);
		$existsPendingReports = !empty($pendingReports);

		if ($existPendingAppointments || $existsPendingReports)
		{
			$buttonsPending[App::APPOINTMENT_BTN_URL] = $existPendingAppointments ? App::getAppointmentsListUrl() : $existPendingAppointments;
			$buttonsPending[App::REPORT_BTN_URL] = $existsPendingReports ? App::getReportsListUrl() : $existsPendingReports;
			$fullName = $tutor[UserFetcher::DB_COLUMN_FIRST_NAME] . " " . $tutor[UserFetcher::DB_COLUMN_LAST_NAME];

			Mailer::sendPending($buttonsPending, $tutor[TutorFetcher::DB_COLUMN_USER_ID], $tutor[UserFetcher::DB_COLUMN_EMAIL], $fullName);
		}

	} catch (Exception $e)
	{
		var_dump($e->getMessage());
	}
} // end foreach

