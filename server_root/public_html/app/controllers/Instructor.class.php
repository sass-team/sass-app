<?php

/**
 * Created by PhpStorm.
 * User: Rizart
 * Date: 9/2/2014
 * Time: 10:50 AM
 */
class Instructor
{


	public static function create( $firstName, $lastName) {
		$firstName = self::validateName($firstName);
		$lastName = self::validateName($lastName);
		InstructorFetcher::create($firstName, $lastName);
	}

	public static function  validateName($name) {
		$name = trim($name);
		if (!preg_match("/^[a-zA-Z\\ ]{1,50}$/", $name)) {
			throw new Exception("Instructor name can only contain <a href='http://www.regular-expressions.info/shorthand.html'
            target='_blank'>word characters</a> and spaces of length 1-50");
		}

		return $name;
	}

	public static function update($instructorId, $newFirstName, $newLastName) {
		$newLastName = self::validateName($newLastName);
		$newLastName = self::validateName($newFirstName);
		$instructorId = self::validateId($instructorId);
	}

	public static function validateId($id) {
		if (is_null($id)) throw new Exception("Instructor is required.");

		if (!preg_match("/^[0-9]+$/", $id)) {
			throw new Exception("Data has been tempered. Aborting process.");
		}

		if (!InstructorFetcher::idExists($id)) {
			// TODO: sent email to developer relevant to this error.
			throw new Exception("Either something went wrong with a database query, or you're trying to hack this app. In either case, the developers were just notified about this.");
		}
	}

	public static function validateIds($ids) {
		foreach ($ids as $id) {
			self::validateId($id);
		}
	}

	public static function updateFname( $id, $newFirstName) {
		$newFirstName = self::validateName($newFirstName);
		InstructorFetcher::updateFname($id, $newFirstName);
	}

	public static function updateLname( $id, $newLastName) {
		$newLastName = self::validateName($newLastName);
		InstructorFetcher::updateLname($id, $newLastName);
	}

	public static function delete( $id) {
		self::validateId($id);
		InstructorFetcher::delete($id);
	}

} 

