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

	public static function insert($db, $dateStart, $dateEnd, $courseId, $studentsIds, $tutorId, $instructorsIds, $termId) {
		date_default_timezone_set('Europe/Athens');
		$dateStart = $dateStart->format(Dates::DATE_FORMAT_IN);
		$dateEnd = $dateEnd->format(Dates::DATE_FORMAT_IN);

		try {
			$queryInsertUser = "INSERT INTO `" . DB_NAME . "`.`" . self::DB_TABLE . "` (`" . self::DB_COLUMN_START_TIME .
				"`,			`" . self::DB_COLUMN_END_TIME . "`, `" . self::DB_COLUMN_COURSE_ID . "`, `" .
				self::DB_COLUMN_TUTOR_USER_ID . "`, `" . self::DB_COLUMN_TERM_ID . "`)
				VALUES(
					:start_time,
					:end_time,
					:course_id,
					:tutor_user_id,
					:term_id
				)";

			$db->getConnection()->beginTransaction();

			$queryInsertUser = $db->getConnection()->prepare($queryInsertUser);
			$queryInsertUser->bindParam(':start_time', $dateStart, PDO::PARAM_STR);
			$queryInsertUser->bindParam(':end_time', $dateEnd, PDO::PARAM_STR);
			$queryInsertUser->bindParam(':course_id', $courseId, PDO::PARAM_STR);
			$queryInsertUser->bindParam(':tutor_user_id', $tutorId, PDO::PARAM_STR);
			$queryInsertUser->bindParam(':term_id', $termId, PDO::PARAM_STR);

			$queryInsertUser->execute();

			// last inserted if of THIS connection
			$appointmentId = $db->getConnection()->lastInsertId();


			for ($i = 0; $i < sizeof($studentsIds); $i++) {
				Appointment_has_student_has_report_has_instructor::insert($db, $appointmentId, $studentsIds[$i], $instructorsIds[$i]);
			}

			$db->getConnection()->commit();
			return $appointmentId;
		} catch (Exception $e) {
			$db->getConnection()->rollback();
			throw new Exception("Could not insert user into database." . $e->getMessage());
		}

	}

	public static function retrieveAll($db) {
		$query =
			"SELECT `" . self::DB_COLUMN_ID . "` , `" . self::DB_COLUMN_START_TIME . "` , `" . self::DB_COLUMN_END_TIME . "`,
			 `" . self::DB_COLUMN_COURSE_ID . "`,  `" . self::DB_COLUMN_TUTOR_USER_ID . "`,  `" .
			self::DB_COLUMN_TUTOR_USER_ID . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "` order by `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_START_TIME . "` DESC";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve terms data from database.");
		}
	}

} 