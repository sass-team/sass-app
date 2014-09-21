<?php
require 'init.php';
header('Content-Type: application/json');

if (is_ajax()) {
	if (isset($_GET["action"]) && !empty($_GET["action"])) { //Checks if action value exists
		$action = $_GET["action"];
		switch ($action) { //Switch case for value of action
			case "all_tutors_working_hours":
				printTutors($db,  $_GET["termId"]);
				break;
			case "single_tutor_working_hours":
				printSingleTutorAppointments($db, $_GET["tutorId"], $_GET["termId"]);
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

function printTutors($db, $termId) {

	$workingHours = Schedule::getTutorsOnTerm($db, $termId);
	$workingHoursJSON = array();
	foreach ($workingHours as $workingHour) {
		$tutorName = $workingHour[UserFetcher::DB_COLUMN_FIRST_NAME] . " " . $workingHour[UserFetcher::DB_COLUMN_LAST_NAME];
		$startDate = new DateTime($workingHour[ScheduleFetcher::DB_COLUMN_START_TIME]);
		$endDate = new DateTime($workingHour[ScheduleFetcher::DB_COLUMN_END_TIME]);
		$tutorsUrl = "http://" . $_SERVER['SERVER_NAME'] . "/staff/edit/" . $workingHour[UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_ID];

		$workingHoursJSON[] = array('title' => $tutorName, 'start' => $startDate->format('Y-m-d H:i:s'), 'end' =>
			$endDate->format('Y-m-d H:i:s'), 'allDay' => false, 'url' => $tutorsUrl, 'color' => '#f0ad4e');
	}

	echo json_encode($workingHoursJSON);
}


function printSingleTutorAppointments($db, $tutorId, $termId) {

	$workingHours = Schedule::getSingleTutor($db, $tutorId, $termId);
	$workingHoursJSON = array();
	foreach ($workingHours as $workingHour) {
		$tutorName = $workingHour[UserFetcher::DB_COLUMN_FIRST_NAME] . " " . $workingHour[UserFetcher::DB_COLUMN_LAST_NAME];
		$startDate = new DateTime($workingHour[ScheduleFetcher::DB_COLUMN_START_TIME]);
		$endDate = new DateTime($workingHour[ScheduleFetcher::DB_COLUMN_END_TIME]);
		$tutorsUrl = "http://" . $_SERVER['SERVER_NAME'] . "/staff/edit/" . $workingHour[UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_ID];

		$workingHoursJSON[] = array('title' => $tutorName, 'start' => $startDate->format('Y-m-d H:i:s'), 'end' =>
			$endDate->format('Y-m-d H:i:s'), 'allDay' => false, 'url' => $tutorsUrl, 'color' => '#f0ad4e');
	}

	echo json_encode($workingHoursJSON);
}