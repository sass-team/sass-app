<?php

/**
 * Created by PhpStorm.
 * User: Riza
 * Date: 5/29/14
 * Time: 8:01 PM
 */
class Secretary extends User {
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
		return false;
	}

	public function isSecretary() {
		return true;
	}
} 