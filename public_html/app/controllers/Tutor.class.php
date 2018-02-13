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

	public function __construct($id, $firstName, $lastName, $email, $mobileNum, $avatarImgLoc, $profileDescription, $dateAccountCreated, $userType, $accountActiveStatus, $majorId)
	{
		parent::__construct($id, $firstName, $lastName, $email, $mobileNum, $avatarImgLoc, $profileDescription, $dateAccountCreated, $userType, $accountActiveStatus);
		$this->setMajorId($majorId);
	}

	public static function addCourse($tutorId, $teachingCoursesId, $termId)
	{
		foreach ($teachingCoursesId as $courseId)
		{
			Course::validateId($courseId);
			if (Tutor::teachesCourseWithIdOnTerm($courseId, $tutorId, $termId))
			{
				throw new Exception("Tutor already teaches a course with id $courseId");
			}
		}
		Term::validateId($termId);

		Tutor_has_course_has_schedule::addCourses($tutorId, $teachingCoursesId, $termId);

	}

	/**
	 * @param $newCourseId
	 * @param $tutorId
	 * @param $termId
	 * @return bool
	 * @internal param $db
	 */
	public static function teachesCourseWithIdOnTerm($newCourseId, $tutorId, $termId)
	{
		$query = "SELECT `" . self::DB_COLUMN_COURSE_ID . "`
			FROM `" . App::getDbName() . "`.`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "`
			WHERE `" . self::DB_COLUMN_COURSE_ID . "`  = :courseId
			AND `" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM_TUTOR_USER_ID . "`  = :tutorId
			AND `" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM_TERM_ID . "`  = :term_id";

		$dbConnection = DatabaseManager::getConnection();
		$query = $dbConnection->prepare($query);
		$query->bindParam(':tutorId', $tutorId, PDO::PARAM_INT);
		$query->bindParam(':courseId', $newCourseId, PDO::PARAM_INT);
		$query->bindParam(':term_id', $termId, PDO::PARAM_INT);

		$query->execute();
		if ($query->fetchColumn() === false)
		{
			return false;
		} else
		{
			return true;
		}
	}

	public static function hasAppointmentWithId($tutorId, $appointmentId)
	{
		self::validateId($tutorId);
		Appointment::validateId($appointmentId);

		return TutorFetcher::hasAppointmentWithId($tutorId, $appointmentId);
	}

	public static function validateId($id)
	{
		if (!preg_match('/^[0-9]+$/', $id) || !TutorFetcher::existsUserId($id))
		{
			throw new Exception("Data tempering detected.
			<br/>You&#39;re trying to hack this app.<br/>Developers are being notified about this.<br/>Expect Us.");
		}
	}

	/**
	 * Returns all information of a user given his email.
	 * @param $id
	 * @throws Exception
	 * @internal param $db
	 */
	public static function  getSingle($id)
	{
		self::validateId($id);

		return TutorFetcher::retrieveSingle($id);
	}

	public static function retrieveCoursesNotTeaching($id)
	{
		$query =
			"SELECT `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_CODE . "` AS 'code', `" . CourseFetcher::DB_TABLE . "`.`" .
			CourseFetcher::DB_COLUMN_NAME . "` AS 'name',  `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_ID . "`
		FROM  `" . App::getDbName() . "`.`" . CourseFetcher::DB_TABLE . "`
		WHERE NOT EXISTS (
			SELECT `" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "`.`" . self::DB_COLUMN_COURSE_ID . "`
			FROM  `" . App::getDbName() . "`.`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "`
			WHERE `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_ID . "` = `" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "`.`" . self::DB_COLUMN_COURSE_ID . "`
			AND
			`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "`.`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM_TUTOR_USER_ID . "` = :tutorUserId
		)";

		try
		{
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':tutorUserId', $id, PDO::PARAM_INT);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);

		} catch (PDOException $e)
		{
			throw new Exception("Could not retrieve courses not teaching by this user from database.");
		}
	}

	public static function retrieveTeachingCourses($id)
	{


		$query = "SELECT `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_CODE . "` , `" . CourseFetcher::DB_TABLE . "`.`" .
			CourseFetcher::DB_COLUMN_NAME . "` , `" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "`.`" . self::DB_COLUMN_COURSE_ID . "`
					FROM `" . App::getDbName() . "`.`" . CourseFetcher::DB_TABLE . "`, `" . App::getDbName() . "`.`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "`
					WHERE `" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "`.`" . self::DB_COLUMN_COURSE_ID . "` =
					`" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_ID . "` AND
					`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "`.`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM_TUTOR_USER_ID . "` = :tutorId;";

		try
		{
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':tutorId', $id, PDO::PARAM_INT);

			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);

		} catch (PDOException $e)
		{
			throw new Exception("Could not retrieve teaching courses data from database.");
		}

	}

	public static function updateTeachingCourse($id, $newCourseId, $oldCourseId, $termId)
	{

		if (!preg_match('/^[0-9]+$/', $newCourseId) || !preg_match('/^[0-9]+$/', $oldCourseId) || strcmp($newCourseId, $oldCourseId) === 0)
		{
			throw new Exception("Data has been tempered. Aborting process.");
		}

		Term::validateId($termId);

		if (self::teachesCourseWithIdOnTerm($newCourseId, $id, $termId))
		{
			throw new Exception("Data has been tempered. Aborting process.");
		}

		try
		{
			$query = "UPDATE `" . App::getDbName() . "`.`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "` SET `course_id`= :newCourseId WHERE `tutor_user_id`= :tutorId and`course_id`= :oldCourseId";

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':newCourseId', $newCourseId, PDO::PARAM_INT);
			$query->bindParam(':tutorId', $id, PDO::PARAM_INT);
			$query->bindParam(':oldCourseId', $oldCourseId, PDO::PARAM_INT);
			$query->execute();
		} catch (Exception $e)
		{
			throw new Exception("Could not replace teaching courses data into database.");
		}
	}

	public static function insertMajor($id, $majorId)
	{
		Major::validateId($majorId);
		TutorFetcher::insertMajor($id, $majorId);
	}

	public static function replaceMajorId($id, $newMajorId, $oldMajorId)
	{
		// no changes made. no need to do any work.
		if (strcmp($newMajorId, $oldMajorId) === 0)
		{
			return false;
		}

		Tutor::validateId($id);
		Major::validateId($newMajorId);
		Major::validateId($oldMajorId);

		TutorFetcher::replaceMajorId($id, $newMajorId);

		return true;
	}

	/**
	 * @return mixed
	 */
	public function getTeachingCourses()
	{
		return $this->teachingCourses;
	}

	/**
	 * @param mixed $teachingCourses
	 */
	public function setTeachingCourses($teachingCourses)
	{
		$this->teachingCourses = $teachingCourses;
	}

	/**
	 * @return mixed
	 */
	public function getMajorId()
	{
		return $this->majorId;
	}

	/**
	 * @param mixed $major
	 */
	public function setMajorId($major)
	{
		$this->majorId = $major;
	}

	public function isTutor()
	{
		return true;
	}

	public function deleteTeachingCourse($courseId)
	{
		if (!preg_match('/^[0-9]+$/', $courseId))
		{
			throw new Exception("Data tempering detected.
			<br/>You&#39;re trying to hack this app.<br/>Developers are being notified about this.<br/>Expect Us.");
		}
		$tutorId = $this->getId();

		try
		{

			$query = "DELETE FROM `" . App::getDbName() . "`.`" . self::DB_TABLE_TUTOR_HAS_COURSE_HAS_TERM . "` WHERE `tutor_user_id`=:id AND`course_id`=:courseId;";

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':id', $tutorId, PDO::PARAM_INT);
			$query->bindParam(':courseId', $courseId, PDO::PARAM_INT);
			$query->execute();

			return true;
		} catch (Exception $e)
		{
			throw new Exception("Could not delete course from database.");
		}
	}
}