<?php

/**
 * Created by PhpStorm.
 * User: Riza
 * Date: 5/29/14
 * Time: 8:00 PM
 */
class Tutor extends User
{
	const DB_TABLE = "tutor";
	const DB_TABLE_TUTOR_HAS_COURSE_HAS_SCHEDULE = "tutor_has_course_has_term";
	const DB_TABLE_TUTOR_HAS_COURSE_TUTOR_USER_ID = "tutor_user_id";
	const DB_COLUMN_USER_ID = "user_id";
	const DB_COLUMN_COURSE_ID = "course_id";
	const DB_COLUMN_MAJOR_ID = "major_id";


	private $majorId;
	private $teachingCourses;

	public function __construct($db, $id, $firstName, $lastName, $email, $mobileNum, $avatarImgLoc, $profileDescription, $dateAccountCreated, $userType, $accountActiveStatus, $majorId) {
		parent::__construct($db, $id, $firstName, $lastName, $email, $mobileNum, $avatarImgLoc, $profileDescription, $dateAccountCreated, $userType, $accountActiveStatus);
		$this->setMajorId($majorId);
	}

	public static function retrieveCoursesNotTeaching($db, $id) {
		$query =
			"SELECT `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_CODE . "` AS 'code', `" . CourseFetcher::DB_TABLE . "`.`" .
			CourseFetcher::DB_COLUMN_NAME . "` AS 'name',  `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_ID . "`
		FROM  `" . DB_NAME . "`.`" . CourseFetcher::DB_TABLE . "`
		WHERE NOT EXISTS (
			SELECT `" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_SCHEDULE . "`.`" . self::DB_COLUMN_COURSE_ID . "`
			FROM  `" . DB_NAME . "`.`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_SCHEDULE . "`
			WHERE `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_ID . "` = `" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_SCHEDULE . "`.`" . self::DB_COLUMN_COURSE_ID . "`
			AND
			`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_SCHEDULE . "`.`" . self::DB_TABLE_TUTOR_HAS_COURSE_TUTOR_USER_ID . "` = :tutorUserId
		)";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':tutorUserId', $id, PDO::PARAM_INT);
			$query->execute();
			return $query->fetchAll(PDO::FETCH_ASSOC);

		} catch (PDOException $e) {
			throw new Exception("Could not retrieve courses not teaching by this user from database.");
		}
	}

	public static function retrieveTeachingCourses($db, $id) {


		$query = "SELECT `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_CODE . "` , `" . CourseFetcher::DB_TABLE . "`.`" .
			CourseFetcher::DB_COLUMN_NAME . "` , `" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_SCHEDULE . "`.`" . self::DB_COLUMN_COURSE_ID . "`
					FROM `" . DB_NAME . "`.`" . CourseFetcher::DB_TABLE . "`, `" . DB_NAME . "`.`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_SCHEDULE . "`
					WHERE `" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_SCHEDULE . "`.`" . self::DB_COLUMN_COURSE_ID . "` =
					`" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_ID . "` AND
					`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_SCHEDULE . "`.`" . self::DB_TABLE_TUTOR_HAS_COURSE_TUTOR_USER_ID . "` = :tutorId;";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':tutorId', $id, PDO::PARAM_INT);

			$query->execute();
			return $query->fetchAll(PDO::FETCH_ASSOC);

		} catch (PDOException $e) {
			throw new Exception("Could not retrieve teaching courses data from database.");}

	}

	public static function updateTeachingCourse($db, $id, $newCourseId, $oldCourseId) {

		if (!preg_match('/^[0-9]+$/', $newCourseId) || !preg_match('/^[0-9]+$/', $oldCourseId) || strcmp($newCourseId, $oldCourseId) === 0) {
			throw new Exception("Data has been tempered. Aborting process.");
		}

		if (self::teachingCourseExists($db, $newCourseId, $id)) {
			throw new Exception("Data has been tempered. Aborting process.");
		}

		try {
			$query = "UPDATE `" . DB_NAME . "`.`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_SCHEDULE . "` SET `course_id`= :newCourseId WHERE `tutor_user_id`= :tutorId and`course_id`= :oldCourseId";
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':newCourseId', $newCourseId, PDO::PARAM_INT);
			$query->bindParam(':tutorId', $id, PDO::PARAM_INT);
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
	public static function teachingCourseExists($db, $newCourseId, $tutorId) {
		$query = "SELECT course_id FROM `" . DB_NAME . "`.`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_SCHEDULE . "` WHERE course_id = :courseId AND tutor_user_id = :tutorId";
		$query = $db->getConnection()->prepare($query);
		$query->bindParam(':tutorId', $tutorId, PDO::PARAM_INT);
		$query->bindParam(':courseId', $newCourseId, PDO::PARAM_INT);
		$query->execute();
		if ($query->fetchColumn() === FALSE) {
			return false;
		} else {
			return true;
		}
	}

	public static function insertMajor($db, $id, $majorId) {
		Major::validateId($db, $majorId);
		TutorFetcher::insertMajor($db, $id, $majorId);
	}

	public static function replaceMajorId($db, $id, $newMajorId, $oldMajorId) {
		// no changes made. no need to do any work.
		if (strcmp($newMajorId, $oldMajorId) === 0) return false;

		Tutor::validateId($db, $id);
		Major::validateId($db, $newMajorId);
		Major::validateId($db, $oldMajorId);

		TutorFetcher::replaceMajorId($db, $id, $newMajorId);
		return true;
	}

	public static function validateId($db, $id) {
		if (!preg_match('/^[0-9]+$/', $id) || !TutorFetcher::existsUserId($db, $id)) {
			throw new Exception("Data tempering detected.
			<br/>You&#39;re trying to hack this app.<br/>Developers are being notified about this.<br/>Expect Us.");
		}
	}

	/**
	 * @return mixed
	 */
	public function getTeachingCourses() {
		return $this->teachingCourses;
	}

	/**
	 * @param mixed $teachingCourses
	 */
	public function setTeachingCourses($teachingCourses) {
		$this->teachingCourses = $teachingCourses;
	}

	/**
	 * @return mixed
	 */
	public function getMajorId() {
		return $this->majorId;
	}

	/**
	 * @param mixed $major
	 */
	public function setMajorId($major) {
		$this->majorId = $major;
	}

	public function isTutor() {
		return true;
	}

	public function deleteTeachingCourse($courseId) {
		if (!preg_match('/^[0-9]+$/', $courseId)) {
			throw new Exception("Data tempering detected.
			<br/>You&#39;re trying to hack this app.<br/>Developers are being notified about this.<br/>Expect Us.");
		}
		$tutorId = $this->getId();

		try {

			$query = "DELETE FROM `" . DB_NAME . "`.`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_SCHEDULE . "` WHERE `tutor_user_id`=:id AND`course_id`=:courseId;";
			$query = $this->getDb()->getConnection()->prepare($query);
			$query->bindParam(':id', $tutorId, PDO::PARAM_INT);
			$query->bindParam(':courseId', $courseId, PDO::PARAM_INT);
			$query->execute();
			return true;
		} catch (Exception $e) {
			throw new Exception("Could not delete course from database.");
		}
	}
}