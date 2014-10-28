<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/19/2014
 * Time: 11:16 AM
 */
class ScheduleFetcher
{

	const DB_TABLE = "work_week_hours";
	const DB_COLUMN_ID = "id";
	const DB_COLUMN_START_TIME = "start";
	const DB_COLUMN_END_TIME = "end";
	const DB_COLUMN_TERM_ID = "term_id";
	const DB_COLUMN_TUTOR_USER_ID = "tutor_user_id";
	const DB_COLUMN_MONDAY = "monday";
	const DB_COLUMN_TUESDAY = "tuesday";
	const DB_COLUMN_WEDNESDAY = "wednesday";
	const DB_COLUMN_THURSDAY = "thursday";
	const DB_COLUMN_FRIDAY = "friday";


	public static function insert($tutorId, $termId, $repeatingDays, $timeStart, $timeEnd) {
		$monday = $tuesday = $wednesday = $thursday = $friday = 0;

		foreach ($repeatingDays as $repeatingDay) {
			switch ($repeatingDay) {
				case '1':
					$monday = 1;
					break;
				case '2':
					$tuesday = 1;
					break;
				case '3':
					$wednesday = 1;
					break;
				case '4':
					$thursday = 1;
					break;
				case '5':
					$friday = 1;
					break;
			}
		}

		try {
			$queryInsertUser = "INSERT INTO `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
            (`" . self::DB_COLUMN_START_TIME . "`, `" . self::DB_COLUMN_END_TIME . "`, `" . self::DB_COLUMN_TERM_ID . "`,
             `" . self::DB_COLUMN_TUTOR_USER_ID . "`, `" . self::DB_COLUMN_MONDAY . "`, `" . self::DB_COLUMN_TUESDAY . "`,
             `" . self::DB_COLUMN_WEDNESDAY . "`,`" . self::DB_COLUMN_THURSDAY . "`,`" . self::DB_COLUMN_FRIDAY . "`)
				VALUES(
					:start,
					:end,
					:term_id,
					:tutor_user_id,
					:monday,
					:tuesday,
					:wednesday,
					:thursday,
					:friday
				)";


			$dbConnection = DatabaseManager::getConnection();
			$queryInsertUser = $dbConnection->prepare($queryInsertUser);
			$queryInsertUser->bindParam(':start', $timeStart, PDO::PARAM_STR);
			$queryInsertUser->bindParam(':end', $timeEnd, PDO::PARAM_STR);
			$queryInsertUser->bindParam(':tutor_user_id', $tutorId, PDO::PARAM_INT);
			$queryInsertUser->bindParam(':term_id', $termId, PDO::PARAM_INT);
			$queryInsertUser->bindParam(':monday', $monday, PDO::PARAM_INT);
			$queryInsertUser->bindParam(':tuesday', $tuesday, PDO::PARAM_INT);
			$queryInsertUser->bindParam(':wednesday', $wednesday, PDO::PARAM_INT);
			$queryInsertUser->bindParam(':thursday', $thursday, PDO::PARAM_INT);
			$queryInsertUser->bindParam(':friday', $friday, PDO::PARAM_INT);

			$queryInsertUser->execute();

			// last inserted if of THIS connection
			return $appointmentId = $dbConnection->lastInsertId();
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not insert data into database.");
		}

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
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not check data already exists on database.");
		}

