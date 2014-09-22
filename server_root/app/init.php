<?php
/**
 *
 */
ob_start();

#starting the staff session
session_start();

require "config/app.php";

// identical to require; but php will include it only if it has not already been included
require_once ROOT_PATH . 'app/config/database.class.php';
require_once ROOT_PATH . "app/models/General.class.php";

require_once ROOT_PATH . "app/models/Person.class.php";

require_once ROOT_PATH . "app/models/User.class.php";
require_once ROOT_PATH . "app/models/UserFetcher.class.php";

require_once ROOT_PATH . "app/models/Admin.class.php";
require_once ROOT_PATH . "app/models/Tutor.class.php";
require_once ROOT_PATH . "app/models/TutorFetcher.class.php";
require_once ROOT_PATH . "app/models/TutorHasCourseHasTerm.class.php";
require_once ROOT_PATH . "app/models/TutorHasCourseHasTermFetcher.class.php";
require_once ROOT_PATH . "app/models/TermFetcher.class.php";
require_once ROOT_PATH . "app/models/Term.class.php";
require_once ROOT_PATH . "app/models/Appointment.class.php";
require_once ROOT_PATH . "app/models/AppointmentFetcher.class.php";
require_once ROOT_PATH . "app/models/AppointmentHasStudentFetcher.class.php";
require_once ROOT_PATH . "app/models/Schedule.class.php";
require_once ROOT_PATH . "app/models/ScheduleFetcher.class.php";

require_once ROOT_PATH . "app/models/ReportFetcher.php";

require_once ROOT_PATH . "app/models/Secretary.class.php";

require_once ROOT_PATH . "app/models/InstructorFetcher.class.php";
require_once ROOT_PATH . "app/models/Instructor.class.php";
require_once ROOT_PATH . "app/models/UserTypes.class.php";
require_once ROOT_PATH . "app/models/MajorFetcher.class.php";
require_once ROOT_PATH . "app/models/Major.class.php";

require_once ROOT_PATH . "app/models/CourseFetcher.class.php";
require_once ROOT_PATH . "app/models/Course.class.php";

require_once ROOT_PATH . "app/models/StudentFetcher.class.php";
require_once ROOT_PATH . "app/models/Student.class.php";

require_once ROOT_PATH . "app/models/Dates.class.php";

require_once ROOT_PATH . "app/models/Mailer.class.php";


$errors = array();

try {
	$db = new Database();
//	$staff = new Users($db->getDbConnection());
	$general = new General();


// retrieves data if a user is logged in
	if ($general->loggedIn() === true) {

		// instantiate user class & connect to db.
		$id = $_SESSION['id']; // getting user's id from the session.4

		$data = User::getSingle($db, $id);

		if (strcmp($data['type'], 'tutor') === 0) {
			$tutor = Tutor::getSingle($db, $id);
			$user = new Tutor($db, $data['id'], $data['f_name'], $data['l_name'], $data['email'], $data['mobile'],
				$data['img_loc'], $data['profile_description'], $data['date'], $data['type'], $data['active'],
				$tutor[MajorFetcher::DB_COLUMN_NAME]);
		} else if (strcmp($data['type'], 'secretary') === 0) {
			$user = new Secretary($db, $data['id'], $data['f_name'], $data['l_name'], $data['email'], $data['mobile'],
				$data['img_loc'], $data['profile_description'], $data['date'], $data['type'], $data['active']);
		} else if (strcmp($data['type'], 'admin') === 0) {
			$user = new Admin($db, $data['id'], $data['f_name'], $data['l_name'], $data['email'], $data['mobile'],
				$data['img_loc'], $data['profile_description'], $data['date'], $data['type'], $data['active']);
		} else {
			throw new Exception("Something terrible has happened with the database. <br/>The software developers will tremble with fear." . var_dump($id));
		}
	}

} catch (Exception $e) {
	// if no database connection available this app is not able to work.
	$errors[] = $e->getMessage();
	header('Location: ' . BASE_URL . 'error-500.php');
	exit();
}
