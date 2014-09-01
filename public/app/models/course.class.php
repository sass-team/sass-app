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
class Course
{

	const DB_TABLE = "course";
	const DB_CODE = "code";
	const DB_NAME = "name";
	const DB_ID = "id";

	public static function retrieveAll($db) {
		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_CODE . "` AS 'code', `" . self::DB_TABLE . "`.`" .
			   self::DB_CODE . "` AS  'name', `" . self::DB_TABLE . "`.`" . self::DB_ID . "`
			FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`, `" . DB_NAME . "`.`" . Major::TABLE_NAME . "`,
				`" . DB_NAME . "`.`" . Major::DB_TABLE_MAJOR_HAS_COURSES . "`
			WHERE `" . self::DB_TABLE . "`.`" . self::DB_ID . "` = `" . Major::DB_TABLE_MAJOR_HAS_COURSES . "`.course_id
				AND major.id = major_has_courses.major_id;
			ORDER BY major.extension";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve courses data from database.");
		}
	}

	public static function retrieveMajors($db) {

		$query = "SELECT major.extension AS 'Extension', major.name AS 'Name'
				FROM `" . DB_NAME . "`.major";
		try {
			$query = $db->getConnection()->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			throw new Exception("Could not retrieve majors data from database.");
		}
	}

	public function getCourses() {

		$query = "SELECT course.code AS 'Code', course.name AS 'Name', course.id
				FROM `" . DB_NAME . "`.course";
		try {
			$query = $this->db->prepare($query);
			$query->execute();

			return $query->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			throw new Exception("Could not retrieve courses data from database.");
		}
	}
} 