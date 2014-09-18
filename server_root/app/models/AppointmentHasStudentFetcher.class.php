<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/18/2014
 * Time: 4:30 PM
 */
class AppointmentHasStudentFetcher
{
	const DB_TABLE = "appointment_has_student";
	const DB_COLUMN_ID = "id";
	const DB_COLUMN_APPOINTMENT_ID = "appointment_id";
	const DB_COLUMN_STUDENT_ID = "student_id";
	const DB_COLUMN_REPORT_ID = "report_id";
	const DB_COLUMN_INSTRUCTOR_ID = "instructor_id";

	public static function insert($db, $appointmentId, $studentId, $instructorId) {
		try {
			$queryInsertUser = "INSERT INTO `" . DB_NAME . "`.`" . self::DB_TABLE . "` (`" . self::DB_COLUMN_APPOINTMENT_ID
				. "`,	`" . self::DB_COLUMN_STUDENT_ID . "`, `" . self::DB_COLUMN_INSTRUCTOR_ID . "`
				)
				VALUES(
					:appointment_id,
					:student_id,
					:instructor_id
				)";


			$queryInsertUser = $db->getConnection()->prepare($queryInsertUser);
			$queryInsertUser->bindParam(':appointment_id', $appointmentId, PDO::PARAM_STR);
			$queryInsertUser->bindParam(':student_id', $studentId, PDO::PARAM_STR);
			$queryInsertUser->bindParam(':instructor_id', $instructorId, PDO::PARAM_STR);

			$queryInsertUser->execute();

		} catch (Exception $e) {
			throw new Exception("Could not insert user into database.");}

	}

	public static function retrieveAll($db) {
		$query =
			"SELECT `" . self::DB_COLUMN_ID . "` , `" . self::DB_COLUMN_APPOINTMENT_ID . "` , `" . self::DB_COLUMN_STUDENT_ID . "`,
			 `" . self::DB_COLUMN_REPORT_ID . "`,  `" . self::DB_COLUMN_INSTRUCTOR_ID . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database.");
		}
	}

	public static function retrieveJoinReport($db) {
		$query =
			"SELECT `" . self::DB_COLUMN_ID . "` , `" . self::DB_COLUMN_APPOINTMENT_ID . "` , `" . self::DB_COLUMN_STUDENT_ID . "`,
			 `" . self::DB_COLUMN_REPORT_ID . "`,  `" . self::DB_COLUMN_INSTRUCTOR_ID . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database.");
		}
	}

} 