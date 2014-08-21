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
	private $notTeachingCourses;

	/**
	 * Constructor
	 * @param $database
	 */
	public function __construct($data, $db) {
		parent::__construct($data, $db);
	}

	/**
	 * @return mixed
	 */
	public function getNotTeachingCourses() {
		if (!isset($this->notTeachingCourses)) {
			$this->notTeachingCourses = $this->retrieveCoursesNotTeaching();
		}
		return $this->notTeachingCourses;

	}

	/**
	 * @param mixed $notTeachingCourses
	 */
	public function setNotTeachingCourses($notTeachingCourses) {
		$this->notTeachingCourses = $notTeachingCourses;
	}

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
		if (!isset($this->teachingCourses)) {
			$this->teachingCourses = $this->retrieveTeachingCourses();
		}
		return $this->teachingCourses;
	}

	/**
	 * @param mixed $teachingCourses
	 */
	public function setTeachingCourses($teachingCourses) {
		$this->teachingCourses = $teachingCourses;
	}

	private function retrieveTeachingCourses() {
		$query = "SELECT course.code AS 'Code', course.name AS  'Name' FROM `" . DB_NAME . "`.course, `" . DB_NAME . "`.tutor_teaches_course
				WHERE tutor_teaches_course.course_id = course.id;";

		try {
			$query = $this->getDb()->getConnection()->prepare($query);
			$query->execute();
			return $query->fetchAll(PDO::FETCH_ASSOC);

		} catch (PDOException $e) {
			throw new Exception("Could not retrieve courses data from database.");
		}
	}

	public function retrieveCoursesNotTeaching() {
		$query = "SELECT course.code AS 'Code', course.name AS  'Name' FROM `" . DB_NAME . "`.course, `" . DB_NAME . "`.tutor_teaches_course
				WHERE tutor_teaches_course.course_id != course.id;";

		try {
			$query = $this->getDb()->getConnection()->prepare($query);
			$query->execute();
			return $query->fetchAll(PDO::FETCH_ASSOC);

		} catch (PDOException $e) {
			throw new Exception("Could not retrieve courses data from database.");
		}
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