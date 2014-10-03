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
 * @since 7/21/14.
 */
class CourseFetcher
{

	const DB_TABLE = "course";
	const DB_COLUMN_CODE = "code";
	const DB_COLUMN_NAME = "name";
	const DB_COLUMN_ID = "id";
	const DB_COLUMN_EMAIL = "email";


	public static function retrieveAll($db) {
		date_default_timezone_set('Europe/Athens');

		$query =
			"SELECT `" . self::DB_COLUMN_CODE . "`, `" . self::DB_COLUMN_NAME . "`, `" . self::DB_COLUMN_ID . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "` order by `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "` desc";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve courses data from database.");
		}
	}

	public static function retrieveForTerm($db, $termId) {
		date_default_timezone_set('Europe/Athens');

		$query =
			"SELECT DISTINCT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_CODE . "`, `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_NAME . "`, `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
			INNER JOIN  `" . DB_NAME . "`.`" . Tutor_has_course_has_termFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . Tutor_has_course_has_termFetcher::DB_TABLE . "`.`" . Tutor_has_course_has_termFetcher::DB_COLUMN_COURSE_ID . "`  = `" .
			CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_ID . "`
			WHERE `" . Tutor_has_course_has_termFetcher::DB_TABLE . "`.`" . Tutor_has_course_has_termFetcher::DB_COLUMN_TERM_ID . "` = :term_id
			ORDER BY`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_NAME . "` ASC";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':term_id', $termId, PDO::PARAM_INT);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve courses data from database.");
		}
	}

	public static function retrieveTutors($db, $courseId) {

		$query =
			"SELECT DISTINCT `" . UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`, `" . UserFetcher::DB_TABLE
			. "`.`" . UserFetcher::DB_COLUMN_FIRST_NAME . "`, `" . UserFetcher::DB_TABLE . "`.`" .
			UserFetcher::DB_COLUMN_LAST_NAME . "`
			FROM `" . DB_NAME . "`.`" . UserFetcher::DB_TABLE . "`
			INNER JOIN  `" . DB_NAME . "`.`" . Tutor_has_course_has_termFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . Tutor_has_course_has_termFetcher::DB_TABLE . "`.`" . Tutor_has_course_has_termFetcher::DB_COLUMN_TUTOR_USER_ID . "`  = `" .
			UserFetcher::DB_TABLE . "`.`" . UserFetcher::DB_COLUMN_ID . "`
			INNER JOIN  `" . DB_NAME . "`.`" . CourseFetcher::DB_TABLE . "`
			ON `" . DB_NAME . "`.`" . Tutor_has_course_has_termFetcher::DB_TABLE . "`.`" . Tutor_has_course_has_termFetcher::DB_COLUMN_COURSE_ID . "`  = `" .
			CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_ID . "`
			WHERE `" . CourseFetcher::DB_TABLE . "`.`" . CourseFetcher::DB_COLUMN_ID . "` = :courseId";
		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':courseId', $courseId, PDO::PARAM_INT);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve data from database.");
		}
	}

	public static function retrieveSingle($db, $id) {
		$query = "SELECT `" . self::DB_COLUMN_CODE . "`, `" . self::DB_COLUMN_NAME . "`, `" . self::DB_COLUMN_ID . "`
		FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`
		WHERE `" . self::DB_COLUMN_ID . "`=:id";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);

			$query->execute();
			return $query->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Something terrible happened . Could not retrieve tutor data from database .: ");
		} // end catch
	}


	public static function courseExists($db, $id) {

		$query = "SELECT COUNT(`" . self::DB_COLUMN_ID . "`) FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "` WHERE
        `" . self::DB_COLUMN_ID . "`= :id";

		$query = $db->getConnection()->prepare($query);
		$query->bindParam(':id', $id, PDO::PARAM_STR);

		try {
			$query->execute();
			$rows = $query->fetchColumn();

			if ($rows == 1) return true;
		} catch (PDOException $e) {
			throw new Exception("Something terrible happened. Could not access database.");
		} // end catch

		return false;
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
			throw new Exception("Could not insert course into database.");
		}
	}


	public static function updateName($db, $id, $newName) {
		$newName = trim($newName);

		$query = "UPDATE `" . DB_NAME . "`.`" . self::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_NAME . "`= :newName
					WHERE `id`= :id";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->bindParam(':newName', $newName, PDO::PARAM_STR);
			$query->execute();

			return true;
		} catch (Exception $e) {
			throw new Exception("Something terrible happened. Could not update course name");
		}
	}

	public static function updateCode($db, $id, $newCode) {
		$newCode = trim($newCode);

		$query = "UPDATE `" . DB_NAME . "`.`" . self::DB_TABLE . "`
					SET	`" . self::DB_COLUMN_CODE . "`= :newCode
					WHERE `id`= :id";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->bindParam(':newCode', $newCode, PDO::PARAM_STR);
			$query->execute();

			return true;
		} catch (Exception $e) {
			throw new Exception("Something terrible happened. Could not update course code");
		}

	}

	public static function codeExists($db, $courseCode) {
		try {
			$sql = "SELECT COUNT(" . self::DB_COLUMN_CODE . ") FROM `" . DB_NAME . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_CODE . "` = :courseCode";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if course id already exists on database. <br/> Aborting process.");
		}

		return true;
	}


	public static function idExists($db, $id) {
		try {
			$sql = "SELECT COUNT(" . self::DB_COLUMN_ID . ") FROM `" . DB_NAME . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_ID . "` = :id";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->execute();

			if ($query->fetchColumn() === 0) return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if course code already exists on database. <br/> Aborting process.");
		}

		return true;
	}


	public static function nameExists($db, $courseName) {
		try {
			$sql = "SELECT COUNT(" . self::DB_COLUMN_NAME . ") FROM `" . DB_NAME . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_NAME . "` = :courseName";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':courseName', $courseName, PDO::PARAM_STR);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if course name already exists on database. <br/> Aborting process.");
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
			throw new Exception("Could not delete course from database.");
		}
	}


}