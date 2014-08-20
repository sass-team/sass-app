<?php

/**
 * Created by PhpStorm.
 * User: Riza
 * Date: 5/29/14
 * Time: 8:00 PM
 */
class Tutor extends User
{
	private $major;
	private $teachingCourses;

	/**
	 * @return mixed
	 */
	public function getMajor() {
		return $this->major;
	}

	/**
	 * @return mixed
	 */
	public function getTeachingCourses() {
		return $this->teachingCourses;
	}

	/**
	 * Constructor
	 * @param $database
	 */
	public function __construct($data, $db) {
		parent::__construct($data, $db);
	}

	public function isTutor() {
		return true;
	}

	/**
	 * @param mixed $tutorMajor
	 */
	private function setMajor($majors) {
		$this->major = array_values($majors)[0];
	}


} 