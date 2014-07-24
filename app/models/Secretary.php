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

	public function is_admin() {
		return false;
	}

	public function is_tutor() {
		return false;
	}

	public function is_secretary() {
		return true;
	}
} 