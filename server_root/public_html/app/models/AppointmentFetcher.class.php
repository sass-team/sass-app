<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/16/2014
 * Time: 8:02 PM
 */
class AppointmentFetcher
{
	const DB_TABLE = "appointment";
	const DB_COLUMN_ID = "id";
	const DB_COLUMN_START_TIME = "start_time";
	const DB_COLUMN_END_TIME = "end_time";
	const DB_COLUMN_COURSE_ID = "course_id";
	const DB_COLUMN_TUTOR_USER_ID = "tutor_user_id";
	const DB_COLUMN_TERM_ID = "term_id";
	const DB_COLUMN_LABEL_MESSAGE = "label_message";
	const DB_COLUMN_LABEL_COLOR = "label_color";

	public static function updateTerm($appointmentId, $newTermId) {

		$query = "UPDATE `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
					SET `" . self::DB_COLUMN_TERM_ID . "`= :term_id
					WHERE `" . self::DB_COLUMN_ID . "` = :appointment_id";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);

			$query->bindParam(':appointment_id', $appointmentId, PDO::PARAM_INT);
			$query->bindParam(':term_id', $newTermId, PDO::PARAM_INT);

			$query->execute();
			return true;
		} catch (Exception $e) {
			throw new Exception("Could not update data.");
		}
		return false;
	}

	public static function updateDuration($appointmentId, $newStartTime, $newEndTime) {
		$newStartTime = $newStartTime->format(Dates::DATE_FORMAT_IN);
		$newEndTime = $newEndTime->format(Dates::DATE_FORMAT_IN);

		$query = "UPDATE `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
					SET `" . self::DB_COLUMN_START_TIME . "`= :start_time,
					`" . self::DB_COLUMN_END_TIME . "`= :end_time
					WHERE `" . self::DB_COLUMN_ID . "` = :appointment_id";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':appointment_id', $appointmentId, PDO::PARAM_INT);
			$query->bindParam(':start_time', $newStartTime, PDO::PARAM_STR);
			$query->bindParam(':end_time', $newEndTime, PDO::PARAM_STR);

			$query->execute();
			return true;
		} catch (Exception $e) {
			throw new Exception("Could not update data.");
		}
		return false;
	}

	public static function updateTutor($appointmentId, $newTutorId) {
		$query = "UPDATE `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
					SET `" . self::DB_COLUMN_TUTOR_USER_ID . "`= :tutor_id
					WHERE `" . self::DB_COLUMN_ID . "` = :appointment_id";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':appointment_id', $appointmentId, PDO::PARAM_INT);
			$query->bindParam(':tutor_id', $newTutorId, PDO::PARAM_INT);
			$query->execute();
			return true;
		} catch (Exception $e) {
			throw new Exception("Could not update data.");
		}
		return false;
	}

	public static function insert($dateStart, $dateEnd, $courseId, $studentsIds, $tutorId, $instructorsIds, $termId) {
		date_default_timezone_set('Europe/Athens');
		$dateStart = $dateStart->format(Dates::DATE_FORMAT_IN);
		$dateEnd = $dateEnd->format(Dates::DATE_FORMAT_IN);

		try {
			$queryInsertUser = "INSERT INTO `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "` (`" . self::DB_COLUMN_START_TIME .
				"`,			`" . self::DB_COLUMN_END_TIME . "`, `" . self::DB_COLUMN_COURSE_ID . "`, `" .
				self::DB_COLUMN_TUTOR_USER_ID . "`, `" . self::DB_COLUMN_TERM_ID . "`)
				VALUES(
					:start_time,
					:end_time,
					:course_id,
					:tutor_user_id,
					:term_id
				)";

			$dbConnection = DatabaseManager::getConnection();
			$dbConnection->beginTransaction();

			$queryInsertUser = $dbConnection->prepare($queryInsertUser);
			$queryInsertUser->bindParam(':start_time', $dateStart, PDO::PARAM_STR);
			$queryInsertUser->bindParam(':end_time', $dateEnd, PDO::PARAM_STR);
			$queryInsertUser->bindParam(':course_id', $courseId, PDO::PARAM_STR);
			$queryInsertUser->bindParam(':tutor_user_id', $tutorId, PDO::PARAM_STR);
			$queryInsertUser->bindParam(':term_id', $termId, PDO::PARAM_STR);

			$queryInsertUser->execute();

			// last inserted if of THIS connection
			$appointmentId = $dbConnection->lastInsertId();


			for ($i = 0; $i < sizeof($studentsIds); $i++) {
				AppointmentHasStudentFetcher::insert($appointmentId, $studentsIds[$i], $instructorsIds[$i]);
			}

			$dbConnection->commit();
			return $appointmentId;
		} catch (Exception $e) {
			$dbConnection->rollback();
			throw new Exception("Could not insert data into database." );
		}

	}

	public static function updateCourse($appointmentId, $newCourseId) {
		$query = "UPDATE `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
					SET `" . self::DB_COLUMN_COURSE_ID . "`= :course_id
					WHERE `" . self::DB_COLUMN_ID . "` = :appointment_id";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':appointment_id', $appointmentId, PDO::PARAM_INT);
			$query->bindParam(':course_id', $newCourseId, PDO::PARAM_INT);
			$query->execute();
			return true;
		} catch (Exception $e) {
			throw new Exception("Could not update data.");
		}
		return false;
	}

	public static function existsId($id) {
		try {
			$query = "SELECT COUNT(" . self::DB_COLUMN_ID . ") FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_ID . "` = :id";

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check data already exists on database.");
		}

		return true;
	}

	public static function existsTutorsAppointmentsBetween($tutorId, $termId, $startDate, $endDate, $existingAppointmentId = false) {
		date_default_timezone_set('Europe/Athens');
		$startDate = $startDate->format(Dates::DATE_FORMAT_IN);
		$endDate = $endDate->format(Dates::DATE_FORMAT_IN);

		try {
			$existingAppointment = ($existingAppointmentId !== false) ? "AND `" . self::DB_COLUMN_ID . "` <> :appointment_id" : "";

			$query =
			$query =
				"SELECT COUNT(`" . self::DB_COLUMN_ID . "`)
				FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
				WHERE `" . self::DB_COLUMN_TUTOR_USER_ID . "` = :tutor_user_id
				" . $existingAppointment . "
				AND
				(
					(:start_date >= `" . self::DB_COLUMN_START_TIME . "` AND :start_date < `" . self::DB_COLUMN_END_TIME . "`)
					OR
					(:end_date > `" . self::DB_COLUMN_START_TIME . "` AND :end_date <= `" . self::DB_COLUMN_END_TIME . "`)
					OR
					(`" . self::DB_COLUMN_START_TIME . "` >= :start_date AND `" . self::DB_COLUMN_START_TIME . "` < :end_date)
					OR
					(`" . self::DB_COLUMN_END_TIME . "` > :start_date AND `" . self::DB_COLUMN_END_TIME . "` < :end_date)

				)
				AND `" . self::DB_COLUMN_TERM_ID . "`=:term_id
				AND
				(
				`" . self::DB_COLUMN_LABEL_MESSAGE . "` = :message_pending
				OR
				`" . self::DB_COLUMN_LABEL_MESSAGE . "` = :message_complete
				)";

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);

			$query->bindParam(':tutor_user_id', $tutorId, PDO::PARAM_INT);
			$query->bindParam(':term_id', $termId, PDO::PARAM_INT);

			$messagePending = Appointment::LABEL_MESSAGE_PENDING;
			$messageComplete = Appointment::LABEL_MESSAGE_COMPLETE;

			$query->bindParam(':message_pending', $messagePending, PDO::PARAM_INT);
			$query->bindParam(':message_complete', $messageComplete, PDO::PARAM_INT);

			if ($existingAppointmentId !== false) $query->bindParam(':appointment_id', $existingAppointmentId, PDO::PARAM_INT);

//			throw new Exception("Could not check conflicts with other appointments." . $startDate);

			$query->bindParam(':start_date', $startDate, PDO::PARAM_STR);
			$query->bindParam(':end_date', $endDate, PDO::PARAM_STR);

			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check conflicts with other appointments.");
		}

		return true;
	}


	public static function belongsToTutor($id, $tutorId) {
		try {
			$query = "SELECT COUNT(" . self::DB_COLUMN_ID . ")
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
				WHERE `" . self::DB_COLUMN_ID . "` = :id
				AND `" . self::DB_COLUMN_TUTOR_USER_ID . "` = :tutor_id";

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->bindParam(':tutor_id', $tutorId, PDO::PARAM_INT);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check data already exists on database.");
		}

		return true;
	}

	public static function retrieveSingle($id) {
		$query = "SELECT `" . self::DB_COLUMN_START_TIME . "`, `" . self::DB_COLUMN_END_TIME . "`, `" .
			self::DB_COLUMN_COURSE_ID . "`, `" . self::DB_COLUMN_TUTOR_USER_ID . "`,  `" . self::DB_COLUMN_TUTOR_USER_ID .
			"`,  `" . self::DB_COLUMN_ID . "`,  `" . self::DB_COLUMN_TERM_ID . "`
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_COLUMN_ID . "`=:id";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);

			$query->execute();
			return $query->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Something terrible happened . Could not retrieve data from database .: ");
		} // end catch
	}

	public static function retrieveReport($id) {
		$query = "SELECT `" . self::DB_COLUMN_START_TIME . "`, `" . self::DB_COLUMN_END_TIME . "`, `" .
			self::DB_COLUMN_COURSE_ID . "`, `" . self::DB_COLUMN_TUTOR_USER_ID . "`,  `" . self::DB_COLUMN_TUTOR_USER_ID .
			"`,  `" . self::DB_COLUMN_ID . "`,  `" . self::DB_COLUMN_TERM_ID . "`
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_COLUMN_ID . "`=:id";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);

			$query->execute();
			return $query->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Something terrible happened . Could not retrieve data from database .: ");
		} // end catch
	}

	public static function retrieveAll() {
		$query =
			"SELECT `" . self::DB_COLUMN_ID . "` , `" . self::DB_COLUMN_START_TIME . "` , `" . self::DB_COLUMN_END_TIME . "`,
			 `" . self::DB_COLUMN_COURSE_ID . "`,  `" . self::DB_COLUMN_TUTOR_USER_ID . "`,  `" .
			self::DB_COLUMN_TUTOR_USER_ID . "`
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "` ORDER BY `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_START_TIME . "` DESC";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database.");
		}
	}

	public static function retrieveAllOfCurrTerms() {
		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "` AS " . self::DB_TABLE . "_" . self::DB_COLUMN_ID . ", 
			`" . self::DB_COLUMN_START_TIME . "` , `" . self::DB_COLUMN_END_TIME . "`, `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_LABEL_MESSAGE . "`, `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_LABEL_COLOR . "`,
			`" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_CODE . "`, 
			`" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_NAME . "`, 
			`" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_FIRST_NAME . "`, 
			`" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_LAST_NAME . "`, 
			`" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_NAME . "` AS " . TermFetcher::DB_TABLE . "_" . TermFetcher::DB_COLUMN_NAME . " 
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			INNER JOIN `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . CourseFetcher::DB_TABLE . "`
				ON `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_ID . "` = 
					`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_COURSE_ID . "` 
			INNER JOIN `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . UserFetcher::DB_TABLE . "`
				ON `" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "` = 
					`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "` 
			INNER JOIN `" . TermFetcher::DB_TABLE . "` 
				ON `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "` = 
					`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`
			WHERE :now BETWEEN `" . TermFetcher::DB_COLUMN_START_DATE . "` AND `" . TermFetcher::DB_COLUMN_END_DATE . "`";

		try {
			date_default_timezone_set('Europe/Athens');
			$now = new DateTime();
			$now = $now->format(Dates::DATE_FORMAT_IN);

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':now', $now, PDO::PARAM_STR);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database." );
		}
	}

	public static function retrieveAllOfCurrTermsByTutor($tutorId) {
		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "` AS " . self::DB_TABLE . "_" .
			self::DB_COLUMN_ID . ", `" . self::DB_COLUMN_START_TIME . "` , `" . self::DB_COLUMN_END_TIME . "`,
			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_LABEL_MESSAGE . "`, `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_LABEL_COLOR . "`, `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_CODE . "`,
			 `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_NAME . "`,	`" . UserFetcher::DB_TABLE . "`.`" .
			UserFetcher::DB_COLUMN_FIRST_NAME . "`, `" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_LAST_NAME
			. "`, `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_NAME . "` AS " . TermFetcher::DB_TABLE . "_"
			. TermFetcher::DB_COLUMN_NAME . "
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			INNER JOIN `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . CourseFetcher::DB_TABLE . "`
				ON `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_ID . "` = 
					`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_COURSE_ID . "` 
			INNER JOIN `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . UserFetcher::DB_TABLE . "`
				ON `" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "` = 
					`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "` 
			INNER JOIN `" . TermFetcher::DB_TABLE . "` 
				ON `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "` = 
					`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`
			WHERE `" . self::DB_COLUMN_TUTOR_USER_ID . "` = :tutor_id AND
			:now BETWEEN `" . TermFetcher::DB_COLUMN_START_DATE . "` AND `" . TermFetcher::DB_COLUMN_END_DATE . "`";

		try {
			date_default_timezone_set('Europe/Athens');
			$now = new DateTime();
			$now = $now->format(Dates::DATE_FORMAT_IN);

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':now', $now, PDO::PARAM_STR);

			$query->bindParam(':tutor_id', $tutorId, PDO::PARAM_INT);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database." );
		}
	}

	public static function retrieveTutors($termId) {
		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "` , `" . self::DB_COLUMN_START_TIME . "` , `" .
			self::DB_COLUMN_END_TIME . "`, `" . self::DB_COLUMN_COURSE_ID . "`,  `" . self::DB_COLUMN_TUTOR_USER_ID . "`,
			`" . self::DB_COLUMN_TUTOR_USER_ID . "`, `" . UserFetcher::DB_COLUMN_FIRST_NAME . "` , `" .
			UserFetcher::DB_COLUMN_LAST_NAME . "`, `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_CODE . "`,
			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_LABEL_COLOR . "`
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			INNER JOIN  `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . UserFetcher::DB_TABLE . "`
			ON `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`  = `" .
			UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`
			INNER JOIN  `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . CourseFetcher::DB_TABLE . "`
			ON `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_COURSE_ID . "`  = `" .
			CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_ID . "`
			INNER JOIN  `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . TermFetcher::DB_TABLE . "`
			ON `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`  = `" .
			TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "`
			WHERE `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "` = :term_id
			ORDER BY `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_START_TIME . "` DESC";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':term_id', $termId, PDO::PARAM_INT);

			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database." );
		}
	}

	public static function updateLabel($appointmentId, $labelMessage, $labelColor) {
		$query = "UPDATE `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
					SET `" . self::DB_COLUMN_LABEL_MESSAGE . "`= :label_message, `" . self::DB_COLUMN_LABEL_COLOR . "` =
					:label_color
					WHERE `" . self::DB_COLUMN_ID . "` = :id";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':id', $appointmentId, PDO::PARAM_INT);
			$query->bindParam(':label_message', $labelMessage, PDO::PARAM_STR);
			$query->bindParam(':label_color', $labelColor, PDO::PARAM_STR);

			$query->execute();
			return true;
		} catch (Exception $e) {
			throw new Exception("Could not update data.");
		}
		return false;
	}

	public static function retrieveBetweenDates($startWeek, $endWeek) {
		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "` , `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_START_TIME . "` , `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_END_TIME . "`,
			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`,
			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_COURSE_ID . "`, `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_TUTOR_USER_ID . "`,  `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`,
			  `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_LABEL_MESSAGE . "`
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			WHERE
			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_START_TIME . "` BETWEEN :start_week AND :end_week";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':start_week', $startWeek, PDO::PARAM_STR);
			$query->bindParam(':end_week', $endWeek, PDO::PARAM_STR);

			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);

		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database." );
		}
	}

	public static function retrieveTutorsBetweenDates($tutorId, $startWeek, $endWeek) {
		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "` , `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_START_TIME . "` , `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_END_TIME . "`,
			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`,
			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_COURSE_ID . "`, `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_TUTOR_USER_ID . "`,  `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`,
			  `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_LABEL_MESSAGE . "`
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			WHERE
			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_START_TIME . "` BETWEEN :start_week AND :end_week
			AND `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "` = :tutor_id";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':start_week', $startWeek, PDO::PARAM_STR);
			$query->bindParam(':end_week', $endWeek, PDO::PARAM_STR);
			$query->bindParam(':tutor_id', $tutorId, PDO::PARAM_INT);

			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);

		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database." );
		}
	}


	public static function retrieveAllForSingleTutor($tutorId, $termId) {
		$query =
			"SELECT `" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_EMAIL . "`, `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_ID . "` , `" . self::DB_COLUMN_START_TIME . "` , `" . self::DB_COLUMN_END_TIME . "`, `" .
			self::DB_COLUMN_COURSE_ID . "`,  `" . self::DB_COLUMN_TUTOR_USER_ID . "`, `" . self::DB_COLUMN_TUTOR_USER_ID .
			"`, `" . UserFetcher::DB_COLUMN_FIRST_NAME . "` , `" . UserFetcher::DB_COLUMN_LAST_NAME . "`, `" .
			CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_CODE . "`, `" . CourseFetcher::DB_TABLE . "`.`" .
			CourseFetcher::DB_COLUMN_NAME . "`, `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_LABEL_COLOR . "`
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			INNER JOIN  `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . UserFetcher::DB_TABLE . "`
			ON `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`  = `" .
			UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`
			INNER JOIN  `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . CourseFetcher::DB_TABLE . "`
			ON `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_COURSE_ID . "`  = `" .
			CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_ID . "`
			INNER JOIN  `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . TermFetcher::DB_TABLE . "`
			ON `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`  = `" .
			TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "`
			WHERE `" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "` = :tutor_id
			AND `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "` = :term_id
			ORDER BY `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_START_TIME . "` DESC";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':tutor_id', $tutorId, PDO::PARAM_INT);
			$query->bindParam(':term_id', $termId, PDO::PARAM_INT);

			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database." );
		}
	}

	/**
	 * Appointments are considered completed if 30 minutes have from the passing of it's start time.
	 * @param $db
	 * @throws Exception
	 */
	public static function  retrieveCmpltWithoutRptsOnCurTerms() {
		$query =
			"SELECT DISTINCT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "` , `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_START_TIME . "` , `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_END_TIME . "`,
			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`,
			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_COURSE_ID . "`, `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_TUTOR_USER_ID . "`,  `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			LEFT JOIN  `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . AppointmentHasStudentFetcher::DB_TABLE . "`
			ON `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`  = `" .
			AppointmentHasStudentFetcher::DB_TABLE . "`.`" . AppointmentHasStudentFetcher::DB_COLUMN_APPOINTMENT_ID . "`
			LEFT JOIN  `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . TermFetcher::DB_TABLE . "`
			ON `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`  = `" .
			TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "`
			WHERE TIME_TO_SEC(TIMEDIFF(:now,  `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_START_TIME . "`))/60 >= 30
			AND `" . AppointmentFetcher::DB_TABLE . "`.`" .
			self::DB_COLUMN_LABEL_MESSAGE . "` = :pending
			AND :now BETWEEN `" . TermFetcher::DB_COLUMN_START_DATE . "` AND `" . TermFetcher::DB_COLUMN_END_DATE . "`
			ORDER BY `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_START_TIME . "` DESC";


		try {
			date_default_timezone_set('Europe/Athens');
			$now = new DateTime();
			$now = $now->format(Dates::DATE_FORMAT_IN);

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':now', $now, PDO::PARAM_STR);

			$pendingLabel = Appointment::LABEL_MESSAGE_PENDING;
			$query->bindParam(':pending', $pendingLabel, PDO::PARAM_STR);


			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database." );
		}
	}

} 