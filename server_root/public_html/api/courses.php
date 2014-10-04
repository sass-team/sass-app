<?php
require 'init.php';
header('Content-Type: application/json');

if (is_ajax()) {
	try {
		if (isset($_GET["action"]) && !empty($_GET["action"])) { //Checks if action value exists
			$action = $_GET["action"];
			switch ($action) { //Switch case for value of action
				case "tutor_has_courses":
					printTutorHasCourses($db);
					break;
				case "courses_on_term":
					printCoursesOnTerm($db, $_GET["termId"]);
					break;
			}
		}
	} catch (Exception $e) {
		header('Location: ' . BASE_URL . "error-403");
		exit();
	}

} else {
	header('Location: ' . BASE_URL . "error-403");
	exit();
}

//Function to check if the request is an AJAX request
function is_ajax() {
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function printTutorHasCourses($db) {
	$tutors = Course::getTutors($db, $_GET['courseId']);
	echo json_encode($tutors);
}

function printCoursesOnTerm($db, $termId) {
	$courses = Course::getForTerm($db, $termId);
	echo json_encode($courses);
}