<?php
require 'init.php';
header('Content-Type: application/json');

if (is_ajax()) {
    if (isset($_GET["action"]) && !empty($_GET["action"])) { //Checks if action value exists
        $action = $_GET["action"];
        switch ($action) { //Switch case for value of action
            case "all_tutors_working_hours":
                printAllTutorsSchedules($db, $_GET["termId"]);
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

function printAllTutorsSchedules($db, $termId) {

    $workingHours = Schedule::getTutorsOnTerm($db, $termId);
    $workingHoursJSON = array();
    foreach ($workingHours as $workingHour) {
        $tutorName = $workingHour[UserFetcher::DB_COLUMN_FIRST_NAME] . " " . $workingHour[UserFetcher::DB_COLUMN_LAST_NAME];
        $startDate = new DateTime($workingHour[ScheduleFetcher::DB_COLUMN_START_TIME]);
        $endDate = new DateTime($workingHour[ScheduleFetcher::DB_COLUMN_END_TIME]);
        $tutorsUrl = "http://" . $_SERVER['SERVER_NAME'] . "/staff/edit/" . $workingHour[UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_ID];

//        $event->repeat_end =  date('Y-m-d',strtotime("+" . $event->repeat_int . " ".$ext));


        $workingHoursJSON[] = array('title' => $tutorName, 'start' => $startDate->format('Y-m-d H:i:s'), 'end' =>
            $endDate->format('Y-m-d H:i:s'), 'allDay' => false, 'url' => $tutorsUrl, 'color' => '#f0ad4e');
    }

    echo json_encode($workingHoursJSON);
}

function printSingleTutorSchedules($db, $tutorId, $termId, $start, $end) {

    $requestedStart = new DateTime();
    $requestedEnd = new DateTime();
    $requestedStart->setTimestamp($start);
    $requestedEnd->setTimestamp($end);

    $workingHours = Schedule::getSingleTutor($db, $tutorId, $termId);

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
            $dayScheduleStart = $startTerm;
            $dayScheduleEnd = $endTerm;
            $dayScheduleYear = $startTerm->format("Y");

            $dayScheduleStart->setISODate($dayScheduleYear, $weekStart);
            $dayScheduleEnd->setISODate($dayScheduleYear, $weekStart);

            $tutorName = $workingHour[UserFetcher::DB_COLUMN_FIRST_NAME] . " " . $workingHour[UserFetcher::DB_COLUMN_LAST_NAME];
            $tutorsUrl = "http://" . $_SERVER['SERVER_NAME'] . "/staff/edit/" . $workingHour[UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_ID];

            $workingHoursJSON[] = array('title' => $tutorName, 'start' => $dayScheduleStart->format('Y-m-d H:i:s'), 'end' =>
                $dayScheduleEnd->format('Y-m-d H:i:s'), 'allDay' => false, 'url' => $tutorsUrl, 'color' => '#f0ad4e');


            $weekStart++;
        }

    }

    echo json_encode($workingHoursJSON);
}


//printSingleTutorSchedules($db, 11, 2, '2014-01-12', '2013-12-24');


