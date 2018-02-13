<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 10/15/2014
 * Time: 9:14 PM
 */
class ConclusionWrapUpFetcher
{
	const DB_TABLE = "conclusion_wrap_up";
	const DB_COLUMN_REPORT_ID = "report_id";

	const DB_COLUMN_QUESTIONS_ADDRESSED = "questions_addressed";
	const DB_COLUMN_ANOTHER_SCHEDULE = "another_schedule";
	const DB_COLUMN_CLARIFY_CONCERNS = "clarify_concerns";

	const IS_SELECTED = "1";
	const IS_NOT_SELECTED = "0";


	public static function delete($reportId) {
		try {
			$query =
				"DELETE FROM `" . App::getDbName() . "`.`" . self::DB_TABLE . "`
				WHERE `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_REPORT_ID . "` = :report_id";


			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':report_id', $reportId, PDO::PARAM_INT);
			$query->execute();

			return $query->rowCount();
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not delete report data.");
		}

		return false;
	}

	public static function update($reportId, $newOptions, $oldOptions) {
		if (!self::exists($reportId)) self::insert($reportId);

		foreach ($oldOptions as $option => $value) {
			switch ($option) {
				case self::DB_COLUMN_QUESTIONS_ADDRESSED:
					if (isset($newOptions[self::DB_COLUMN_QUESTIONS_ADDRESSED])) {
						$questionsAddressed = 1;
					} else {
						$questionsAddressed = 0;
					}
					break;
				case self::DB_COLUMN_ANOTHER_SCHEDULE:
					if (isset($newOptions[self::DB_COLUMN_ANOTHER_SCHEDULE])) {
						$anotherSchedule = 1;
					} else {
						$anotherSchedule = 0;
					}
					break;
				case self::DB_COLUMN_CLARIFY_CONCERNS:
					if (isset($newOptions[self::DB_COLUMN_CLARIFY_CONCERNS])) {
						$clarifyConcerns = 1;
					} else {
						$clarifyConcerns = 0;
					}
					break;

				default:
					throw new Exception("Data have been malformed. Process aborted.");
					break;
			}
		}

		$query = "UPDATE `" . App::getDbName() . "`.`" . self::DB_TABLE . "`
			SET `" . self::DB_COLUMN_QUESTIONS_ADDRESSED . "` = :questions_addressed,
			`" . self::DB_COLUMN_ANOTHER_SCHEDULE . "` = :another_schedule,
			`" . self::DB_COLUMN_CLARIFY_CONCERNS . "` = :clarify_concerns
			WHERE `" . self::DB_COLUMN_REPORT_ID . "` = :report_id";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':questions_addressed', $questionsAddressed, PDO::PARAM_INT);
			$query->bindParam(':another_schedule', $anotherSchedule, PDO::PARAM_INT);
			$query->bindParam(':clarify_concerns', $clarifyConcerns, PDO::PARAM_INT);

			$query->bindParam(':report_id', $reportId, PDO::PARAM_INT);

			$query->execute();

			return true;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not update report data." . $e->getMessage());
		}
		return false;
	}

	public static function exists($reportId) {
		try {
			$query = "SELECT COUNT(" . self::DB_COLUMN_REPORT_ID . ")
			FROM `" . App::getDbName() . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_COLUMN_REPORT_ID . "` = :report_id";

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':report_id', $reportId, PDO::PARAM_INT);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not verify data on database.");
		}

		return true;
	}

	public static function insert($reportId) {

		try {

			$query = "INSERT INTO `" . App::getDbName() . "`.`" . self::DB_TABLE . "`
			(`" . self::DB_COLUMN_REPORT_ID . "`)
			VALUES(:report_id)";


			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':report_id', $reportId, PDO::PARAM_INT);
			$query->execute();

		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not insert report data into database.");
		}

		return false;
	}
} 