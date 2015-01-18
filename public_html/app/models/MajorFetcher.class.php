<?php

/**
 * Created by PhpStorm.
 * User: Rizart
 * Date: 9/2/2014
 * Time: 7:57 AM
 */
class MajorFetcher
{
	const DB_TABLE = "major";
	const DB_COLUMN_ID = "id";
	const DB_COLUMN_CODE = "code";
	const DB_COLUMN_NAME = "name";

	public static function retrieveMajors() {

		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_CODE . "`,
	        			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_NAME . "`,
	        			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`
					FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
					order by `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "` asc"; //ordering tis way so "Undecided" and "I do not know" to be first

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not retrieve majors data from database.");
		}
	}

	//This function is identical to retrieveMajors() but only used in courses.php to edit majors by excluding "I do not know" and "Undecided"
	public static function retrieveMajorsToEdit() {

		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_CODE . "`,
	        			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_NAME . "`,
	        			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`
				   FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
				  WHERE `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_CODE . "` != 'A'
				  AND   `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_CODE . "` != 'B'
					order by `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "` desc";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not retrieve majors data from database.");
		}
	}

	public static function idExists($majorId) {
		try {
			$sql = "SELECT COUNT(`" . self::DB_COLUMN_ID . "`) FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_ID . "` = :majorId";

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($sql);
			$query->bindParam(':majorId', $majorId, PDO::PARAM_INT);
			$query->execute();

			if ($query->fetchColumn() === 0) return false;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not check if major code already exists on database. <br/> Aborting process.");
		}

		return true;
	}

	public static function insert($majorCode, $majorName) {
		try {
			$query = "INSERT INTO `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . MajorFetcher::DB_TABLE . "` (`" . MajorFetcher::DB_COLUMN_CODE .
				"`, `" . MajorFetcher::DB_COLUMN_NAME . "`)
				VALUES(
					:major_code,
					:major_name
				)";

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':major_code', $majorCode, PDO::PARAM_STR);
			$query->bindParam(':major_name', $majorName, PDO::PARAM_STR);
			$query->execute();
			return true;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not insert major into database.");}

	}


	public static function update($majorId, $newMajorCode, $newMajorName) {
		$newMajorCode = trim($newMajorCode);
		$newMajorName = trim($newMajorName);

		$query = "UPDATE `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . MajorFetcher::DB_TABLE . "`
					SET `" . self::DB_COLUMN_CODE . "`= :newMajorCode, 
						`" . self::DB_COLUMN_NAME . "`= :newMajorName
					WHERE `" . self::DB_COLUMN_ID . "`= :majorId";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':newMajorCode', $newMajorCode, PDO::PARAM_STR);
			$query->bindParam(':newMajorName', $newMajorName, PDO::PARAM_STR);
			$query->execute();

			return true;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Something terrible happened. Could not update major.");}

	}

	public static function updateName($id, $newName) {
		$newName = trim($newName);

		$query = "UPDATE `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . MajorFetcher::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_NAME . "`= :newMajorName
					WHERE  `" . self::DB_COLUMN_ID . "`= :majorId";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':majorId', $id, PDO::PARAM_INT);
			$query->bindParam(':newMajorName', $newName, PDO::PARAM_STR);
			$query->execute();

			return true;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Something terrible happened. Could not update major name");}

	}

	public static function updateCode($id, $newCode) {
		$newCode = trim($newCode);

		$query = "UPDATE `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . MajorFetcher::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_CODE . "`= :newCode
					WHERE  `" . self::DB_COLUMN_ID . "`= :majorId";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':majorId', $id, PDO::PARAM_INT);
			$query->bindParam(':newCode', $newCode, PDO::PARAM_STR);
			$query->execute();

			return true;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Something terrible happened. Could not update major code");}

	}

	public static function codeExists($majorCode) {
		try {
			$query = "SELECT COUNT(" . self::DB_COLUMN_CODE . ") FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_CODE . "` = :majorCode";
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':majorCode', $majorCode, PDO::PARAM_STR);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not check if major id already exists on database. <br/> Aborting process.");
		}

		return true;
	}


	public static function nameExists($majorName) {
		try {
			$query = "SELECT COUNT(" . self::DB_COLUMN_NAME . ") FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_NAME . "` = :majorName";

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':majorName', $majorName, PDO::PARAM_STR);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not check if major name already exists on database. <br/> Aborting process.");
		}

		return true;
	}

	public static function delete($id) {
		try {
			$query = "DELETE FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_ID . "` = :id";
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->execute();
			return true;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not delete major from database. Please first remove this major from students have this major, then you can delete this major.");
		}
	}

	public function getMajors() {

		$query = "SELECT major.code AS 'Code', major.name AS 'Name', major.id
				FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.major";
		try {
			$query = $this->db->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not retrieve majors data from database.");
		}
	}

}