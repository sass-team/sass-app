<?php
/**
 *
 */
ob_start();

#starting the users session
session_start();

require "config.php";
// identical to require; but php will include it only if it has not already been included
require_once ROOT_PATH . 'inc/DB.php';
require_once ROOT_PATH . "inc/model/User.php";
require_once ROOT_PATH . "inc/model/Users.php";
require_once ROOT_PATH . "inc/model/Admin.php";
require_once ROOT_PATH . "inc/model/Tutor.php";
require_once ROOT_PATH . "inc/model/Secretary.php";
require_once ROOT_PATH . "inc/model/General.php";

$errors = array();

try {
	$db = new DB();
	$users = new Users($db->getDbConnection());
} catch (Exception $e) {
	$errors[] = $e->getMessage();
	header('Location: ' . BASE_URL . 'page-500.html');
	exit();
}
$general = new General();


// retrieves data if a user is logged in
// TODO: CEHCK IF DB IS INITALIZED.
if ($general->logged_in() === true) {
   // instantiate user class & connect to db.

	$user_email = $_SESSION['email']; // getting user's id from the session.4
	$userData = $users->getAllData($user_email);

	// TODO: add secretary role.
	if($userData['type'] == 'admin') {
		$user = new Admin($userData);
	} else {
		$user = new Tutor($userData);
	}
}
?>