		return true;
	}

	public static function retrieveAll() {
		$query =
			"SELECT `" . self::DB_COLUMN_START_TIME . "`, `" . self::DB_COLUMN_END_TIME . "`, `" .
			self::DB_COLUMN_TUTOR_USER_ID . "`, `" . self::DB_COLUMN_TERM_ID . "`
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "` order by `" .
			self::DB_TABLE . "`.`" . self::DB_COLUMN_START_TIME . "` DESC";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not retrieve data from database.");
		}
	}

	public static function retrieveTutors() {
		$query =
			"SELECT `" . TermFetcher::DB_COLUMN_NAME . "`, `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`, `" . self::DB_COLUMN_START_TIME . "`, `" .
			self::DB_COLUMN_END_TIME . "`, `" . self::DB_COLUMN_TUTOR_USER_ID . "`, `" . self::DB_COLUMN_TERM_ID . "`,
			`" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_FIRST_NAME . "`, `" . UserFetcher::DB_TABLE . "`.`"
			. UserFetcher::DB_COLUMN_LAST_NAME . "`, `" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`  AS
			" . UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_ID . "
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			INNER JOIN  `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . UserFetcher::DB_TABLE . "`
			ON `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`  = `" .
			UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`
			INNER JOIN  `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . TermFetcher::DB_TABLE . "`
			ON `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`  = `" .
			TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "`
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
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not retrieve data from database.");
		}
	}

	public static function retrieveTutorsOnTerm($termId) {
		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`, `" . self::DB_COLUMN_START_TIME . "`, `" .
			self::DB_COLUMN_END_TIME . "`, `" . self::DB_COLUMN_TUTOR_USER_ID . "`, `" . self::DB_COLUMN_TERM_ID . "`,
			`" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_FIRST_NAME . "`, `" . UserFetcher::DB_TABLE . "`.`"
			. UserFetcher::DB_COLUMN_LAST_NAME . "`, `" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`  AS
			" . UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_ID . ", `" . TermFetcher::DB_TABLE . "`.`" .
			TermFetcher::DB_COLUMN_START_DATE . "` AS " . TermFetcher::DB_TABLE . "_" . TermFetcher::DB_COLUMN_START_DATE
			. ", `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_END_DATE . "`  AS " . TermFetcher::DB_TABLE
			. "_" . TermFetcher::DB_COLUMN_END_DATE . ",
            `" . self::DB_COLUMN_MONDAY . "`, `" .
			self::DB_COLUMN_TUESDAY . "`, `" . self::DB_COLUMN_WEDNESDAY . "`,`" . self::DB_COLUMN_THURSDAY . "`,`" .
			self::DB_COLUMN_FRIDAY . "`
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			INNER JOIN  `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . UserFetcher::DB_TABLE . "`
			ON `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`  = `" .
			UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`
			INNER JOIN  `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . TermFetcher::DB_TABLE . "`
			ON `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`  = `" .
			TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "`
			WHERE `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "` = :term_id";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':term_id', $termId, PDO::PARAM_INT);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not retrieve data from database.");
		}
	}

	public static function retrieveTutorsOnTermOnCourse($courseId, $termId) {
		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`, `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_START_TIME . "`,
			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_END_TIME . "`, `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`,
			 `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`, `" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_FIRST_NAME . "`, `" . UserFetcher::DB_TABLE . "`.`"
			. UserFetcher::DB_COLUMN_LAST_NAME . "`, `" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`  AS
			" . UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_ID . ", `" . TermFetcher::DB_TABLE . "`.`" .
			TermFetcher::DB_COLUMN_START_DATE . "` AS " . TermFetcher::DB_TABLE . "_" . TermFetcher::DB_COLUMN_START_DATE
			. ", `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_END_DATE . "`  AS " . TermFetcher::DB_TABLE
			. "_" . TermFetcher::DB_COLUMN_END_DATE . ",
            `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_MONDAY . "`, `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUESDAY . "`,
            `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_WEDNESDAY . "`,`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_THURSDAY . "`,
            `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_FRIDAY . "`
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			INNER JOIN  `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . UserFetcher::DB_TABLE . "`
			ON `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`  = `" .
			UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`
			INNER JOIN  `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . TermFetcher::DB_TABLE . "`
			ON `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`  = `" .
			TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "`
			INNER JOIN  `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . Tutor_has_course_has_termFetcher::DB_TABLE . "`
			ON `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`  = `" .
			Tutor_has_course_has_termFetcher::DB_TABLE . "`.`" . Tutor_has_course_has_termFetcher::DB_COLUMN_TUTOR_USER_ID . "`
			WHERE `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "` = :term_id
			AND `" . Tutor_has_course_has_termFetcher::DB_TABLE . "`.`" . Tutor_has_course_has_termFetcher::DB_COLUMN_COURSE_ID . "` = :course_id";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':term_id', $termId, PDO::PARAM_INT);
			$query->bindParam(':course_id', $courseId, PDO::PARAM_INT);

			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not retrieve data from database.");
		}
	}

	public static function retrieveTutorsOnCurrentTerms() {
		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`, `" . self::DB_COLUMN_START_TIME . "`,
            `" . self::DB_COLUMN_END_TIME . "`,	`" . self::DB_COLUMN_TUTOR_USER_ID . "`,`" . self::DB_COLUMN_TERM_ID . "`,
			`" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_FIRST_NAME . "`, `" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_LAST_NAME . "`,
			`" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`  AS
			" . UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_ID . ",
			`" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_NAME . "`,
			`" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "`  AS
			" . TermFetcher::DB_TABLE . "_" . TermFetcher::DB_COLUMN_ID . ", `" . self::DB_COLUMN_MONDAY . "`, `" .
			self::DB_COLUMN_TUESDAY . "`, `" . self::DB_COLUMN_WEDNESDAY . "`,`" . self::DB_COLUMN_THURSDAY . "`,`" .
			self::DB_COLUMN_FRIDAY . "`
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			INNER JOIN  `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . UserFetcher::DB_TABLE . "`
			ON `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`  = `" .
			UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`
			INNER JOIN  `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . TermFetcher::DB_TABLE . "`
			ON `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`  = `" .
			TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "`
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
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not retrieve data from database.");
		}
	}


	public static function retrieveSingleTutorOnTerm($tutorId, $termId) {
		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`, `" . self::DB_COLUMN_START_TIME . "`, `" .
			self::DB_COLUMN_END_TIME . "`, `" . self::DB_COLUMN_TUTOR_USER_ID . "`, `" . self::DB_COLUMN_TERM_ID . "`,
			`" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_FIRST_NAME . "`, `" . UserFetcher::DB_TABLE . "`.`"
			. UserFetcher::DB_COLUMN_LAST_NAME . "`, `" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`  AS
			" . UserFetcher::DB_TABLE . "_" . UserFetcher::DB_COLUMN_ID . ", `" . TermFetcher::DB_TABLE . "`.`" .
			TermFetcher::DB_COLUMN_START_DATE . "` AS " . TermFetcher::DB_TABLE . "_" . TermFetcher::DB_COLUMN_START_DATE
			. ", `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_END_DATE . "`  AS " . TermFetcher::DB_TABLE
			. "_" . TermFetcher::DB_COLUMN_END_DATE . ",`" . self::DB_COLUMN_MONDAY . "`, `" . self::DB_COLUMN_TUESDAY .
			"`, `" . self::DB_COLUMN_WEDNESDAY . "`,`" . self::DB_COLUMN_THURSDAY . "`,`" . self::DB_COLUMN_FRIDAY . "`
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			INNER JOIN  `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . UserFetcher::DB_TABLE . "`
			ON `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`  = `" .
			UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`
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
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not retrieve data from database.");
		}
	}

	public static function retrieveWorkingHours($tutorId, $termId) {
		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`, `" . self::DB_COLUMN_START_TIME . "`, `" .
			self::DB_COLUMN_END_TIME . "`, `" . self::DB_COLUMN_TUTOR_USER_ID . "`, `" . self::DB_COLUMN_TERM_ID . "`
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`=:tutor_id
			AND `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`=:term_id";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':tutor_id', $tutorId, PDO::PARAM_INT);
			$query->bindParam(':term_id', $termId, PDO::PARAM_INT);

			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not retrieve data from database.");
		}
	}

	public static function retrieveCurrWorkingHours($tutorId) {
		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_START_TIME . "`,
                    `" . self::DB_COLUMN_END_TIME . "`, 
                    `" . self::DB_COLUMN_TUTOR_USER_ID . "`, 
                    `" . self::DB_COLUMN_TERM_ID . "`, 
                    `" . self::DB_COLUMN_MONDAY . "`, 
                    `" . self::DB_COLUMN_TUESDAY . "`, 
                    `" . self::DB_COLUMN_WEDNESDAY . "`,
                    `" . self::DB_COLUMN_THURSDAY . "`,
                    `" . self::DB_COLUMN_FRIDAY . "`,
                    `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_NAME . "`,
                    `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "`  AS
                    " . TermFetcher::DB_TABLE . "_" . TermFetcher::DB_COLUMN_ID . "
            FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
            INNER JOIN  `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . TermFetcher::DB_TABLE . "`
            ON `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`  = `" .
			TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "`
            WHERE `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`=:tutor_id
            AND :now BETWEEN `" . TermFetcher::DB_COLUMN_START_DATE . "` AND `" . TermFetcher::DB_COLUMN_END_DATE . "`
            ORDER BY `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_NAME . "`";

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
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not retrieve data from database.");
		}
	}

	public static function existsTutorsSchedulesBetween($tutorId, $termId, $startDate, $endDate) {
		date_default_timezone_set('Europe/Athens');
//		$startDate = $startDate->format(Dates::DATE_FORMAT_IN);
//		$endDate = $endDate->format(Dates::DATE_FORMAT_IN);

		$curStartWorkingWeekDay = $startDate->format('w');
		$curEndWorkingWeekDay = $endDate->format('w');
		$startHour = $startDate->format('Hi');
		$endHour = $endDate->format('Hi');

		$startDayColumn = self::getColumnWeekDay($curStartWorkingWeekDay);

		try {
			$query = "SELECT COUNT(`" . self::DB_COLUMN_ID . "`)
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_COLUMN_TUTOR_USER_ID . "` = :tutor_user_id
			AND `$startDayColumn` = 1
			AND `" . self::DB_COLUMN_TERM_ID . "`=:term_id
			AND    :start_time >= EXTRACT(HOUR_MINUTE FROM `" . self::DB_COLUMN_START_TIME . "`)
			AND    :end_time <= EXTRACT(HOUR_MINUTE FROM `" . self::DB_COLUMN_END_TIME . "`)";


			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':tutor_user_id', $tutorId, PDO::PARAM_INT);
			$query->bindParam(':term_id', $termId, PDO::PARAM_INT);
			$query->bindParam(':start_time', $startHour, PDO::PARAM_STR);
			$query->bindParam(':end_time', $endHour, PDO::PARAM_STR);

			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not check conflicts with other appointments.");
		}

		return true;
	}

	/**
	 * @param $curStartWorkingWeekDay
	 * @return string
	 * @throws Exception
	 */
	public static function getColumnWeekDay($curStartWorkingWeekDay) {
		switch ($curStartWorkingWeekDay) {
			case '1':
				$day = self::DB_COLUMN_MONDAY;
				break;
			case '2':
				$day = self::DB_COLUMN_TUESDAY;
				break;
			case '3':
				$day = self::DB_COLUMN_WEDNESDAY;
				break;
			case '4':
				$day = self::DB_COLUMN_THURSDAY;
				break;
			case '5':
				$day = self::DB_COLUMN_FRIDAY;
				break;
			default:
				throw new Exception("Data have been malformed. Aborting.");
		}

		return $day;
	}

	public static function retrieveSingle($id) {
		$query = "SELECT  `" . self::DB_COLUMN_ID . "` ,
						  `" . self::DB_COLUMN_TERM_ID . "`,
						  `" . self::DB_COLUMN_TUTOR_USER_ID . "` ,
						  `" . self::DB_COLUMN_START_TIME . "`,
						  `" . self::DB_COLUMN_END_TIME . "`
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_COLUMN_ID . "`=:id";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);

			$query->execute();
			return $query->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not retrieve data from database .: ");
		} // end catch
	}

	public static function  updateStartingDate($id, $newStartingDate) {
		$query = "UPDATE `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_START_TIME . "`= :newName
					WHERE `id`= :id";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->bindParam(':newName', $newStartingDate, PDO::PARAM_STR);
			$query->execute();

			return true;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Something terrible happened. Could not update starting time.");
		}
	}

	public static function updateSingleColumn($id, $column, $value, $valueType) {
		$query = "UPDATE `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
					SET	`" . $column . "`= :column
					WHERE `id`= :id";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->bindParam(':column', $value, $valueType);
			$query->execute();

			return true;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Something went wrong. Could not update schedules table.");
		}
	}

	public static function idExists($id) {
		try {
			$query = "SELECT COUNT(" . self::DB_COLUMN_ID . ") FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_ID . "` = :id";
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->execute();

			if ($query->fetchColumn() === 0) return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if schedule id already exists on database. <br/> Aborting process.");
		}

		return true;
	}

	public static function delete($id) {
		try {
			$query = "DELETE FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_ID . "` = :id";
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->execute();
			return true;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not delete schedule from database.");
		}
	}

	/**
	 * NEEDS TESTING
	 * @param $dateStart
	 * @param $dateEnd
	 * @param $tutorId
	 * @throws Exception
	 * @internal param $db
	 * @return bool
	 */
	public static function existDatesBetween($dateStart, $dateEnd, $tutorId) {
		date_default_timezone_set('Europe/Athens');
		$dateStart = $dateStart->format(Dates::DATE_FORMAT_IN);
		$dateEnd = $dateEnd->format(Dates::DATE_FORMAT_IN);

		$query = "SELECT COUNT(`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`),`" . CourseFetcher::DB_TABLE . "`
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_COLUMN_TUTOR_USER_ID . "` = :tutor_id
			AND(`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_START_TIME . "`  BETWEEN $dateStart AND $dateEnd)";


		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':tutor_id', $tutorId, PDO::PARAM_INT);

			$query->execute();
			if ($query->fetchColumn() === '0') return false;

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not retrieve teaching courses data from database.");
		}
		return true;
	}
}