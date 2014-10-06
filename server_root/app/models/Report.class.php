<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/26/2014
 * Time: 9:50 PM
 */
class Report
{

	const LABEL_COLOR_WARNING = "warning";
	const LABEL_MESSAGE_PENDING_FILL = "pending fill";
	const LABEL_MESSAGE_PENDING_VALIDATION = "pending validation";
	const LABEL_MESSAGE_COMPLETE = "complete";
	const LABEL_COLOR_SUCCESS = "success";


	public static function getAllWithAppointmentId($db, $appointmentId) {
		Appointment::validateId($db, $appointmentId);
		return ReportFetcher::retrieveAllAllWithAppointmentId($db, $appointmentId);
	}

	public static function getSingle($db, $reportId) {
		self::validateId($db, $reportId);
		return ReportFetcher::retrieveSingle($db, $reportId);
	}

	public static function validateId($db, $id) {
		if (!preg_match('/^[0-9]+$/', $id) || !ReportFetcher::existsId($db, $id)) {
			throw new Exception("Data tempering detected.
			<br/>You&#39;re trying to hack this app.<br/>Developers are being notified about this.<br/>Expect Us.");
		}
	}

	public static function updateProjectTopicOtherText($db, $reportId, $oldText, $newText) {
		if (strcmp($oldText, $newText) === 0) return false;
		self::validateId($db, $reportId);
		self::validateTextarea($newText, true);
		return ReportFetcher::updateSingleColumn($db, $reportId, $newText, ReportFetcher::DB_COLUMN_PROJECT_TOPIC_OTHER);
	}

	public static function validateTextarea($text, $tempVal) {
		$tempStringValidation = "/^[\\w\t\n\r\\ .,\\-]{0,512}$/";
		$finalStringValidation = "/^[\\w\t\n\r\\ .,\\-]{1,512}$/";
		$stringValidation = !$tempVal ? $finalStringValidation : $tempStringValidation;

		if (!preg_match($stringValidation, $text)) {
			throw new Exception("Textareas can contain only <a href='http://www.regular-expressions.info/shorthand.html'
			target='_blank'>word characters</a>, spaces, carriage returns, line feeds and special characters <strong>.,-2</strong> of max size 512 characters.");
		}
	}

	public static function updateOtherText($db, $reportId, $oldText, $newText) {
		if (strcmp($oldText, $newText) === 0) return false;
		self::validateId($db, $reportId);
		self::validateTextarea($newText, true);
		return ReportFetcher::updateSingleColumn($db, $reportId, $newText, ReportFetcher::DB_COLUMN_OTHER_TEXT_AREA);
	}

	public static function updateStudentsConcerns($db, $reportId, $oldText, $newText) {
		if (strcmp($oldText, $newText) === 0) return false;
		self::validateId($db, $reportId);
		self::validateTextarea($newText, true);
		return ReportFetcher::updateSingleColumn($db, $reportId, $newText, ReportFetcher::DB_COLUMN_STUDENT_CONCERNS);
	}

	public static function updateRelevantFeedbackGuidelines($db, $reportId, $oldText, $newText) {
		if (!isset($newText) || strcmp($oldText, $newText) === 0) return false;
		self::validateId($db, $reportId);
		self::validateTextarea($newText, true);
		return ReportFetcher::updateSingleColumn($db, $reportId, $newText, ReportFetcher::DB_COLUMN_RELEVANT_FEEDBACK_OR_GUIDELINES);
	}

	public static function updateAdditionalComments($db, $reportId, $oldText, $newText) {
		if (strcmp($oldText, $newText) === 0) return false;
		self::validateId($db, $reportId);
		self::validateTextarea($newText, true);
		return ReportFetcher::updateSingleColumn($db, $reportId, $newText, ReportFetcher::DB_COLUMN_ADDITIONAL_COMMENTS);
	}

	public static function updateAllFields($db, $reportId, $projectTopicOtherNew, $otherTextArea, $studentsConcernsTextArea, $relevantFeedbackGuidelines, $conclusionAdditionalComments) {
		self::validateId($db, $reportId);
		self::validateTextarea($projectTopicOtherNew, false);
		self::validateTextarea($otherTextArea, true);
		self::validateTextarea($studentsConcernsTextArea, false);
		self::validateTextarea($relevantFeedbackGuidelines, false);
		self::validateTextarea($conclusionAdditionalComments, true);
		return ReportFetcher::updateAllColumns($db, $reportId, $projectTopicOtherNew, $otherTextArea, $studentsConcernsTextArea, $relevantFeedbackGuidelines, $conclusionAdditionalComments);
	}

	public static function updateStudentBroughtAlong($db, $reportId, $newOptions, $oldOptions) {
		if ($newOptions === NULL) $newOptions = [];
		self::validateOptionsStudentBroughtAlong($newOptions);
		if (!self::validateIfUpdateIsNeeded($newOptions, $oldOptions)) return false;
		self::validateId($db, $reportId);
		return StudentBroughtAlongFetcher::update($db, $newOptions, $oldOptions, $reportId);
	}

	public static function validateOptionsStudentBroughtAlong($newOptions) {
		foreach ($newOptions as $option => $value) {
			switch ($option) {
				case StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_GRADED:
				case StudentBroughtAlongFetcher::DB_COLUMN_DRAFT:
				case StudentBroughtAlongFetcher::DB_COLUMN_INSTRUCTORS_FEEDBACK:
				case StudentBroughtAlongFetcher::DB_COLUMN_TEXTBOOK:
				case StudentBroughtAlongFetcher::DB_COLUMN_NOTES:
				case StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_SHEET:
				case StudentBroughtAlongFetcher::DB_COLUMN_EXERCISE_ON:
				case StudentBroughtAlongFetcher::DB_COLUMN_OTHER:
					break;
				case StudentBroughtAlongFetcher::DB_COLUMN_EXERCISE_ON . "text":
				case StudentBroughtAlongFetcher::DB_COLUMN_OTHER . "text":
					self::validateTextarea($newOptions[$option], true);

					break;
				default:
					throw new Exception("Data have been malformed.");
					break;
			}
		}
	}

	public static function validateIfUpdateIsNeeded($newOptions, $oldOptions) {
		foreach ($oldOptions as $key => $value) {
			switch ($key) {

				case StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_GRADED:
					if ((!isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_GRADED])
							&& strcmp($value, StudentBroughtAlongFetcher::IS_SELECTED) === 0)
						|| (isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_GRADED])
							&& strcmp($value, StudentBroughtAlongFetcher::IS_NOT_SELECTED) === 0)
					) return true;
					break;
				case StudentBroughtAlongFetcher::DB_COLUMN_DRAFT:
					if ((!isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_DRAFT])
							&& strcmp($value, StudentBroughtAlongFetcher::IS_SELECTED) === 0)
						|| (isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_DRAFT])
							&& strcmp($value, StudentBroughtAlongFetcher::IS_NOT_SELECTED) === 0)
					) return true;
					break;
				case StudentBroughtAlongFetcher::DB_COLUMN_INSTRUCTORS_FEEDBACK:
					if ((!isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_INSTRUCTORS_FEEDBACK])
							&& strcmp($value, StudentBroughtAlongFetcher::IS_SELECTED) === 0)
						|| (isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_INSTRUCTORS_FEEDBACK])
							&& strcmp($value, StudentBroughtAlongFetcher::IS_NOT_SELECTED) === 0)
					) return true;
					break;
				case StudentBroughtAlongFetcher::DB_COLUMN_TEXTBOOK:
					if ((!isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_TEXTBOOK])
							&& strcmp($value, StudentBroughtAlongFetcher::IS_SELECTED) === 0)
						|| (isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_TEXTBOOK])
							&& strcmp($value, StudentBroughtAlongFetcher::IS_NOT_SELECTED) === 0)
					) return true;
					break;
				case StudentBroughtAlongFetcher::DB_COLUMN_NOTES:
					if ((!isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_NOTES])
							&& strcmp($value, StudentBroughtAlongFetcher::IS_SELECTED) === 0)
						|| (isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_NOTES])
							&& strcmp($value, StudentBroughtAlongFetcher::IS_NOT_SELECTED) === 0)
					) return true;
					break;
				case StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_SHEET:
					if ((!isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_SHEET])
							&& strcmp($value, StudentBroughtAlongFetcher::IS_SELECTED) === 0)
						|| (isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_SHEET])
							&& strcmp($value, StudentBroughtAlongFetcher::IS_NOT_SELECTED) === 0)
					) return true;
					break;
				case StudentBroughtAlongFetcher::DB_COLUMN_EXERCISE_ON:
					if ((!isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_EXERCISE_ON])
							&& strcmp($value, StudentBroughtAlongFetcher::IS_SELECTED) !== NULL)
						|| (isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_EXERCISE_ON])
							&& strcmp($value, $newOptions[StudentBroughtAlongFetcher::DB_COLUMN_EXERCISE_ON . "text"]) !== 0)
					) return true;
					break;
				case StudentBroughtAlongFetcher::DB_COLUMN_OTHER:
					if ((!isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_OTHER])
							&& strcmp($value, StudentBroughtAlongFetcher::IS_SELECTED) !== NULL)
						||
						(isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_OTHER])
							&& strcmp($value, $newOptions[StudentBroughtAlongFetcher::DB_COLUMN_OTHER . "text"]) !== 0)
					) return true;
					break;
				default:
					return false;
					break;
			}
		}
	}
} 