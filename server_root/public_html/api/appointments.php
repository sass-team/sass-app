<?php
require 'init.php';
header('Content-Type: application/json');

if (is_ajax()) {
	if (isset($_GET["action"]) && !empty($_GET["action"])) { //Checks if action value exists
		$action = $_GET["action"];
		switch ($action) { //Switch case for value of action
			case "all_tutors_appointments":
				printTutorsAppointments($db, $_GET["termId"]);
				break;
			case "single_tutor_working_hours":
				printSingleTutorAppointments($db, $_GET["tutorId"],  $_GET["termId"]);
				break;
		}
	}
} else {
	header('Location: ' . BASE_URL . "error-403");
	exit();
}

//Function to check if the request is an AJAX request
function is_ajax() {
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function printTutorsAppointments($db, $termId) {

	$appointmentHours = Appointment::getTutorsOnTerm($db, $termId);
	$appointmentHoursJSON = array();
	foreach ($appointmentHours as $appointmentHour) {
		$tutorName = $appointmentHour[CourseFetcher::DB_COLUMN_CODE] . " - " .
			$appointmentHour[UserFetcher::DB_COLUMN_FIRST_NAME] . " " . $appointmentHour[UserFetcher::DB_COLUMN_LAST_NAME];
		$startDate = new DateTime($appointmentHour[AppointmentFetcher::DB_COLUMN_START_TIME]);
		$endDate = new DateTime($appointmentHour[AppointmentFetcher::DB_COLUMN_END_TIME]);
		$appointmentUrl = "http://" . $_SERVER['SERVER_NAME'] . "/appointments/" . $appointmentHour[UserFetcher::DB_COLUMN_ID];

		$appointmentHoursJSON[] = array('title' => $tutorName, 'start' => $startDate->format('Y-m-d H:i:s'), 'end' =>
			$endDate->format('Y-m-d H:i:s'), 'allDay' => false, 'url' => $appointmentUrl, 'color' => '#e5412d');
	}

	echo json_encode($appointmentHoursJSON);
}

function printSingleTutorAppointments($db, $tutorId, $termId) {

	$appointmentHours = Appointment::getSingleTutor($db, $tutorId, $termId);
	$appointmentHoursJSON = array();
	foreach ($appointmentHours as $appointmentHour) {
		$tutorName = $appointmentHour[CourseFetcher::DB_COLUMN_CODE] . " - " .
			$appointmentHour[UserFetcher::DB_COLUMN_FIRST_NAME] . " " . $appointmentHour[UserFetcher::DB_COLUMN_LAST_NAME];
		$startDate = new DateTime($appointmentHour[AppointmentFetcher::DB_COLUMN_START_TIME]);
		$endDate = new DateTime($appointmentHour[AppointmentFetcher::DB_COLUMN_END_TIME]);
		$appointmentUrl = "http://" . $_SERVER['SERVER_NAME'] . "/appointments/" . $appointmentHour[UserFetcher::DB_COLUMN_ID];

		$appointmentHoursJSON[] = array('title' => $tutorName, 'start' => $startDate->format('Y-m-d H:i:s'), 'end' =>
			$endDate->format('Y-m-d H:i:s'), 'allDay' => false, 'url' => $appointmentUrl, 'color' => '#e5412d');
	}

	echo json_encode($appointmentHoursJSON);
}