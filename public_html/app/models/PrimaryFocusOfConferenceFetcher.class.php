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

	const STRING_DISCUSSION_OF_CONCEPTS = "Discussion of concepts";
	const STRING_ORGANIZATION_THOUGHTS_IDEAS = "Organization of thoughts/ideas";
	const STRING_EXPRESSION_GRAMMAR_SYNTAX_ETC = "Expression (grammar, syntax, diction, etc.)";
	const STRING_EXERCISES = "Exercises";
	const STRING_ACADEMIC_SKILLS = "Academic skills";
	const STRING_CITATIONS_REFERENCING = "Citations & Referencing";
	const STRING_OTHER = "Other";


	public static function retrieveForTerm($termId) {
		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_DISCUSSION_OF_CONCEPTS . "` , `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_ORGANIZATION_THOUGHTS_IDEAS . "` , `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_EXPRESSION_GRAMMAR_SYNTAX_ETC . "`,
			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_REPORT_ID . "`,  `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_EXERCISES . "`, `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_ACADEMIC_SKILLS . "`,`" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_CITATIONS_REFERENCING . "`,`" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_OTHER . "`,`" . AppointmentHasStudentFetcher::DB_TABLE . "`.`" .
			AppointmentHasStudentFetcher::DB_COLUMN_APPOINTMENT_ID . "`

			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			INNER JOIN `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . ReportFetcher::DB_TABLE . "`
			ON `" . ReportFetcher::DB_TABLE . "`.`" . ReportFetcher::DB_COLUMN_ID . "` =
			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_REPORT_ID . "`
			INNER JOIN `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . AppointmentHasStudentFetcher::DB_TABLE . "`
			ON `" . AppointmentHasStudentFetcher::DB_TABLE . "`.`" . AppointmentHasStudentFetcher::DB_COLUMN_REPORT_ID . "` =
			`" . ReportFetcher::DB_TABLE . "`.`" . ReportFetcher::DB_COLUMN_ID . "`
			INNER JOIN `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . AppointmentFetcher::DB_TABLE . "`
			ON `" . AppointmentFetcher::DB_TABLE . "`.`" . AppointmentFetcher::DB_COLUMN_ID . "` =
			`" . AppointmentHasStudentFetcher::DB_TABLE . "`.`" . AppointmentHasStudentFetcher::DB_COLUMN_APPOINTMENT_ID . "`
			WHERE `" . AppointmentFetcher::DB_COLUMN_TERM_ID . "` = :term_id";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':term_id', $termId, PDO::PARAM_INT);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not retrieve data from database.");
		}
	}

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
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not delete report data.");
		}

		return false;
	}

	public static function update($reportId, $newOptions, $oldOptions) {
		if (!self::exists($reportId)) self::insert($reportId);

		foreach ($oldOptions as $option => $value) {
			switch ($option) {
				case self::DB_COLUMN_DISCUSSION_OF_CONCEPTS:
					if (isset($newOptions[self::DB_COLUMN_DISCUSSION_OF_CONCEPTS])) {
						$discussionOfConcepts = 1;
					} else {
						$discussionOfConcepts = 0;
					}
					break;
				case self::DB_COLUMN_ORGANIZATION_THOUGHTS_IDEAS:
					if (isset($newOptions[self::DB_COLUMN_ORGANIZATION_THOUGHTS_IDEAS])) {
						$organizationThoughtsIdeas = 1;
					} else {
						$organizationThoughtsIdeas = 0;
					}
					break;
				case self::DB_COLUMN_EXPRESSION_GRAMMAR_SYNTAX_ETC:
					if (isset($newOptions[self::DB_COLUMN_EXPRESSION_GRAMMAR_SYNTAX_ETC])) {
						$expressionGrammarSyntaxEtc = 1;
					} else {
						$expressionGrammarSyntaxEtc = 0;
					}
					break;
				case self::DB_COLUMN_EXERCISES:
					if (isset($newOptions[self::DB_COLUMN_EXERCISES])) {
						$exercises = 1;
					} else {
						$exercises = 0;
					}
					break;
				case self::DB_COLUMN_ACADEMIC_SKILLS:
					if (isset($newOptions[self::DB_COLUMN_ACADEMIC_SKILLS])) {
						$academicSkills = 1;
					} else {
						$academicSkills = 0;
					}
					break;
				case self::DB_COLUMN_CITATIONS_REFERENCING:
					if (isset($newOptions[self::DB_COLUMN_CITATIONS_REFERENCING])) {
						$citationsReferencing = 1;
					} else {
						$citationsReferencing = 0;
					}
					break;
				case PrimaryFocusOfConferenceFetcher::DB_TABLE . "_" . PrimaryFocusOfConferenceFetcher::DB_COLUMN_OTHER:
					if (isset($newOptions[PrimaryFocusOfConferenceFetcher::DB_TABLE . "_" . PrimaryFocusOfConferenceFetcher::DB_COLUMN_OTHER])) {
						$other = 1;
					} else {
						$other = 0;
					}
					break;
				default:
					throw new Exception("Data have been malformed. Process aborted.");
					break;
			}
		}

		$query = "UPDATE `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			SET `" . self::DB_COLUMN_DISCUSSION_OF_CONCEPTS . "` = :discussion_of_concepts,
			`" . self::DB_COLUMN_ORGANIZATION_THOUGHTS_IDEAS . "` = :organization_thoughts_ideas,
			`" . self::DB_COLUMN_EXPRESSION_GRAMMAR_SYNTAX_ETC . "` = :expression_grammar_syntax,
			`" . self::DB_COLUMN_EXERCISES . "` = :exercises, `" . self::DB_COLUMN_ACADEMIC_SKILLS . "` = :academic_skills,
			`" . self::DB_COLUMN_CITATIONS_REFERENCING . "` = :citation_referencing, `" . self::DB_COLUMN_OTHER . "` = :other
			WHERE `" . self::DB_COLUMN_REPORT_ID . "` = :report_id";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':discussion_of_concepts', $discussionOfConcepts, PDO::PARAM_INT);
			$query->bindParam(':organization_thoughts_ideas', $organizationThoughtsIdeas, PDO::PARAM_INT);
			$query->bindParam(':expression_grammar_syntax', $expressionGrammarSyntaxEtc, PDO::PARAM_INT);
			$query->bindParam(':exercises', $exercises, PDO::PARAM_INT);
			$query->bindParam(':academic_skills', $academicSkills, PDO::PARAM_INT);
			$query->bindParam(':citation_referencing', $citationsReferencing, PDO::PARAM_INT);
			$query->bindParam(':other', $other, PDO::PARAM_INT);

			$query->bindParam(':report_id', $reportId, PDO::PARAM_INT);

			$query->execute();

			return true;
		} catch
		(Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not update report data.");
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
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not verify data on database.");
		}

		return true;
	}

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
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not insert report data into database.");
		}

		return false;
	}
} 