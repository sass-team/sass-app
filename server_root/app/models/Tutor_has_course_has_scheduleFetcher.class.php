<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/14/2014
 * Time: 5:02 AM
 */
class Tutor_has_course_has_scheduleFetcher
{
	const DB_TABLE = "tutor_has_course_has_schedule";
	const DB_COLUMN_TUTOR_USER_ID = "tutor_user_id";
	const DB_COLUMN_COURSE_ID = "course_id";
	const DB_COLUMN_TERM_ID = "term_id";

	public static function insertMany($db, $id, $coursesId, $termId) {
		try {
			foreach ($coursesId as $courseId) {
				$query = "INSERT INTO `" . DB_NAME . "`.`" . self::DB_TABLE .
					"` (`" . self::DB_COLUMN_TUTOR_USER_ID . "`, `" . self::DB_COLUMN_COURSE_ID .
					"`, `" . self::DB_COLUMN_TERM_ID . "`) VALUES(:id, :courseId, :term_id)";
				$query = $db->getConnection()->prepare($query);
				$query->bindParam(':id', $id, PDO::PARAM_INT);
				$query->bindParam(':courseId', $courseId, PDO::PARAM_INT);
				$query->bindParam(':term_id', $termId, PDO::PARAM_INT);
				$query->execute();
			}

			return true;
		} catch (Exception $e) {
			throw new Exception("Could not insert teaching courses data into database." . $e->getMessage());
		}
	}
}