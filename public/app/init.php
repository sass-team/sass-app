<?php
/**
 *
 */
ob_start();

#starting the users session
session_start();

require "config/app.php";

// identical to require; but php will include it only if it has not already been included
require_once ROOT_PATH . 'app/config/database.class.php';
require_once ROOT_PATH . "app/models/user.class.php";
require_once ROOT_PATH . "app/models/admin.class.php";
require_once ROOT_PATH . "app/models/tutor.class.php";
require_once ROOT_PATH . "app/models/secretary.class.php";
require_once ROOT_PATH . "app/models/general.class.php";
require_once ROOT_PATH . "app/models/courses.class.php";

$errors = array();

try {
	$db = new Database();
//	$users = new Users($db->getDbConnection());
	$general = new General();


// retrieves data if a user is logged in
	if ($general->loggedIn() === true) {

		// instantiate user class & connect to db.
		$id = $_SESSION['id']; // getting user's id from the session.4

		$data = $db->getData($id);

		if (strcmp($data['type'], 'tutor') === 0) {
			$user = new Tutor($data, $db);
		} else if (strcmp($data['type'], 'secretary') === 0) {
			$user = new Secretary($data, $db);
		} else if (strcmp($data['type'], 'admin') === 0) {
			$user = new Admin($data, $db);
		} else {
			throw new Exception("Something terrible has happened with the database. <br/>The software developers will tremble with fear.");
		}
	}

} catch
(Exception $e) {
	// if no database connection available this app is not able to work.
	$errors[] = $e->getMessage();
	header('Location: ' . BASE_URL . 'error-500.php');
	exit();
}


?>