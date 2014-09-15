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
	const DATE_FORMAT = "Y-m-d H:i:s";
//m-d-Y h:i A
	public static function retrieveAll($db) {
		$query =
			"SELECT `" . self::DB_COLUMN_ID . "` , `" . self::DB_COLUMN_NAME . "` , `" . self::DB_COLUMN_START_DATE . "`,
			 `" . self::DB_COLUMN_END_DATE . "` FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "` order by `" .
			self::DB_TABLE . "`.`" . self::DB_COLUMN_START_DATE . "` DESC";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve terms data from database.");
		}
	}

	public static function insert($db, $name, $startDate, $endDate) {
		date_default_timezone_set('Europe/Athens');

		$startDate = $startDate->format(self::DATE_FORMAT);
		$endDate = $endDate->format(self::DATE_FORMAT);

		try {
			$query = "INSERT INTO `" . DB_NAME . "`.`" . self::DB_TABLE . "` (`" . self::DB_COLUMN_NAME .
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

	public static function existsName($db, $name) {
		try {
			$sql = "SELECT COUNT(" . self::DB_COLUMN_NAME . ") FROM `" . DB_NAME . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_NAME . "` = :name";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':name', $name, PDO::PARAM_STR);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if course name already exists on database. <br/> Aborting process.");
		}

		return true;

	}
} 