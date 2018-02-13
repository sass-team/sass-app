<?php

if (!is_ajax())
{
	header("Location: /error-403");
	exit();
}

require "../app/config/app.php";
header('Content-Type: application/json');

if (isset($_GET["action"]) && !empty($_GET["action"]))
{ //Checks if action value exists
	$action = $_GET["action"];
	switch ($action)
	{ //Switch case for value of action
		case "getAppointments":
			echo Appointment::getCalendarAllAppointmentsOnTerm($_GET["termId"]);
			break;
		case "getAppointmentsForTutor":
			echo Appointment::getCalendarSingleTutorAppointments($_GET["tutorId"], $_GET["termId"]);
			break;
		case "getAppointmentsWithCourse":
			echo Appointment::getCalendarAllAppointmentsForTutorsTeachingOnTerm($_GET["courseId"], $_GET["termId"]);
			break;
		case "getPendingAppointments":
			echo Appointment::getPendingAppointments($_GET["termId"]);
			break;
		case "getPendingAppointmentsWithCourse":
			echo Appointment::getPendingAppointmentsWithCourse($_GET["courseId"], $_GET["termId"]);
			break;
		case "getPendingAppointWithCourseAndTutor":
			echo Appointment::getPendingAppointmentsWithCourseAndTutor($_GET["tutorId"], $_GET["courseId"], $_GET["termId"]);
			break;
		case "getAppointmentWithCourseAndTutor":
			echo Appointment::getAppointmentsForCourseAndTutor($_GET["tutorId"], $_GET["courseId"], $_GET["termId"]);
			break;
	}
} else
{
	header("Location: /error-403");
	exit();
}


//Function to check if the request is an AJAX request
function is_ajax()
{
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
