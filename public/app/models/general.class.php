<?php

/**
 * Created by PhpStorm.
 * Class for general info
 *    - Log In / Log Out Redirect functionalities
 * User: Rizart Dokollari & Giorgos Skarlatos
 * Date: 5/20/14
 * Time: 12:52 AM
 */
class General {

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
	}
}