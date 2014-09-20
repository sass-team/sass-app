<?php
require 'init.php';
header('Content-Type: application/json');

if (is_ajax()) {
	if (isset($_GET["action"]) && !empty($_GET["action"])) { //Checks if action value exists
		$action = $_GET["action"];
		switch ($action) { //Switch case for value of action
			case "tutors":
				printTutors($db);
				break;
		}
	}
}

//Function to check if the request is an AJAX request
function is_ajax() {
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function printTutors($db) {
	$tutors = Course::getTutors($db, $_GET['courseId']);
	$return["tutors"] = json_encode($tutors);
	echo json_encode($tutors);
}
