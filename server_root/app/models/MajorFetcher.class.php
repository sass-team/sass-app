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

	public static function retrieveMajors($db) {

		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_CODE . "`,
	        			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_NAME . "`,
	        			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`
					FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
					order by `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "` asc"; //ordering tis way so "Undecided" and "I do not know" to be first

		try {
			$query = $db->getConnection()->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			throw new Exception("Could not retrieve majors data from database.");
		}
	}

	//This function is identical to retrieveMajors() but only used in courses.php to edit majors by excluding "I do not know" and "Undecided"
	public static function retrieveMajorsToEdit($db) {

		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_CODE . "`,
	        			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_NAME . "`,
	        			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`
				   FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
				  WHERE `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_CODE . "` != 'A'
				  AND   `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_CODE . "` != 'B'
					order by `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "` desc";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			throw new Exception("Could not retrieve majors data from database.");
		}
	}

	public static function idExists($db, $majorId) {
		try {
			$sql = "SELECT COUNT(" . self::DB_COLUMN_ID . ") FROM `" . DB_NAME . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_ID . "` = :majorId";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':majorId', $majorId, PDO::PARAM_INT);
			$query->execute();

			if ($query->fetchColumn() === 0) return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if major code already exists on database. <br/> Aborting process.");
		}

		return true;
	}

	public static function insert($db, $majorCode, $majorName) {
		try {
			$query = "INSERT INTO `" . DB_NAME . "`.`" . MajorFetcher::DB_TABLE . "` (`" . MajorFetcher::DB_COLUMN_CODE .
				"`, `" . MajorFetcher::DB_COLUMN_NAME . "`)
				VALUES(
					:major_code,
					:major_name
				)";

			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':major_code', $majorCode, PDO::PARAM_STR);
			$query->bindParam(':major_name', $majorName, PDO::PARAM_STR);
			$query->execute();
			return true;
		} catch (Exception $e) {
			throw new Exception("Could not insert major into database.");}

	}


	public static function update($db, $majorId, $newMajorCode, $newMajorName) {
		$newMajorCode = trim($newMajorCode);
		$newMajorName = trim($newMajorName);

		$query = "UPDATE `" . DB_NAME . "`.`" . MajorFetcher::DB_TABLE . "`
					SET `" . self::DB_COLUMN_CODE . "`= :newMajorCode, 
						`" . self::DB_COLUMN_NAME . "`= :newMajorName
					WHERE `" . self::DB_COLUMN_ID . "`= :majorId";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':newMajorCode', $newMajorCode, PDO::PARAM_STR);
			$query->bindParam(':newMajorName', $newMajorName, PDO::PARAM_STR);
			$query->execute();

			return true;
		} catch (Exception $e) {
			throw new Exception("Something terrible happened. Could not update major.");}

	}

	public static function updateName($db, $id, $newName) {
		$newName = trim($newName);

		$query = "UPDATE `" . DB_NAME . "`.`" . MajorFetcher::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_NAME . "`= :newMajorName
					WHERE  `" . self::DB_COLUMN_ID . "`= :majorId";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':majorId', $id, PDO::PARAM_INT);
			$query->bindParam(':newMajorName', $newName, PDO::PARAM_STR);
			$query->execute();

			return true;
		} catch (Exception $e) {
			throw new Exception("Something terrible happened. Could not update major name");}

	}

	public static function updateCode($db, $id, $newCode) {
		$newCode = trim($newCode);

		$query = "UPDATE `" . DB_NAME . "`.`" . MajorFetcher::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_CODE . "`= :newCode
					WHERE  `" . self::DB_COLUMN_ID . "`= :majorId";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':majorId', $id, PDO::PARAM_INT);
			$query->bindParam(':newCode', $newCode, PDO::PARAM_STR);
			$query->execute();

			return true;
		} catch (Exception $e) {
			throw new Exception("Something terrible happened. Could not update major code");}

	}

	public static function codeExists($db, $majorCode) {
		try {
			$sql = "SELECT COUNT(" . self::DB_COLUMN_CODE . ") FROM `" . DB_NAME . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_CODE . "` = :majorCode";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':majorCode', $majorCode, PDO::PARAM_STR);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if major id already exists on database. <br/> Aborting process.");
		}

		return true;
	}


	public static function nameExists($db, $majorName) {
		try {
			$sql = "SELECT COUNT(" . self::DB_COLUMN_NAME . ") FROM `" . DB_NAME . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_NAME . "` = :majorName";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':majorName', $majorName, PDO::PARAM_STR);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if major name already exists on database. <br/> Aborting process.");
		}

		return true;
	}

	public static function delete($db, $id) {
		try {
			$query = "DELETE FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_ID . "` = :id";
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->execute();
			return true;
		} catch (Exception $e) {
			throw new Exception("Could not delete major from database. Please first remove this major from students have this major, then you can delete this major.");
		}
	}

	public function getMajors() {

		$query = "SELECT major.code AS 'Code', major.name AS 'Name', major.id
				FROM `" . DB_NAME . "`.major";
		try {
			$query = $this->db->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			throw new Exception("Could not retrieve majors data from database.");
		}
	}

}