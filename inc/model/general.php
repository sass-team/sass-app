<?php

/**
 * Created by PhpStorm.
 * Class for general info
 *    - Log In / Log Out Redirect functionalities
 * User: Rizart Dokollari
 * Date: 5/20/14
 * Time: 12:52 AM
 */
class General {

	/**
	 * If a log in session exists then to home.php
	 */
	public function logged_in_protect() {
		if ($this->logged_in() === true) {
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
	public function logged_in() {
		return isset($_SESSION['email']) ? true : false;
	}
}