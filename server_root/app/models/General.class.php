<?php

/**
 * Created by PhpStorm.
 * Class for general info
 *    - Log In / Log Out Redirect functionalities
 * User: Rizart Dokollari & Giorgos Skarlatos
 * Date: 5/20/14
 * Time: 12:52 AM
 */
class General
{

	/**
	 * If a log in session exists then to home.php
	 */
	public function loggedInProtect() {
		if ($this->loggedIn() === true) {
			header('Location: ' . BASE_URL);
			exit();
		}
	} // logged_in

	/**
	 * Checks if a user has logged in.
	 * Returns true if yes. Else false
	 *
	 * @return bool
	 */
	public function loggedIn() {
		return isset($_SESSION['id']) ? true : false;
	}

	#if not logged in then redirect to students.class.php
	public function loggedOutProtect() {
		if ($this->loggedIn() === false) {
			header('Location: ' . BASE_URL . 'login');
			exit();
		}

		// source: http://stackoverflow.com/a/1270960/2790481

		// last request was more than 1 day ago
		if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 86400)) {
			session_unset();     // unset $_SESSION variable for the run-time
			session_destroy();   // destroy session data in storage
			header('Location: ' . BASE_URL . 'login');
			exit();
		}
		$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

		if (!isset($_SESSION['CREATED'])) {
			$_SESSION['CREATED'] = time();
		} else if (time() - $_SESSION['CREATED'] > 3600) {
			// session started more than 1 hour ago
			$id = $_SESSION['id'];

			// better security - avoid fixation attack.
			session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
			$_SESSION['CREATED'] = time();  // update creation time
			$_SESSION['id'] = $id;
			$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
		}
	}
}