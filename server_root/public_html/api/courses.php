<?php
require 'init.php';

if (is_ajax()) {
	if (isset($_GET["action"]) && !empty($_GET["action"])) { //Checks if action value exists
		$action = $_GET["action"];
		switch ($action) { //Switch case for value of action
			case "tutor_has_courses":
				printTutorHasCourses($db);
				break;
		}
	}
}

//Function to check if the request is an AJAX request
function is_ajax() {
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function printTutorHasCourses($db) {
	$tutors = Course::getTutors($db, $_GET['courseId']);

	//Do what you need to do with the info. The following are some examples.
	//if ($return["favorite_beverage"] == ""){
	//  $return["favorite_beverage"] = "Coke";
	//}
	//$return["favorite_restaurant"] = "McDonald's";

	$return["json"] = json_encode($tutors);
	echo json_encode($tutors);
}
