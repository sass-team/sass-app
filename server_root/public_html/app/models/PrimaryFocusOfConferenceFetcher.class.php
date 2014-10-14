<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 10/13/2014
 * Time: 1:31 PM
 */
class PrimaryFocusOfConferenceFetcher
{
	const DB_TABLE = "primary_focus_of_conference";
	const DB_COLUMN_REPORT_ID = "report_id";

	const DB_COLUMN_DISCUSSION_OF_CONCEPTS = "discussion_of_concept";
	const DB_COLUMN_ORGANIZATION_THOUGHTS_IDEAS = "organization_thoughts_ideas";
	const DB_COLUMN_EXPRESSION_GRAMMAR_SYNTAX_ETC = "expression_grammar_syntax_etc";
	const DB_COLUMN_EXERCISES = "exercises";
	const DB_COLUMN_ACADEMIC_SKILLS = "academic_skills";
	const DB_COLUMN_CITATIONS_REFERENCING = "citations_referencing";
	const DB_COLUMN_OTHER = "other";
	const IS_SELECTED = "1";
	const IS_NOT_SELECTED = "0";

	public static function insert($reportId) {
		try {
			$query = "INSERT INTO `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			(`" . self::DB_COLUMN_REPORT_ID . "`)
			VALUES(:report_id)";


			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':report_id', $reportId, PDO::PARAM_INT);

			$query->execute();

		} catch (Exception $e) {
			throw new Exception("Could not insert report data into database.");
		}

	}
} 