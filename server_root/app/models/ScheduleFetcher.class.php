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
	const DB_COLUMN_TUTOR_USER_ID = "tutor_user_id";
	const DB_COLUMN_TERM_ID = "term_id";

	public static function insert($db, $dateStart, $dateEnd, $tutorId, $termId) {
		date_default_timezone_set('Europe/Athens');
		$dateStart = $dateStart->format(Dates::DATE_FORMAT_IN);
		$dateEnd = $dateEnd->format(Dates::DATE_FORMAT_IN);

		try {
			$queryInsertUser = "INSERT INTO `" . DB_NAME . "`.`" . self::DB_TABLE . "` (`" . self::DB_COLUMN_START_TIME .
				"`, `" . self::DB_COLUMN_END_TIME . "`, `" . self::DB_COLUMN_TUTOR_USER_ID . "`, `" .
				self::DB_COLUMN_TERM_ID . "`)
				VALUES(
					:start,
					:end,
					:tutor_user_id,
					:term_id
				)";


			$queryInsertUser = $db->getConnection()->prepare($queryInsertUser);
			$queryInsertUser->bindParam(':start', $dateStart, PDO::PARAM_STR);
			$queryInsertUser->bindParam(':end', $dateEnd, PDO::PARAM_STR);
			$queryInsertUser->bindParam(':tutor_user_id', $tutorId, PDO::PARAM_STR);
			$queryInsertUser->bindParam(':term_id', $termId, PDO::PARAM_STR);

			$queryInsertUser->execute();

			// last inserted if of THIS connection
			return $appointmentId = $db->getConnection()->lastInsertId();
		} catch (Exception $e) {
			throw new Exception("Could not insert data into database." . $e->getMessage());
		}

	}

	public static function retrieveAll($db) {
		$query =
			"SELECT `" . self::DB_COLUMN_START_TIME . "`, `" . self::DB_COLUMN_END_TIME . "`, `" .
			self::DB_COLUMN_TUTOR_USER_ID . "`, `" . self::DB_COLUMN_TERM_ID . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "` order by `" .
			self::DB_TABLE . "`.`" . self::DB_COLUMN_START_TIME . "` DESC";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database.");
		}
	}

	public static function retrieveTutors($db) {
		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`, `" . self::DB_COLUMN_START_TIME . "`, `" .
			self::DB_COLUMN_END_TIME . "`, `" . self::DB_COLUMN_TUTOR_USER_ID . "`, `" . self::DB_COLUMN_TERM_ID . "`,
			`" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_FIRST_NAME . "`, `" . UserFetcher::DB_TABLE . "`.`"
			. UserFetcher::DB_COLUMN_LAST_NAME . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			INNER JOIN  `" . DB_NAME . "`.`" . UserFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`  = `" .
			UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database.");
		}
	}
} 