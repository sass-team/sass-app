<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/14/2014
 * Time: 5:02 AM
 */
class Tutor_has_course_has_termFetcher
{
	const DB_TABLE = "tutor_has_course_has_term";
	const DB_COLUMN_TUTOR_USER_ID = "tutor_user_id";
	const DB_COLUMN_COURSE_ID = "course_id";
	const DB_COLUMN_TERM_ID = "term_id";

	public static function insertMany($id, $coursesId, $termId) {
		try {
			foreach ($coursesId as $courseId) {
				$query = "INSERT INTO `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE .
					"` (`" . self::DB_COLUMN_TUTOR_USER_ID . "`, `" . self::DB_COLUMN_COURSE_ID .
					"`, `" . self::DB_COLUMN_TERM_ID . "`) VALUES(:id, :courseId, :term_id)";

				$dbConnection = DatabaseManager::getConnection();
				$query = $dbConnection->prepare($query);

				$query->bindParam(':id', $id, PDO::PARAM_INT);
				$query->bindParam(':courseId', $courseId, PDO::PARAM_INT);
				$query->bindParam(':term_id', $termId, PDO::PARAM_INT);
				$query->execute();
			}

			return true;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not insert teaching courses data into database.");
		}

	}

	public static function retrieveCurrTermAllTeachingCourses() {
		$query = "SELECT `" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_FIRST_NAME . "`,
						 `" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_LAST_NAME . "`,
						 `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_CODE . "`,
						 `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_NAME . "`,
						 `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_NAME . "` AS
						" . TermFetcher::DB_TABLE . "_" . TermFetcher::DB_COLUMN_NAME . "
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			INNER JOIN `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . UserFetcher::DB_TABLE . "`
				ON `" . Tutor_has_course_has_termFetcher::DB_TABLE . "`.`" . Tutor_has_course_has_termFetcher::DB_COLUMN_TUTOR_USER_ID . "` = `" .
			UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`
			INNER JOIN `" . TermFetcher::DB_TABLE . "`
				ON `" . Tutor_has_course_has_termFetcher::DB_TABLE . "`.`" . Tutor_has_course_has_termFetcher::DB_COLUMN_TERM_ID . "` = `" .
			TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "`
			INNER JOIN `" . CourseFetcher::DB_TABLE . "`
				ON `" . Tutor_has_course_has_termFetcher::DB_TABLE . "`.`" . Tutor_has_course_has_termFetcher::DB_COLUMN_COURSE_ID . "` = `" .
			CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_ID . "`
			WHERE (:now BETWEEN `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_START_DATE . "` AND `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_END_DATE . "`)";

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
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not retrieve teaching courses from current terms from database.");
		}
	}

}