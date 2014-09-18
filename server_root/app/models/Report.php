<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/17/2014
 * Time: 6:28 AM
 */
class Report
{
	const DB_TABLE = "report";
	const DB_COLUMN_ID = "id";
	const DB_COLUMN_APPOINTMENT_ID = "appointment_id";
	const DB_COLUMN_STUDENT_ID = "student_id";
	const DB_COLUMN_INSTRUCTOR_ID = "instructor_id";
	const DB_COLUMN_REPORT_ID = "report_id";

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
			$db->getConnection()->rollback();
			throw new Exception("Could not insert user into database." . $e->getMessage());
		}
	}
} 