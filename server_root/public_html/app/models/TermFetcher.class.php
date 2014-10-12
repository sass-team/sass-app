<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) <year> <copyright holders>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * @author Rizart Dokollari
 * @author George Skarlatos
 * @since 9/15/2014
 */
class TermFetcher
{
	const DB_TABLE = "term";
	const DB_COLUMN_ID = "id";
	const DB_COLUMN_NAME = "name";
	const DB_COLUMN_START_DATE = "start_date";
	const DB_COLUMN_END_DATE = "end_date";


//m-d-Y h:i A
	public static function retrieveAllButCur($db) {
		$query =
			"SELECT `" . self::DB_COLUMN_ID . "` , `" . self::DB_COLUMN_NAME . "` , `" . self::DB_COLUMN_START_DATE . "`,
			 `" . self::DB_COLUMN_END_DATE . "`
			 FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			 WHERE (NOW() NOT BETWEEN `" . TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_START_DATE . "` AND `" .
			TermFetcher::DB_TABLE . "`.`" . TermFetcher::DB_COLUMN_END_DATE . "`)
			 order by `" .
			self::DB_TABLE . "`.`" . self::DB_COLUMN_START_DATE . "` DESC";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve terms data from database.");
		}
	}

	//m-d-Y h:i A
	public static function retrieveAll($db) {
		$query =
			"SELECT `" . self::DB_COLUMN_ID . "` , `" . self::DB_COLUMN_NAME . "` , `" . self::DB_COLUMN_START_DATE . "`,
			 `" . self::DB_COLUMN_END_DATE . "`
			 FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			order by `" .
			self::DB_TABLE . "`.`" . self::DB_COLUMN_START_DATE . "` DESC";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve terms data from database.");
		}
	}


	public static function retrieveCurrTerm($db) {
		$query =
			"SELECT `" . self::DB_COLUMN_ID . "` ,
					`" . self::DB_COLUMN_NAME . "`
			 	FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			 	WHERE CURRENT_TIMESTAMP() BETWEEN `" . self::DB_COLUMN_START_DATE . "` AND `" . self::DB_COLUMN_END_DATE . "`";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve current term from database.");
		}
	}


	public static function retrieveSingle($db, $id) {
		$query = "SELECT  `" . self::DB_COLUMN_ID . "` , `" . self::DB_COLUMN_NAME . "` , `" . self::DB_COLUMN_START_DATE
			. "`,		 `" . self::DB_COLUMN_END_DATE . "`
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_COLUMN_ID . "`=:id";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);

			$query->execute();
			return $query->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database .: ");
		} // end catch
	}

	public static function updateName($db, $id, $newName) {
		$query = "UPDATE `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_NAME . "`= :newName
					WHERE `id`= :id";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->bindParam(':newName', $newName, PDO::PARAM_STR);
			$query->execute();

			return true;
		} catch (Exception $e) {
			throw new Exception("Something terrible happened. Could not update term name");
		}
	}

	public static function  updateStartingDate($db, $id, $newStartingDate) {
		$query = "UPDATE `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_START_DATE . "`= :newName
					WHERE `id`= :id";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->bindParam(':newName', $newStartingDate, PDO::PARAM_STR);
			$query->execute();

			return true;
		} catch (Exception $e) {
			throw new Exception("Something terrible happened. Could not update starting date");
		}
	}


	public static function updateSingleColumn($db, $id, $column, $value, $valueType) {
		$query = "UPDATE `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
					SET	`" . $column . "`= :column
					WHERE `id`= :id";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->bindParam(':column', $value, $valueType);
			$query->execute();

			return true;
		} catch (Exception $e) {
			throw new Exception("Something went wrong. Could not update term table.");
		}
	}

	public static function insert($db, $name, $startDate, $endDate) {
		date_default_timezone_set('Europe/Athens');

		$startDate = $startDate->format(Dates::DATE_FORMAT_IN);
		$endDate = $endDate->format(Dates::DATE_FORMAT_IN);

		try {
			$query = "INSERT INTO `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "` (`" . self::DB_COLUMN_NAME .
				"`, `" . self::DB_COLUMN_START_DATE . "`, `" . self::DB_COLUMN_END_DATE . "`)
				VALUES(
					:name,
					:start_date,
					:end_date
				)";

			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':name', $name, PDO::PARAM_STR);
			$query->bindParam(':start_date', $startDate);
			$query->bindParam(':end_date', $endDate);
			$query->execute();
			return true;
		} catch (Exception $e) {
			throw new Exception("Could not insert term data into database.");
		}
	}

	public static function idExists($id) {
		try {
			$query = "SELECT COUNT(" . self::DB_COLUMN_ID . ") FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_ID . "` = :id";

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->execute();

			if ($query->fetchColumn() === 0) return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if term id already exists on database. <br/> Aborting process.");
		}

		return true;
	}


	public static function delete($db, $id) {
		try {
			$query = "DELETE FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_ID . "` = :id";
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->execute();
			return true;
		} catch (Exception $e) {
			throw new Exception("Could not delete term from database.");
		}
	}


	public static function existsName($db, $name) {
		try {
			$sql = "SELECT COUNT(" . self::DB_COLUMN_NAME . ") FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_NAME . "` = :name";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':name', $name, PDO::PARAM_STR);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if term name already exists on database. <br/> Aborting process.");
		}

		return true;

	}
} 