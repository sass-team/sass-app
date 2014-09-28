<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/17/2014
 * Time: 6:28 AM
 */
class ReportFetcher
{
	const DB_TABLE = "report";
	const DB_COLUMN_ID = "id";
	const DB_COLUMN_STUDENT_ID = "student_id";
	const DB_COLUMN_INSTRUCTOR_ID = "instructor_id";

	const DB_COLUMN_PROJECT_TOPIC_OTHER = "project_topic_other";
	const DB_COLUMN_STUDENT_CONCERNS = "students_concerns";
	const DB_COLUMN_RELEVANT_FEEDBACK_OR_GUIDELINES = "relevant_feedback_or_guidelines";
	const DB_COLUMN_ADDITIONAL_COMMENTS = "additional_comments";
	const DB_COLUMN_LABEL_MESSAGE = "label_message";
	const DB_COLUMN_LABEL_COLOR = "label_color";

	public static function retrieveAllAllWithAppointmentId($db, $appointmentId) {
		$query =
			"SELECT `" . StudentFetcher::DB_TABLE . "`.`" . StudentFetcher::DB_COLUMN_FIRST_NAME . "` , `" .
			StudentFetcher::DB_TABLE . "`.`" . StudentFetcher::DB_COLUMN_LAST_NAME . "` ,`" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_ID . "` , `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_STUDENT_ID . "` AS
			 " . StudentFetcher::DB_TABLE . "_" . StudentFetcher::DB_COLUMN_ID . ", `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_INSTRUCTOR_ID . "` AS " . InstructorFetcher::DB_TABLE . "_" . InstructorFetcher::DB_COLUMN_ID .
			",  `" . self::DB_COLUMN_PROJECT_TOPIC_OTHER . "`, `" . self::DB_COLUMN_STUDENT_CONCERNS . "`,  `" .
			self::DB_COLUMN_RELEVANT_FEEDBACK_OR_GUIDELINES . "`, `" . self::DB_COLUMN_ADDITIONAL_COMMENTS . "`, `" .
			self::DB_COLUMN_LABEL_MESSAGE . "` , `" . self::DB_COLUMN_LABEL_COLOR . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			INNER JOIN  `" . DB_NAME . "`.`" . AppointmentHasStudentFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`  = `" .
			AppointmentHasStudentFetcher::DB_TABLE . "`.`" . AppointmentHasStudentFetcher::DB_COLUMN_REPORT_ID . "`
			INNER JOIN  `" . DB_NAME . "`.`" . StudentFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_STUDENT_ID . "`  = `" .
			StudentFetcher::DB_TABLE . "`.`" . StudentFetcher::DB_COLUMN_ID . "`
			WHERE `" . AppointmentHasStudentFetcher::DB_TABLE . "`.`" .
			AppointmentHasStudentFetcher::DB_COLUMN_APPOINTMENT_ID . "` = :appointment_id";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':appointment_id', $appointmentId, PDO::PARAM_INT);

			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database." . $e->getMessage());
		}
	}

	public static function insert($db, $studentId, $appointmentId, $instructorId) {

		try {
			$query = "INSERT INTO `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			(`" . self::DB_COLUMN_STUDENT_ID . "`,	`" . self::DB_COLUMN_INSTRUCTOR_ID . "`)
				VALUES
				(
					:student_id,
					:instructor_id
				)";

			$db->getConnection()->beginTransaction();


			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':student_id', $studentId, PDO::PARAM_INT);
			$query->bindParam(':instructor_id', $instructorId, PDO::PARAM_INT);


			$query->execute();
			// last inserted if of THIS connection
			$reportId = $db->getConnection()->lastInsertId();

			AppointmentHasStudentFetcher::update($db, $appointmentId, $reportId);

			$db->getConnection()->commit();
			return $reportId;
		} catch (Exception $e) {
			$db->getConnection()->rollback();
			throw new Exception("Could not insert data into database." . $e->getMessage());
		}

	}


	public static function existsId($db, $id) {
		try {
			$sql = "SELECT COUNT(" . self::DB_COLUMN_ID . ")
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_COLUMN_ID . "` = :id";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if data exists on database.");
		}

		return true;
	}

	public static function retrieveAll($db) {
		date_default_timezone_set('Europe/Athens');

		$query =
			"SELECT `" . self::DB_COLUMN_STUDENT_ID . "`, `" . self::DB_COLUMN_INSTRUCTOR_ID . "`,
			`" . self::DB_COLUMN_STUDENT_CONCERNS . "`			, `" . self::DB_COLUMN_RELEVANT_FEEDBACK_OR_GUIDELINES . "`
			, `" . self::DB_COLUMN_ADDITIONAL_COMMENTS . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			ORDER BY `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "` DESC";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve courses data from database.");
		}
	}


	public static function retrieveSingle($db, $id) {
		$query = "SELECT `" . self::DB_COLUMN_ID . "`, `" . self::DB_COLUMN_INSTRUCTOR_ID . "`, `" .
			self::DB_COLUMN_STUDENT_ID . "`, `" . self::DB_COLUMN_STUDENT_CONCERNS . "`, `" .
			self::DB_COLUMN_PROJECT_TOPIC_OTHER . "`, `" . self::DB_COLUMN_RELEVANT_FEEDBACK_OR_GUIDELINES . ",`" .
			self::DB_COLUMN_ADDITIONAL_COMMENTS . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_COLUMN_ID . "`=:id";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);

			$query->execute();
			return $query->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database.");
		} // end catch
	}

}