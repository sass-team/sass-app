<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 10/28/2014
 * Time: 8:23 PM
 */

require "../app/config/app.php";

$courseId = 15;
$termId = 2;
echo Appointment::getCalendarAllAppointmentsForTutorsTeachingOnTerm($courseId, $termId);

