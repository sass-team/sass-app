<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 10/15/2014
 * Time: 5:31 PM
 */

require __DIR__ . '/../app/init.php';

try {
	Report::deleteWithAppointmentId(8);
//	AppointmentHasStudentFetcher::disconnectReport(67);

} catch (Exception $e) {
	var_dump($e->getMessage());
}
