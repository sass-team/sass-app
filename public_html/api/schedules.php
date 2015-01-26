<?php

if (is_ajax()) {

	require "../app/config/app.php";

	header('Content-Type: application/json');

	if (isset($_GET["action"]) && !empty($_GET["action"])) { //Checks if action value exists
		$action = $_GET["action"];

		switch ($action) { //Switch case for value of action
			case "all_tutors_working_hours":
				printAllTutorsSchedules($_GET["termId"], $_GET["start"], $_GET["end"]);
				break;
			case "single_tutor_working_hours":
				printSingleTutorSchedules($_GET["tutorId"], $_GET["termId"], $_GET["start"], $_GET["end"]);
				break;
			case "many_tutor_working_hours":
				printManyTutorSchedulesForCourse($_GET["courseId"], $_GET["termId"], $_GET["start"], $_GET["end"]);
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

//printAllTutorsSchedules( 4, 1409582384, 1412087984);


//Function to check if the request is an AJAX request
function is_ajax() {
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function printAllTutorsSchedules($termId, $start, $end) {
	$workingHours = Schedule::getTutorsOnTerm($termId);

	$workingHoursJSON = generateCalendarData($start, $end, $workingHours);

	echo json_encode($workingHoursJSON);
}

function printManyTutorSchedulesForCourse($courseId, $termId, $start, $end) {
	$workingHours = Schedule::getTutorsOnTermOnCourse($courseId, $termId);

	$workingHoursJSON = generateCalendarData($start, $end, $workingHours);

	echo json_encode($workingHoursJSON);
}

function printSingleTutorSchedules($tutorId, $termId, $start, $end) {
	$workingHours = Schedule::getSingleTutorOnTerm($tutorId, $termId);
	$workingHoursJSON = generateCalendarData($start, $end, $workingHours);

	echo json_encode($workingHoursJSON);
}

/**
 * @param $start
 * @param $end
 * @param $workingHours
 * @return array
 */
function generateCalendarData($start, $end, $workingHours) {
	date_default_timezone_set('Europe/Athens');

	$requestedStart = new DateTime();
	$requestedEnd = new DateTime();
	$requestedStart->setTimestamp($start);
	$requestedEnd->setTimestamp($end);

	$workingHoursJSON = array();
	foreach ($workingHours as $workingHour) {
		$startTerm = new DateTime($workingHour[TermFetcher::DB_TABLE . "_" . TermFetcher::DB_COLUMN_START_DATE]);
		$endTerm = new DateTime($workingHour[TermFetcher::DB_TABLE . "_" . TermFetcher::DB_COLUMN_END_DATE]);

		$dayOfYearStartTerm = intval($startTerm->format("z")) + 1;
		$dayOfYearEndTerm = intval($endTerm->format("z")) + 1;
		$dayOfYearStartRequested = intval($requestedStart->format("z"));
		$dayOfYearEndRequested = intval($requestedEnd->format("z")) + 1;

		$dayOfYearStart = $dayOfYearStartRequested < $dayOfYearStartTerm ? $dayOfYearStartTerm : $dayOfYearStartRequested;
		$dayOfYearEnd = $dayOfYearEndRequested > $dayOfYearEndTerm ? $dayOfYearEndRequested : $dayOfYearEndTerm;

		while ($dayOfYearStart <= $dayOfYearEnd + 1) {
			$weekOfYearStart = $dayOfYearStart / 7 + 1;
			$dayOfWeek = $dayOfYearStart % 7 + 1;


			switch ($dayOfWeek) {
				case 1:
					if ($workingHour[ScheduleFetcher::DB_COLUMN_MONDAY] == 1) {
						$workingHoursJSON[] = generateDay($startTerm, $endTerm, $weekOfYearStart, $dayOfWeek, $workingHour);
					}
					break;
				case 2:
					if ($workingHour[ScheduleFetcher::DB_COLUMN_TUESDAY] == 1) {
						$workingHoursJSON[] = generateDay($startTerm, $endTerm, $weekOfYearStart, $dayOfWeek, $workingHour);
					}
					break;
				case 3:
					if ($workingHour[ScheduleFetcher::DB_COLUMN_WEDNESDAY] == 1) {
						$workingHoursJSON[] = generateDay($startTerm, $endTerm, $weekOfYearStart, $dayOfWeek, $workingHour);
					}
					break;
				case 4:
					if ($workingHour[ScheduleFetcher::DB_COLUMN_THURSDAY] == 1) {
						$workingHoursJSON[] = generateDay($startTerm, $endTerm, $weekOfYearStart, $dayOfWeek, $workingHour);
					}
					break;
				case 5:
					if ($workingHour[ScheduleFetcher::DB_COLUMN_FRIDAY] == 1) {
						$workingHoursJSON[] = generateDay($startTerm, $endTerm, $weekOfYearStart, $dayOfWeek, $workingHour);
					}
					break;
				default:
					$dayOfYearStart++;
			}
			$dayOfYearStart++;
		}
	}
	return $workingHoursJSON;
}

/**
 * http://stackoverflow.com/a/9018728/2790481
 * @param $year
 * @return int
 */
function getIsoWeeksInYear($year) {
	date_default_timezone_set('Europe/Athens');
	$date = new DateTime;
	$date->setISODate($year, 53);
	return ($date->format("W") === "53" ? 53 : 52);
}

/**
 * @param $startTerm
 * @param $endTerm
 * @param $weekStart
 * @param $dayOfWeek
 * @param $workingHour
 * @param $workingHoursJSON
 * @return array
 */
function generateDay($startTerm, $endTerm, $weekStart, $dayOfWeek, $workingHour) {
	$dayScheduleStart = new DateTime($workingHour[ScheduleFetcher::DB_COLUMN_START_TIME]);
	$dayScheduleEnd = new DateTime($workingHour[ScheduleFetcher::DB_COLUMN_END_TIME]);

	$dayScheduleYearStart = $startTerm->format("Y");
	$dayScheduleYearEnd = $endTerm->format("Y");

	$dayScheduleStart->setISODate($dayScheduleYearStart, $weekStart, $dayOfWeek);
	$dayScheduleEnd->setISODate($dayScheduleYearEnd, $weekStart, $dayOfWeek);

	$tutorName = $workingHour[UserFetcher::DB_COLUMN_FIRST_NAME] . " " . $workingHour[UserFetcher::DB_COLUMN_LAST_NAME];
	$tutorsUrl = App::getDomainName() . "/staff/edit/" . $workingHour[UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_ID];

	return array('title' => $tutorName, 'start' => $dayScheduleStart->format('Y-m-d H:i:s'), 'end' =>
		$dayScheduleEnd->format('Y-m-d H:i:s'), 'allDay' => false, 'url' => $tutorsUrl, 'color' => '#f0ad4e');
}


