<?php

/**
 * Created by PhpStorm.
 * User: Riza
 * Date: 5/29/14
 * Time: 8:00 PM
 */
class Tutor extends User {
	/**
	 * Constructor
	 * @param $database
	 */
	public function __construct($allUserData) {
		parent::__construct($allUserData);
	}

	public function isAdmin() {
		return false;
	}

	public function isTutor() {
		return true;
	}

	public function isSecretary() {
		return false;
	}
} 