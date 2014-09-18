<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/18/2014
 * Time: 2:35 PM
 */

require __DIR__ . '/../../app/init.php';
$appointments = AppointmentFetcher::retrieveAllCompleted($db);
var_dump($appointments);

foreach ($appointments as $appointment) {
	// create report -- tutor can edit on his page
	// mail tutor
}