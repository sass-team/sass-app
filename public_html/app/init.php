<?php
/**
 *
 */
ob_start();

#starting the staff session
session_start();

require "config/app.php";

$errors = array();

date_default_timezone_set('Europe/Athens');


try {
//	$staff = new Users(->getDbConnection());
	$general = new General();


// retrieves data if a user is logged in
	if ($general->loggedIn() === true) {

		// instantiate user class & connect to db.
		$id = $_SESSION['id']; // getting user's id from the session.4

		$data = User::getSingle($id);

		if (strcmp($data['type'], 'tutor') === 0) {
			$tutor = Tutor::getSingle($id);
			$user = new Tutor($data['id'], $data['f_name'], $data['l_name'], $data['email'], $data['mobile'], $data['img_loc'], $data['profile_description'], $data['date'], $data['type'], $data['active'], $tutor[MajorFetcher::DB_COLUMN_NAME]);
		} else if (strcmp($data['type'], 'secretary') === 0) {
			$user = new Secretary($data['id'], $data['f_name'], $data['l_name'], $data['email'], $data['mobile'], $data['img_loc'], $data['profile_description'], $data['date'], $data['type'], $data['active']);
		} else if (strcmp($data['type'], 'admin') === 0) {
			$user = new Admin($data['id'], $data['f_name'], $data['l_name'], $data['email'], $data['mobile'], $data['img_loc'], $data['profile_description'], $data['date'], $data['type'], $data['active']);
		} else {
			throw new Exception("Something terrible has happened with the database. <br/>The software developers will tremble with fear.");
		}
	}

} catch (Exception $e) {
	// if no database connection available this app is not able to work.
	$_SESSION['errors'] = $e->getMessage();
	header('Location: ' . BASE_URL . 'error-500.php');
	exit();
}
