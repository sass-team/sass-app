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
	const DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM = "tutor_has_course_has_term";
	const DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM_TUTOR_USER_ID = "tutor_user_id";
	const DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM_TERM_ID = "term_id";

	const DB_COLUMN_USER_ID = "user_id";
	const DB_COLUMN_COURSE_ID = "course_id";
	const DB_COLUMN_MAJOR_ID = "major_id";


	private $majorId;
	private $teachingCourses;

	public function __construct($db, $id, $firstName, $lastName, $email, $mobileNum, $avatarImgLoc, $profileDescription, $dateAccountCreated, $userType, $accountActiveStatus, $majorId) {
		parent::__construct($db, $id, $firstName, $lastName, $email, $mobileNum, $avatarImgLoc, $profileDescription, $dateAccountCreated, $userType, $accountActiveStatus);
		$this->setMajorId($majorId);
	}

	public static function addCourse($db, $tutorId, $teachingCoursesId, $termId) {
		foreach ($teachingCoursesId as $courseId) {
			Course::validateId($db, $courseId);
			if (Tutor::teachesCourseWithIdOnTerm($db, $courseId, $tutorId, $termId)) {
				throw new Exception("Tutor already teaches a course with id $courseId");
			}
		}
		Term::validateId($db, $termId);

		Tutor_has_course_has_schedule::addCourses($db, $tutorId, $teachingCoursesId, $termId);

	}

	/**
	 * @param $db
	 * @param $newCourseId
	 * @param $tutorId
	 * @param $termId
	 * @return bool
	 */
	public static function teachesCourseWithIdOnTerm($db, $newCourseId, $tutorId, $termId) {
		$query = "SELECT `" . self::DB_COLUMN_COURSE_ID . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "`
			WHERE `" . self::DB_COLUMN_COURSE_ID . "`  = :courseId
			AND `" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM_TUTOR_USER_ID . "`  = :tutorId
			AND `" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM_TERM_ID . "`  = :term_id";

		$query = $db->getConnection()->prepare($query);
		$query->bindParam(':tutorId', $tutorId, PDO::PARAM_INT);
		$query->bindParam(':courseId', $newCourseId, PDO::PARAM_INT);
		$query->bindParam(':term_id', $termId, PDO::PARAM_INT);

		$query->execute();
		if ($query->fetchColumn() === FALSE) {
			return false;
		} else {
			return true;
		}
	}

	public static function hasAppointmentWithId($db, $tutorId, $appointmentId) {
		self::validateId($db, $tutorId);
		Appointment::validateId($db, $appointmentId);
		return TutorFetcher::hasAppointmentWithId($db, $tutorId, $appointmentId);
	}

	public static function validateId($db, $id) {
		if (!preg_match('/^[0-9]+$/', $id) || !TutorFetcher::existsUserId($db, $id)) {
			throw new Exception("Data tempering detected.
			<br/>You&#39;re trying to hack this app.<br/>Developers are being notified about this.<br/>Expect Us.");
		}
	}

	/**
	 * Returns all information of a user given his email.
	 * @param $db
	 * @param $id
	 * @throws Exception
	 */
	public static function  getSingle($db, $id) {
		self::validateId($db, $id);

		return TutorFetcher::retrieveSingle($db, $id);
	}

	public static function retrieveCoursesNotTeaching($db, $id) {
		$query =
			"SELECT `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_CODE . "` AS 'code', `" . CourseFetcher::DB_TABLE . "`.`" .
			CourseFetcher::DB_COLUMN_NAME . "` AS 'name',  `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_ID . "`
		FROM  `" . DB_NAME . "`.`" . CourseFetcher::DB_TABLE . "`
		WHERE NOT EXISTS (
			SELECT `" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "`.`" . self::DB_COLUMN_COURSE_ID . "`
			FROM  `" . DB_NAME . "`.`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "`
			WHERE `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_ID . "` = `" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "`.`" . self::DB_COLUMN_COURSE_ID . "`
			AND
			`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "`.`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM_TUTOR_USER_ID . "` = :tutorUserId
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
			CourseFetcher::DB_COLUMN_NAME . "` , `" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "`.`" . self::DB_COLUMN_COURSE_ID . "`
					FROM `" . DB_NAME . "`.`" . CourseFetcher::DB_TABLE . "`, `" . DB_NAME . "`.`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "`
					WHERE `" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "`.`" . self::DB_COLUMN_COURSE_ID . "` =
					`" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_ID . "` AND
					`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "`.`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM_TUTOR_USER_ID . "` = :tutorId;";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':tutorId', $id, PDO::PARAM_INT);

			$query->execute();
			return $query->fetchAll(PDO::FETCH_ASSOC);

		} catch (PDOException $e) {
			throw new Exception("Could not retrieve teaching courses data from database.");
		}

	}

	public static function updateTeachingCourse($db, $id, $newCourseId, $oldCourseId, $termId) {

		if (!preg_match('/^[0-9]+$/', $newCourseId) || !preg_match('/^[0-9]+$/', $oldCourseId) || strcmp($newCourseId, $oldCourseId) === 0) {
			throw new Exception("Data has been tempered. Aborting process.");
		}

		Term::validateId($db, $termId);

		if (self::teachesCourseWithIdOnTerm($db, $newCourseId, $id, $termId)) {
			throw new Exception("Data has been tempered. Aborting process.");
		}

		try {
			$query = "UPDATE `" . DB_NAME . "`.`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "` SET `course_id`= :newCourseId WHERE `tutor_user_id`= :tutorId and`course_id`= :oldCourseId";
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':newCourseId', $newCourseId, PDO::PARAM_INT);
			$query->bindParam(':tutorId', $id, PDO::PARAM_INT);
			$query->bindParam(':oldCourseId', $oldCourseId, PDO::PARAM_INT);
			$query->execute();
		} catch (Exception $e) {
			throw new Exception("Could not replace teaching courses data into database.");
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

			$query = "DELETE FROM `" . DB_NAME . "`.`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "` WHERE `tutor_user_id`=:id AND`course_id`=:courseId;";
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