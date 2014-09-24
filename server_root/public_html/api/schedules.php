<?php
require 'init.php';
header('Content-Type: application/json');

if (is_ajax()) {
    if (isset($_GET["action"]) && !empty($_GET["action"])) { //Checks if action value exists
        $action = $_GET["action"];
        switch ($action) { //Switch case for value of action
            case "all_tutors_working_hours":
                printAllTutorsSchedules($db, $_GET["termId"], $_GET["start"], $_GET["end"]);
                break;
            case "single_tutor_working_hours":
                printSingleTutorSchedules($db, $_GET["tutorId"], $_GET["termId"], $_GET["start"], $_GET["end"]);
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

function printAllTutorsSchedules($db, $termId, $start, $end) {
    $workingHours = Schedule::getTutorsOnTerm($db, $termId);

    $workingHoursJSON = generateCalendarData($start, $end, $workingHours);

    echo json_encode($workingHoursJSON);
}

function printSingleTutorSchedules($db, $tutorId, $termId, $start, $end) {
    $workingHours = Schedule::getSingleTutor($db, $tutorId, $termId);
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
    $requestedStart = new DateTime();
    $requestedEnd = new DateTime();
    $requestedStart->setTimestamp($start);
    $requestedEnd->setTimestamp($end);

    $workingHoursJSON = array();
    foreach ($workingHours as $workingHour) {
        $startTerm = new DateTime($workingHour[TermFetcher::DB_TABLE . "_" . TermFetcher::DB_COLUMN_START_DATE]);
        $endTerm = new DateTime($workingHour[TermFetcher::DB_TABLE . "_" . TermFetcher::DB_COLUMN_END_DATE]);

        // only retrieve from requested weeks
        $weekStart = $requestedStart->format('W') > $startTerm->format("W") ? $requestedStart->format('W') :
            $startTerm->format("W");
        $weekEnd = $requestedEnd->format('W') < $endTerm->format("W") ? $requestedEnd->format('W') :
            $endTerm->format("W");

        while ($weekStart <= $weekEnd) {
	        if ($workingHour[ScheduleFetcher::DB_COLUMN_MONDAY] == 1) {
		        $dayOfWeek = 1;
		        $workingHoursJSON[] = generateDay($startTerm, $endTerm, $weekStart, $dayOfWeek, $workingHour);
	        }
            if ($workingHour[ScheduleFetcher::DB_COLUMN_TUESDAY] == 1) {
                $dayOfWeek = 2;
                $workingHoursJSON[] = generateDay($startTerm, $endTerm, $weekStart, $dayOfWeek, $workingHour);
            }

            if ($workingHour[ScheduleFetcher::DB_COLUMN_WEDNESDAY] == 1) {
                $dayOfWeek = 3;
                $workingHoursJSON[] = generateDay($startTerm, $endTerm, $weekStart, $dayOfWeek, $workingHour);
            }

            if ($workingHour[ScheduleFetcher::DB_COLUMN_THURSDAY] == 1) {
                $dayOfWeek = 4;
                $workingHoursJSON[] = generateDay($startTerm, $endTerm, $weekStart, $dayOfWeek, $workingHour);
            }

            if ($workingHour[ScheduleFetcher::DB_COLUMN_FRIDAY] == 1) {
                $dayOfWeek = 5;
                $workingHoursJSON[] = generateDay($startTerm, $endTerm, $weekStart, $dayOfWeek, $workingHour);
            }


            $weekStart++;
        }
    }
    return $workingHoursJSON;
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
    $tutorsUrl = "http://" . $_SERVER['SERVER_NAME'] . "/staff/edit/" . $workingHour[UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_ID];

    return array('title' => $tutorName, 'start' => $dayScheduleStart->format('Y-m-d H:i:s'), 'end' =>
        $dayScheduleEnd->format('Y-m-d H:i:s'), 'allDay' => false, 'url' => $tutorsUrl, 'color' => '#f0ad4e');
}


