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
	const DB_COLUMN_OTHER_TEXT_AREA = "other_text_area";
	const DB_COLUMN_STUDENT_CONCERNS = "students_concerns";
	const DB_COLUMN_RELEVANT_FEEDBACK_OR_GUIDELINES = "relevant_feedback_or_guidelines";
	const DB_COLUMN_ADDITIONAL_COMMENTS = "additional_comments";
	const DB_COLUMN_LABEL_MESSAGE = "label_message";
	const DB_COLUMN_LABEL_COLOR = "label_color";

	public static function retrieveAllWithAppointmentId($db, $appointmentId) {
		$query =
			"SELECT `" . StudentFetcher::DB_TABLE . "`.`" . StudentFetcher::DB_COLUMN_FIRST_NAME . "` ,
			`" . StudentFetcher::DB_TABLE . "`.`" . StudentFetcher::DB_COLUMN_LAST_NAME . "` ,
			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "` ,
			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_STUDENT_ID . "` AS " . StudentFetcher::DB_TABLE . "_" .
			StudentFetcher::DB_COLUMN_ID . ",
				`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_INSTRUCTOR_ID . "` AS " . InstructorFetcher::DB_TABLE .
			"_" . InstructorFetcher::DB_COLUMN_ID . ",
			  `" . self::DB_COLUMN_PROJECT_TOPIC_OTHER . "`,
			  `" . self::DB_COLUMN_STUDENT_CONCERNS . "`,
			  `" . self::DB_COLUMN_OTHER_TEXT_AREA . "`,
			  `" . self::DB_COLUMN_STUDENT_CONCERNS . "`,
			  `" . self::DB_COLUMN_RELEVANT_FEEDBACK_OR_GUIDELINES . "`,
			  `" . self::DB_COLUMN_ADDITIONAL_COMMENTS . "`,
			  `" . self::DB_COLUMN_LABEL_MESSAGE . "` ,
			  `" . self::DB_COLUMN_LABEL_COLOR . "`,
			`" . StudentBroughtAlongFetcher::DB_TABLE . "`.`" . StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_GRADED . "`,
			`" . StudentBroughtAlongFetcher::DB_TABLE . "`.`" . StudentBroughtAlongFetcher::DB_COLUMN_DRAFT . "`,
			`" . StudentBroughtAlongFetcher::DB_TABLE . "`.`" . StudentBroughtAlongFetcher::DB_COLUMN_INSTRUCTORS_FEEDBACK . "`,
			`" . StudentBroughtAlongFetcher::DB_TABLE . "`.`" . StudentBroughtAlongFetcher::DB_COLUMN_TEXTBOOK . "`,
			`" . StudentBroughtAlongFetcher::DB_TABLE . "`.`" . StudentBroughtAlongFetcher::DB_COLUMN_NOTES . "`,
			`" . StudentBroughtAlongFetcher::DB_TABLE . "`.`" . StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_SHEET . "`,
			`" . StudentBroughtAlongFetcher::DB_TABLE . "`.`" . StudentBroughtAlongFetcher::DB_COLUMN_EXERCISE_ON . "`,
			`" . StudentBroughtAlongFetcher::DB_TABLE . "`.`" . StudentBroughtAlongFetcher::DB_COLUMN_OTHER . "`

			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			INNER JOIN  `" . DB_NAME . "`.`" . AppointmentHasStudentFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`  = `" .
			AppointmentHasStudentFetcher::DB_TABLE . "`.`" . AppointmentHasStudentFetcher::DB_COLUMN_REPORT_ID . "`
			INNER JOIN  `" . DB_NAME . "`.`" . StudentFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_STUDENT_ID . "`  = `" .
			StudentFetcher::DB_TABLE . "`.`" . StudentFetcher::DB_COLUMN_ID . "`
			INNER JOIN  `" . DB_NAME . "`.`" . StudentBroughtAlongFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`  = `" .
			StudentBroughtAlongFetcher::DB_TABLE . "`.`" . StudentBroughtAlongFetcher::DB_COLUMN_REPORT_ID . "`
			WHERE `" . AppointmentHasStudentFetcher::DB_TABLE . "`.`" .
			AppointmentHasStudentFetcher::DB_COLUMN_APPOINTMENT_ID . "` = :appointment_id";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':appointment_id', $appointmentId, PDO::PARAM_INT);

			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve reports data from database." . $e->getMessage());
		}
	}

	/**
	 * @param $db
	 * @param $tutorId
	 * @throws Exception
	 */
	public static function retrieveAllOfCurrTermsByTutor($db, $tutorId) {
		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`, `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_LABEL_MESSAGE . "`,
			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_LABEL_COLOR . "`, `" . AppointmentFetcher::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`
			AS " . AppointmentFetcher::DB_TABLE . "_" . AppointmentFetcher::DB_COLUMN_ID . "
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			INNER JOIN `" . DB_NAME . "`.`" . AppointmentHasStudentFetcher::DB_TABLE . "`
				ON `" . AppointmentHasStudentFetcher::DB_TABLE . "`.`" . AppointmentHasStudentFetcher::DB_COLUMN_REPORT_ID . "` =
					`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`
			INNER JOIN `" . DB_NAME . "`.`" . AppointmentFetcher::DB_TABLE . "`
				ON `" . AppointmentFetcher::DB_TABLE . "`.`" . AppointmentFetcher::DB_COLUMN_ID . "` =
					`" . AppointmentHasStudentFetcher::DB_TABLE . "`.`" . AppointmentHasStudentFetcher::DB_COLUMN_APPOINTMENT_ID . "`
			INNER JOIN `" . TermFetcher::DB_TABLE . "`
				ON `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "` =
					`" . AppointmentFetcher::DB_TABLE . "`.`" . AppointmentFetcher::DB_COLUMN_TERM_ID . "`
			WHERE CURRENT_TIMESTAMP() BETWEEN `" . TermFetcher::DB_COLUMN_START_DATE . "` AND `" . TermFetcher::DB_COLUMN_END_DATE . "`
			AND `" . AppointmentFetcher::DB_TABLE . "`.`" . AppointmentFetcher::DB_COLUMN_TUTOR_USER_ID . "` = :tutor_id
			ORDER BY `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "` ASC";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':tutor_id', $tutorId, PDO::PARAM_INT);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database.");
		}
	}

	/**
	 * @param $db
	 * @throws Exception
	 */
	public static function retrieveAllOfCurrTerms($db) {
		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`, `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_LABEL_MESSAGE . "`,
			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_LABEL_COLOR . "`, `" . AppointmentFetcher::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`
			AS " . AppointmentFetcher::DB_TABLE . "_" . AppointmentFetcher::DB_COLUMN_ID . "
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			INNER JOIN `" . DB_NAME . "`.`" . AppointmentHasStudentFetcher::DB_TABLE . "`
				ON `" . AppointmentHasStudentFetcher::DB_TABLE . "`.`" . AppointmentHasStudentFetcher::DB_COLUMN_REPORT_ID . "` =
					`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`
			INNER JOIN `" . DB_NAME . "`.`" . AppointmentFetcher::DB_TABLE . "`
				ON `" . AppointmentFetcher::DB_TABLE . "`.`" . AppointmentFetcher::DB_COLUMN_ID . "` =
					`" . AppointmentHasStudentFetcher::DB_TABLE . "`.`" . AppointmentHasStudentFetcher::DB_COLUMN_APPOINTMENT_ID . "`
			INNER JOIN `" . TermFetcher::DB_TABLE . "`
				ON `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_ID . "` =
					`" . AppointmentFetcher::DB_TABLE . "`.`" . AppointmentFetcher::DB_COLUMN_TERM_ID . "`
			WHERE CURRENT_TIMESTAMP() BETWEEN `" . TermFetcher::DB_COLUMN_START_DATE . "` AND `" . TermFetcher::DB_COLUMN_END_DATE . "`
			ORDER BY `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "` ASC";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database.");
		}
	}

	public static function updateLabel($db, $reportId, $labelMessage, $labelColor) {
		$query = "UPDATE `" . DB_NAME . "`.`" . self::DB_TABLE . "`
					SET `" . self::DB_COLUMN_LABEL_MESSAGE . "`= :label_message, `" . self::DB_COLUMN_LABEL_COLOR . "` =
					:label_color
					WHERE `" . self::DB_COLUMN_ID . "` = :id";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':id', $reportId, PDO::PARAM_INT);
			$query->bindParam(':label_message', $labelMessage, PDO::PARAM_STR);
			$query->bindParam(':label_color', $labelColor, PDO::PARAM_STR);

			$query->execute();
			return true;
		} catch (Exception $e) {
			throw new Exception("Could not update data.");
		}
		return false;
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
			StudentBroughtAlongFetcher::insert($db, $reportId);
			AppointmentHasStudentFetcher::update($db, $appointmentId, $reportId);

			$db->getConnection()->commit();
			return $reportId;
		} catch (Exception $e) {
			$db->getConnection()->rollback();
			throw new Exception("Could not insert data into database.");
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

	public static function updateSingleColumn($db, $reportId, $newText, $column) {
		$query = "UPDATE `" . DB_NAME . "`.`" . self::DB_TABLE . "`
					SET `" . $column . "`= :new_text
					WHERE `" . self::DB_COLUMN_ID . "` = :report_id";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':new_text', $newText, PDO::PARAM_STR);
			$query->bindParam(':report_id', $reportId, PDO::PARAM_INT);

			$query->execute();

			return true;
		} catch (Exception $e) {
			throw new Exception("Could not update data.");
		}
		return false;
	}

	public static function updateAllColumns($db, $reportId, $projectTopicOtherNew, $otherTextArea,
	                                        $studentsConcernsTextArea, $relevantFeedbackGuidelines, $studentBroughtAlongNew, $studentBroughtAlongOld, $conclusionAdditionalComments) {
		$query = "UPDATE `" . DB_NAME . "`.`" . self::DB_TABLE . "`
					SET  `" . self::DB_COLUMN_PROJECT_TOPIC_OTHER . "`= :project_topic_other,
					`" . self::DB_COLUMN_OTHER_TEXT_AREA . "`= :other_text_area,
					`" . self::DB_COLUMN_STUDENT_CONCERNS . "`= :students_concerns_text_area,
					`" . self::DB_COLUMN_ADDITIONAL_COMMENTS . "`= :additional_comments,
					`" . self::DB_COLUMN_RELEVANT_FEEDBACK_OR_GUIDELINES . "`= :relevant_feedback_guidelines
					WHERE `" . self::DB_COLUMN_ID . "` = :report_id";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':project_topic_other', $projectTopicOtherNew, PDO::PARAM_STR);
			$query->bindParam(':other_text_area', $otherTextArea, PDO::PARAM_STR);
			$query->bindParam(':students_concerns_text_area', $studentsConcernsTextArea, PDO::PARAM_STR);
			$query->bindParam(':additional_comments', $conclusionAdditionalComments, PDO::PARAM_STR);
			$query->bindParam(':relevant_feedback_guidelines', $relevantFeedbackGuidelines, PDO::PARAM_STR);

			$query->bindParam(':report_id', $reportId, PDO::PARAM_INT);

			$query->execute();

			Report::updateStudentBroughtAlong($db, $reportId, $studentBroughtAlongNew, $studentBroughtAlongOld);

			return true;
		} catch (Exception $e) {
			throw new Exception("Could not update data.");
		}
		return false;
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