<?php

/**
 * Created by PhpStorm.
 * User: rdk
 * Date: 5/29/14
 * Time: 2:13 PM
 */
class Admin extends User {

	/**
	 * Constructor
	 * @param $database
	 */
	public function __construct($allUserData) {
		parent::__construct($allUserData);
	}

	public function is_admin() {
		return true;
	}

	public function is_tutor() {
		return false;
	}

	public function is_secretary() {
		return false;
	}
} 