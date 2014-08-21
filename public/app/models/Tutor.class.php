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

	public function retrieveCoursesNotTeaching() {
		$query = "
			SELECT course.code AS 'Code', course.name AS 'Name',  course.id
				FROM  `" . DB_NAME . "`.course
				WHERE NOT EXISTS (
					SELECT course_id FROM `sass-ms`.tutor_teaches_course WHERE course.id = tutor_teaches_course.course_id
				);
			";

		try {
			$query = $this->getDb()->getConnection()->prepare($query);
			$query->execute();
			return $query->fetchAll(PDO::FETCH_ASSOC);

		} catch (PDOException $e) {
			throw new Exception("Could not retrieve courses data from database.");
		}
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
		$query = "SELECT course.code AS 'Code', course.name AS  'Name', tutor_teaches_course.course_id  AS id
					FROM `" . DB_NAME . "`.course, `" . DB_NAME . "`.tutor_teaches_course
					WHERE tutor_teaches_course.course_id = course.id;";

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

	public function addTeachingCourses($coursesIds) {
		$tutorId = $this->getId();

		try {
			foreach ($coursesIds as $courseId) {
				$query = "INSERT INTO `" . DB_NAME . "`.`tutor_teaches_course` (`tutor_user_id`, `course_id`) VALUES(:id, :courseId);";
				$query = $this->getDb()->getConnection()->prepare($query);
				$query->bindParam(':id', $tutorId, PDO::PARAM_INT);
				$query->bindParam(':courseId', $courseId, PDO::PARAM_INT);
				$query->execute();
			}

			return true;
		} catch (Exception $e) {
			throw new Exception("Could not insert teaching courses data into database.");
		}
	}

	public function updateTeachingCourse($newCourseId, $oldCourseId) {
		$tutorId = $this->getId();

		if (!preg_match('/^[0-9]+$/', $newCourseId) || !preg_match('/^[0-9]+$/', $oldCourseId) || strcmp($newCourseId, $oldCourseId) === 0) {
			throw new Exception("Data has been tempered. Aborting process.");
		}

		if ($this->teachingCourseExists($newCourseId, $tutorId)) {
			throw new Exception("Data has been tempered. Aborting process.");
		}

		try {
			$query = "UPDATE `" . DB_NAME . "`.`tutor_teaches_course` SET `course_id`= :newCourseId WHERE `tutor_user_id`= :tutorId and`course_id`= :oldCourseId";
			$query = $this->getDb()->getConnection()->prepare($query);
			$query->bindParam(':newCourseId', $newCourseId, PDO::PARAM_INT);
			$query->bindParam(':tutorId', $tutorId, PDO::PARAM_INT);
			$query->bindParam(':oldCourseId', $oldCourseId, PDO::PARAM_INT);
			$query->execute();
		} catch (Exception $e) {
			throw new Exception("Could not replace teaching courses data into database.");
		}
	}

	/**
	 * @param $newCourseId
	 * @param $tutorId
	 * @return bool
	 */
	public function teachingCourseExists($newCourseId, $tutorId) {
		$query = "SELECT course_id FROM `" . DB_NAME . "`.tutor_teaches_course WHERE course_id = :courseId AND tutor_user_id = :tutorId";
		$query = $this->getDb()->getConnection()->prepare($query);
		$query->bindParam(':tutorId', $tutorId, PDO::PARAM_INT);
		$query->bindParam(':courseId', $newCourseId, PDO::PARAM_INT);
		$query->execute();
		if ($query->fetchColumn() === FALSE) {
			return false;
		} else {
			return true;
		}
	}

	public function delTeachingCourse($courseId) {
		if (!preg_match('/^[0-9]+$/', $courseId)) {
			throw new Exception("Data tempering detected.
			<br/>You&#39;re trying to hack this app.<br/>Developers are being notified about this.<br/>Expect us.");
		}
		$tutorId = $this->getId();

		try {

			$query = "DELETE FROM `" . DB_NAME . "`.`tutor_teaches_course` WHERE `tutor_user_id`=:id and`course_id`=:courseId;";
			$query = $this->getDb()->getConnection()->prepare($query);
			$query->bindParam(':id', $tutorId, PDO::PARAM_INT);
			$query->bindParam(':courseId', $courseId, PDO::PARAM_INT);
			$query->execute();
			return true;
		} catch (Exception $e) {
			throw new Exception("Could not delete course from database.");
		}
	}

	/**
	 * @param mixed $tutorMajor
	 */
	private
	function setMajor($majors) {
		$this->major = array_values($majors)[0];
	}

}