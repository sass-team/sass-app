<?php
if (is_ajax()) {
	require "../app/config/app.php";
	header('Content-Type: application/json');

	try {
		if (isset($_GET["action"]) && !empty($_GET["action"])) { //Checks if action value exists
			$action = $_GET["action"];
			switch ($action) { //Switch case for value of action
				case "tutor_has_courses":
					printTutorHasCourses();
					break;
				case "courses_on_term":
					printCoursesOnTerm($_GET["termId"]);
					break;
			}
		}
	} catch (Exception $e) {
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

function printTutorHasCourses() {
	$tutors = Course::getTutors($_GET['courseId']);
	echo json_encode($tutors);
}

function printCoursesOnTerm($termId) {
	$courses = Course::getForTerm($termId);
	echo json_encode($courses);
}