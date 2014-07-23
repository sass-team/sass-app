<?php

/**
 * Created by PhpStorm.
 * User: Riza
 * Date: 5/29/14
 * Time: 8:00 PM
 */
class Tutor extends User {
	private $major;
	private $teaching_courses;

	/**
	 * Constructor
	 * @param $database
	 */
	public function __construct($allUserData) {
		parent::__construct($allUserData);
		$this->set_majors();
	}

	/**
	 * @param mixed $tutorMajor
	 */
	private function set_majors() {
		$this->major = $tutorMajor;
	}

	public function is_admin() {
		return false;
	}

	public function is_tutor() {
		return true;
	}

	public function is_secretary() {
		return false;
	}
} 