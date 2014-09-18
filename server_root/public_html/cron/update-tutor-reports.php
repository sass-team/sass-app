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

	// create report with
	Report::
//	$appointmentHasStudents = AppointmentHasStudentFetcher::retrieveJoinReport($db, $appointment[AppointmentFetcher::DB_COLUMN_ID]);
//	var_dump($appointmentHasStudents);

//	foreach()
	// create report -- tutor can edit on his page
//	Report::insert($db, $appointment[AppointmentFetcher::DB_COLUMN_ID], $appointment[AppointmentFetcher::DB])
	// mail tutor
}