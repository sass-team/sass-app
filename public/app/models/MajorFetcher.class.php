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

	public static function codeExists($db, $code) {
		try {
			$sql = "SELECT COUNT(" . self::DB_COLUMN_CODE . ") FROM `" . DB_NAME . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_CODE . "` = :code";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':code', $code, PDO::PARAM_STR);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if course code already exists on database. <br/> Aborting process.");
		}

		return true;
	}


	public static function nameExists($db, $name) {
		try {
			$sql = "SELECT COUNT(" . self::DB_COLUMN_NAME . ") FROM `" . DB_NAME . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_NAME . "` = :name";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':name', $name, PDO::PARAM_STR);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if major name already exists on database. <br/> Aborting process.");
		}

		return true;
	}


	public static function insert($db, $code, $name) {
		try {
			$query = "INSERT INTO `" . DB_NAME . "`.`" . self::DB_TABLE . "` (`" . self::DB_COLUMN_CODE .
				"`, `" . self::DB_COLUMN_NAME . "`)
				VALUES(
					:code,
					:name
				)";

			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':code', $code, PDO::PARAM_STR);
			$query->bindParam(':name', $name, PDO::PARAM_STR);
			$query->execute();
			return true;
		} catch (Exception $e) {
			throw new Exception("Could not insert major into database.");
		}
	}

	public static function idExists($db, $id) {
		try {

			$query = "SELECT COUNT(`" . self::DB_COLUMN_ID . "`)  FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
            WHERE `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "` = :id";

			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':id', $id);
			$query->execute();

			if ($query->fetchColumn() === 0) return false;

		} catch (Exception $e) {
			throw new Exception("Could not connect to database.");
		}

		return true;

	}

}