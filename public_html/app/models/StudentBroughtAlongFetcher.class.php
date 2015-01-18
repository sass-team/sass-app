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


	public static function delete($reportId) {
		try {
			$query =
				"DELETE FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
				WHERE `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_REPORT_ID . "` = :report_id";


			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':report_id', $reportId, PDO::PARAM_INT);
			$query->execute();
			return $query->rowCount();

		} catch (Exception $e) {
			throw new Exception("Could not delete report data.");
		}

		return false;
	}

	public static function update($newOptions, $oldOptions, $reportId) {

		foreach ($oldOptions as $oldOption => $oldValue) {
			switch ($oldOption) {
				case self::DB_COLUMN_ASSIGNMENT_GRADED:
					if (isset($newOptions[self::DB_COLUMN_ASSIGNMENT_GRADED])) {
						$assignmentGraded = 1;
					} else {
						$assignmentGraded = 0;
					}
					break;
				case self::DB_COLUMN_DRAFT:
					if (isset($newOptions[self::DB_COLUMN_DRAFT])) {
						$draft = 1;
					} else {
						$draft = 0;
					}
					break;
				case self::DB_COLUMN_INSTRUCTORS_FEEDBACK:
					if (isset($newOptions[self::DB_COLUMN_INSTRUCTORS_FEEDBACK])) {
						$instructorFeedback = 1;
					} else {
						$instructorFeedback = 0;
					}
					break;
				case self::DB_COLUMN_TEXTBOOK:
					if (isset($newOptions[self::DB_COLUMN_TEXTBOOK])) {
						$textbook = 1;
					} else {
						$textbook = 0;
					}
					break;
				case self::DB_COLUMN_NOTES:
					if (isset($newOptions[self::DB_COLUMN_NOTES])) {
						$notes = 1;
					} else {
						$notes = 0;
					}
					break;
				case self::DB_COLUMN_ASSIGNMENT_SHEET:
					if (isset($newOptions[self::DB_COLUMN_ASSIGNMENT_SHEET])) {
						$assignmentSheet = 1;
					} else {
						$assignmentSheet = 0;
					}
					break;
				case self::DB_COLUMN_EXERCISE_ON:
					$exerciseOn = $newOptions[self::DB_COLUMN_EXERCISE_ON . "text"];
					break;
				case self::DB_COLUMN_OTHER:
					$other = $newOptions[self::DB_COLUMN_OTHER . "text"];
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
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
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

	public static function exists($reportId) {
		try {
			$query = "SELECT COUNT(" . self::DB_COLUMN_REPORT_ID . ")
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_COLUMN_REPORT_ID . "` = :report_id";

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':report_id', $reportId, PDO::PARAM_INT);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not verify data on database.");
		}

		return true;
	}

	public static function insert($reportId) {
		try {
			$queryInsertUser = "INSERT INTO `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			(`" . self::DB_COLUMN_REPORT_ID . "`)
			VALUES(:report_id)";


			$dbConnection = DatabaseManager::getConnection();
			$queryInsertUser = $dbConnection->prepare($queryInsertUser);
			$queryInsertUser->bindParam(':report_id', $reportId, PDO::PARAM_INT);

			$queryInsertUser->execute();

		} catch (Exception $e) {
			throw new Exception("Could not insert report data into database.");
		}

	}
} 