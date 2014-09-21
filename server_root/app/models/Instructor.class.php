<?php

/**
 * Created by PhpStorm.
 * User: Rizart
 * Date: 9/2/2014
 * Time: 10:50 AM
 */
class Instructor
{


	public static function create($db, $firstName, $lastName) {
		$firstName = self::validateName($db, $firstName);
		$lastName = self::validateName($db, $lastName);
		InstructorFetcher::create($db, $firstName, $lastName);
	}

	public static function  validateName($db, $name) {
		$name = trim($name);
		if (!preg_match("/^[a-zA-Z\\ ]{1,50}$/", $name)) {
			throw new Exception("Instructor name can only contain <a href='http://www.regular-expressions.info/shorthand.html'
            target='_blank'>word characters</a> and spaces of length 1-50");
		}

		return $name;
	}

	public static function update($db, $instructorId, $newFirstName, $newLastName) {
		$newLastName = self::validateName($db, $newLastName);
		$instructorId = self::validateId($db, $instructorId);
	}

	public static function validateId($db, $id) {
		if (is_null($id)) throw new Exception("Instructor is required.");

		if (!preg_match("/^[0-9]+$/", $id)) {
			throw new Exception("Data has been tempered. Aborting process.");
		}

		if (!InstructorFetcher::idExists($db, $id)) {
			// TODO: sent email to developer relevant to this error.
			throw new Exception("Either something went wrong with a database query, or you're trying to hack this app. In either case, the developers were just notified about this.");
		}
	}

	public static function validateIds($db, $ids) {
		foreach ($ids as $id) {
			self::validateId($db, $id);
		}
	}

	public static function updateFname($db, $id, $newFirstName) {
		$newFirstName = self::validateName($db, $newFirstName);
		InstructorFetcher::updateFname($db, $id, $newFirstName);
	}

	public static function updateLname($db, $id, $newLastName) {
		$newLastName = self::validateName($db, $newLastName);
		InstructorFetcher::updateLname($db, $id, $newLastName);
	}

	public static function delete($db, $id) {
		self::validateId($db, $id);
		InstructorFetcher::delete($db, $id);
	}

} 

