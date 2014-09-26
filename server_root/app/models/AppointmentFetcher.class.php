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
				AppointmentHasStudentFetcher::insert($db, $appointmentId, $studentsIds[$i], $instructorsIds[$i]);
			}

			$db->getConnection()->commit();
			return $appointmentId;
		} catch (Exception $e) {
			$db->getConnection()->rollback();
			throw new Exception("Could not insert data into database." . $e->getMessage());
		}

	}

	public static function existsId($db, $id) {
		try {
			$sql = "SELECT COUNT(" . self::DB_COLUMN_ID . ") FROM `" . DB_NAME . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_ID . "` = :id";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check data already exists on database.");
		}

		return true;
	}

	public static function belongsToTutor($db, $id, $tutorId) {
		try {
			$sql = "SELECT COUNT(" . self::DB_COLUMN_ID . ")
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
				WHERE `" . self::DB_COLUMN_ID . "` = :id
				AND `" . self::DB_COLUMN_TUTOR_USER_ID . "` = :tutor_id";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->bindParam(':tutor_id', $tutorId, PDO::PARAM_INT);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check data already exists on database.");
		}

		return true;
	}

	public static function retrieveSingle($db, $id) {
		$query = "SELECT `" . self::DB_COLUMN_START_TIME . "`, `" . self::DB_COLUMN_END_TIME . "`, `" .
			self::DB_COLUMN_COURSE_ID . "`, `" . self::DB_COLUMN_TUTOR_USER_ID . "`,  `" . self::DB_COLUMN_TUTOR_USER_ID .
			"`,  `" . self::DB_COLUMN_ID . "`,  `" . self::DB_COLUMN_TERM_ID . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_COLUMN_ID . "`=:id";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);

			$query->execute();
			return $query->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Something terrible happened . Could not retrieve data from database .: ");
		} // end catch
	}

	public static function retrieveReport($db, $id) {
		$query = "SELECT `" . self::DB_COLUMN_START_TIME . "`, `" . self::DB_COLUMN_END_TIME . "`, `" .
			self::DB_COLUMN_COURSE_ID . "`, `" . self::DB_COLUMN_TUTOR_USER_ID . "`,  `" . self::DB_COLUMN_TUTOR_USER_ID .
			"`,  `" . self::DB_COLUMN_ID . "`,  `" . self::DB_COLUMN_TERM_ID . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_COLUMN_ID . "`=:id";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);

			$query->execute();
			return $query->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Something terrible happened . Could not retrieve data from database .: ");
		} // end catch
	}


	public static function retrieveAll($db) {
		$query =
			"SELECT `" . self::DB_COLUMN_ID . "` , `" . self::DB_COLUMN_START_TIME . "` , `" . self::DB_COLUMN_END_TIME . "`,
			 `" . self::DB_COLUMN_COURSE_ID . "`,  `" . self::DB_COLUMN_TUTOR_USER_ID . "`,  `" .
			self::DB_COLUMN_TUTOR_USER_ID . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "` ORDER BY `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_START_TIME . "` DESC";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database.");
		}
	}

	public static function retrieveTutors($db, $termId) {
		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "` , `" . self::DB_COLUMN_START_TIME . "` , `" .
			self::DB_COLUMN_END_TIME . "`, `" . self::DB_COLUMN_COURSE_ID . "`,  `" . self::DB_COLUMN_TUTOR_USER_ID . "`,
			`" . self::DB_COLUMN_TUTOR_USER_ID . "`, `" . UserFetcher::DB_COLUMN_FIRST_NAME . "` , `" .
			UserFetcher::DB_COLUMN_LAST_NAME . "`, `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_CODE . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			INNER JOIN  `" . DB_NAME . "`.`" . UserFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`  = `" .
			UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`
			INNER JOIN  `" . DB_NAME . "`.`" . CourseFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_COURSE_ID . "`  = `" .
			CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_ID . "`
			INNER JOIN  `" . DB_NAME . "`.`" . TermFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`  = `" .
			TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "`
			WHERE `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "` = :term_id
			ORDER BY `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_START_TIME . "` DESC";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':term_id', $termId, PDO::PARAM_INT);

			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database." . $e->getMessage());
		}
	}


	public static function retrieveSingleTutor($db, $tutorId, $termId) {
		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "` , `" . self::DB_COLUMN_START_TIME . "` , `" .
			self::DB_COLUMN_END_TIME . "`, `" . self::DB_COLUMN_COURSE_ID . "`,  `" . self::DB_COLUMN_TUTOR_USER_ID . "`,
			`" . self::DB_COLUMN_TUTOR_USER_ID . "`, `" . UserFetcher::DB_COLUMN_FIRST_NAME . "` , `" .
			UserFetcher::DB_COLUMN_LAST_NAME . "`, `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_CODE . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			INNER JOIN  `" . DB_NAME . "`.`" . UserFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`  = `" .
			UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`
			INNER JOIN  `" . DB_NAME . "`.`" . CourseFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_COURSE_ID . "`  = `" .
			CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_ID . "`
			INNER JOIN  `" . DB_NAME . "`.`" . TermFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TERM_ID . "`  = `" .
			TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "`
			WHERE `" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "` = :tutor_id
			AND `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "` = :term_id
			ORDER BY `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_START_TIME . "` DESC";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':tutor_id', $tutorId, PDO::PARAM_INT);
			$query->bindParam(':term_id', $termId, PDO::PARAM_INT);

			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database." . $e->getMessage());
		}
	}


	/**
	 * Appointments are considered completed if 30 minutes have from the passing of it's start time.
	 * @param $db
	 * @throws Exception
	 */
	public static function  retrieveCompletedWithoutReports($db) {
		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "` , `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_START_TIME . "` , `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_END_TIME . "`, `" .
			self::DB_TABLE . "`.`" . self::DB_COLUMN_COURSE_ID . "`, `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_TUTOR_USER_ID . "`,  `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_TUTOR_USER_ID . "`,
			`" . AppointmentHasStudentFetcher::DB_TABLE . "`.`" . AppointmentHasStudentFetcher::DB_COLUMN_STUDENT_ID . "`,
			`" . AppointmentHasStudentFetcher::DB_TABLE . "`.`" . AppointmentHasStudentFetcher::DB_COLUMN_ID . "` AS
			 " . AppointmentHasStudentFetcher::DB_TABLE . "_" . AppointmentHasStudentFetcher::DB_COLUMN_ID . ",
			`" . AppointmentHasStudentFetcher::DB_TABLE . "`.`" . AppointmentHasStudentFetcher::DB_COLUMN_INSTRUCTOR_ID . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			INNER JOIN  `" . DB_NAME . "`.`" . AppointmentHasStudentFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`  = `" .
			AppointmentHasStudentFetcher::DB_TABLE . "`.`" . AppointmentHasStudentFetcher::DB_COLUMN_APPOINTMENT_ID . "`
			WHERE TIME_TO_SEC(TIMEDIFF(NOW(),  `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_START_TIME . "`))/60 > 30
			AND `" . AppointmentHasStudentFetcher::DB_TABLE . "`.`" .
			AppointmentHasStudentFetcher::DB_COLUMN_REPORT_ID . "` IS NULL
			ORDER BY `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_START_TIME . "` DESC";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database." . $e->getMessage());
		}
	}

} 