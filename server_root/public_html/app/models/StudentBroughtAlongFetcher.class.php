<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/28/2014
 * Time: 10:25 PM
 */
class StudentBroughtAlongFetcher
{
	const DB_TABLE = "student_brought_along";
	const DB_COLUMN_REPORT_ID = "report_id";
	const DB_COLUMN_ASSIGNMENT_GRADED = "assignment_graded";
	const DB_COLUMN_DRAFT = "draft";
	const DB_COLUMN_INSTRUCTORS_FEEDBACK = "instructors_feedback";
	const DB_COLUMN_TEXTBOOK = "textbook";
	const DB_COLUMN_NOTES = "notes";
	const DB_COLUMN_ASSIGNMENT_SHEET = "assignment_sheet";
	const DB_COLUMN_EXERCISE_ON = "exercise_on";
	const DB_COLUMN_OTHER = "other";
	const IS_SELECTED = "1";
	const IS_NOT_SELECTED = "0";

	public static function insert($db, $reportId) {
		try {
			$queryInsertUser = "INSERT INTO `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			(`" . self::DB_COLUMN_REPORT_ID . "`)
			VALUES(:report_id)";


			$queryInsertUser = $db->getConnection()->prepare($queryInsertUser);
			$queryInsertUser->bindParam(':report_id', $reportId, PDO::PARAM_INT);

			$queryInsertUser->execute();

		} catch (Exception $e) {
			throw new Exception("Could not insert report data into database.");
		}

	}


	public static function update($db, $newOptions, $oldOptions, $reportId) {
		foreach ($oldOptions as $option => $value) {
			switch ($option) {
				case StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_GRADED:
					if (isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_GRADED])) {
						$assignmentGraded = 1;
					} else {
						$assignmentGraded = 0;
					}
					break;
				case StudentBroughtAlongFetcher::DB_COLUMN_DRAFT:
					if (isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_DRAFT])) {
						$draft = 1;
					} else {
						$draft = 0;
					}
					break;
				case StudentBroughtAlongFetcher::DB_COLUMN_INSTRUCTORS_FEEDBACK:
					if (isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_INSTRUCTORS_FEEDBACK])) {
						$instructorFeedback = 1;
					} else {
						$instructorFeedback = 0;
					}
					break;
				case StudentBroughtAlongFetcher::DB_COLUMN_TEXTBOOK:
					if (isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_TEXTBOOK])) {
						$textbook = 1;
					} else {
						$textbook = 0;
					}
					break;
				case StudentBroughtAlongFetcher::DB_COLUMN_NOTES:
					if (isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_NOTES])) {
						$notes = 1;
					} else {
						$notes = 0;
					}
					break;
				case StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_SHEET:
					if (isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_ASSIGNMENT_SHEET])) {
						$assignmentSheet = 1;
					} else {
						$assignmentSheet = 0;
					}
					break;
				case StudentBroughtAlongFetcher::DB_COLUMN_EXERCISE_ON:
					if (isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_EXERCISE_ON . "text"])
						&& strcmp($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_EXERCISE_ON . "text"], $value) !== 0
					) {
						$exerciseOn = $newOptions[StudentBroughtAlongFetcher::DB_COLUMN_EXERCISE_ON . "text"];
					} else {
						$exerciseOn = NULL;
					}
					break;
				case StudentBroughtAlongFetcher::DB_COLUMN_OTHER:
					if (isset($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_OTHER . "text"])
						&& strcmp($newOptions[StudentBroughtAlongFetcher::DB_COLUMN_OTHER], $value) !== 0
					) {
						$other = $newOptions[StudentBroughtAlongFetcher::DB_COLUMN_OTHER . "text"];
					} else {
						$other = NULL;
					}
					break;
				default:
					throw new Exception("Data have been malformed");
					break;
			}
		}


		$query = "UPDATE `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
					SET  `" . self::DB_COLUMN_ASSIGNMENT_GRADED . "`= :assignment_graded,
					`" . self::DB_COLUMN_DRAFT . "`= :draft,
					`" . self::DB_COLUMN_INSTRUCTORS_FEEDBACK . "`= :instructors_feedback,
					`" . self::DB_COLUMN_TEXTBOOK . "`= :textbook,
					`" . self::DB_COLUMN_NOTES . "`= :notes,
						`" . self::DB_COLUMN_ASSIGNMENT_SHEET . "`= :assignment_sheet,
					`" . self::DB_COLUMN_EXERCISE_ON . "`= :exercise_on,
					`" . self::DB_COLUMN_OTHER . "`= :other
					WHERE `" . self::DB_COLUMN_REPORT_ID . "` = :report_id";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':assignment_graded', $assignmentGraded, PDO::PARAM_INT);
			$query->bindParam(':draft', $draft, PDO::PARAM_INT);
			$query->bindParam(':instructors_feedback', $instructorFeedback, PDO::PARAM_INT);
			$query->bindParam(':textbook', $textbook, PDO::PARAM_INT);
			$query->bindParam(':notes', $notes, PDO::PARAM_INT);
			$query->bindParam(':assignment_sheet', $assignmentSheet, PDO::PARAM_INT);
			$query->bindParam(':exercise_on', $exerciseOn, PDO::PARAM_STR);
			$query->bindParam(':other', $other, PDO::PARAM_STR);

			$query->bindParam(':report_id', $reportId, PDO::PARAM_INT);

			$query->execute();

			return true;
		} catch
		(Exception $e) {
			throw new Exception("Could not update data.");
		}
		return false;
	}
} 