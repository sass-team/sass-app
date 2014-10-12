<?php

/**
 * Created by PhpStorm.
 * User: Rizart
 * Date: 9/2/2014
 * Time: 8:28 AM
 */
class Course
{
	const NULL = "null";

	public static function validateSingle($courseId) {
		if (!isset($courseId) || empty($courseId)) throw new Exception("Data has been tempered. Aborting process.");
		if (strcmp($courseId, self::NULL) === 0) throw new Exception("Course is required.");
		if (!CourseFetcher::courseExists($courseId)) throw new Exception("Data has been tempered. Aborting process");

		return true;
	}

	public static function getForTerm($termId) {
		Term::validateId($termId);
		return CourseFetcher::retrieveForTerm($termId);
	}

	public static function create($courseCode, $courseName) {
		$courseCode = self::validateCode($courseCode);
		$courseName = self::validateName($courseName);
		CourseFetcher::insert($courseCode, $courseName);
	}

	public static function  validateCode($courseCode) {
		$courseCode = trim($courseCode);

		if (!preg_match("/^[A-Z0-9]{1,10}$/", $courseCode)) {
			throw new Exception("Course code can contain capital letters in the range of A-Z, numbers 0-9 and of length 1-10.");
		}

		if (CourseFetcher::codeExists($courseCode)) {
			throw new Exception("Course code already exists on database. Please insert a different one.");
		}

		return $courseCode;
	}

	public static function  validateName($courseName) {
		$courseName = trim($courseName);


		if (!preg_match("/^[\\w\\ ]{1,50}$/", $courseName)) {
			throw new Exception("Course name can only contain <a href='http://www.regular-expressions.info/shorthand.html'
            target='_blank'>word characters</a> and spaces of length 1-50");
		}

		if (CourseFetcher::nameExists($courseName)) {
			throw new Exception("Course name already exists on database. Please insert a different one.");
		}

		return $courseName;
	}

	public static function update( $courseId, $newCourseCode, $newCourseName) {
		$newCourseName = self::validateName($newCourseName);
		$courseId = self::validateId($courseId);
	}

	public static function validateId($id) {
		if (!preg_match('/^[0-9]+$/', $id) || (!CourseFetcher::idExists($id))) {
			throw new Exception("Data tempering detected.
			<br/>You&#39;re trying to hack this app.<br/>Developers are being notified about this.<br/>Expect Us.");
		}
	}

	public static function updateCode( $id, $newCode) {
		$newCode = self::validateCode($newCode);
		CourseFetcher::updateCode($id, $newCode);
	}

	public static function updateName( $id, $newName, $oldName) {
		if (strcmp($newName, $oldName) === 0) return false;

		$newName = self::validateName($newName);
		CourseFetcher::updateName($id, $newName);

		return true;
	}

	public static function delete($id) {
		self::validateId($id);
		if (!CourseFetcher::courseExists($id)) {
			throw new Exception("Could not retrieve course to be deleted from database. <br/>
                Maybe some other administrator just deleted it?");
		}

		CourseFetcher::delete($id);
	}

	public static function getTutors($courseId) {
		self::validateId($courseId);
		return CourseFetcher::retrieveTutors($courseId);
	}

	public static function get($courseId) {
		self::validateId($courseId);
		return CourseFetcher::retrieveSingle($courseId);
	}
}