<?php

if (is_ajax()) {
	require __DIR__ . '/init.php';
	header('Content-Type: application/json');

	if (isset($_GET["action"]) && !empty($_GET["action"])) { //Checks if action value exists
		$action = $_GET["action"];
		switch ($action) { //Switch case for value of action
			case "all_tutors_appointments":
				echo Appointment::getCalendarAllAppointmentsOnTerm($_GET["termId"]);
				break;
			case "single_tutor_working_hours":
				echo Appointment::getCalendarSingleTutorAppointments($_GET["tutorId"], $_GET["termId"]);
				break;
		}
	} else {
		header("Location: /error-403");
		exit();
	}
} else {
	header("Location: /error-403");
	exit();
}

//Function to check if the request is an AJAX request
function is_ajax() {
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}