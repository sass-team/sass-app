<?php

/**
 * Created by PhpStorm.
 * User: Rizart
 * Date: 8/24/14
 * Time: 12:38 AM
 */
class InstructorFetcher extends Person
{
	const DB_TABLE = "instructor";
	const DB_ID = "id";
	const DB_FIRST_NAME = "f_name";
	const DB_LAST_NAME = "l_name";
	const DB_EMAIL = "email";

	/**
	 * @return mixed
	 */
	public static function retrieve($db) {
		$query = "SELECT `" . self::DB_ID . "`, `" . self::DB_EMAIL . "`, `" . self::DB_FIRST_NAME . "`, `" .
			self::DB_LAST_NAME . "` FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "`";
		$query = $db->getConnection()->prepare($query);

		try {
			$query->execute();
			$rows = $query->fetchAll(PDO::FETCH_ASSOC);

			return $rows;
		} catch (PDOException $e) {
			throw new Exception("Something terrible happened. Could not retrieve users data from database.: ");
		} // end catch
	}

	public static function create($db, $firstName, $lastName, $email, $courses) {
		self::validateName($firstName);
		self::validateName($lastName);
		self::validateEmail($db, $email, self::DB_TABLE);

		// validate courses -- only numbers
		//$this->validate_user_major($user_major_ext);
		//$this->validate_teaching_course($teaching_courses);

		try {
			$query = "INSERT INTO `" . DB_NAME . "`.`" . self::DB_TABLE . "` (`f_name`, `l_name`, `email`) VALUES
			(:firstName, :lastName, :email)";

			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':firstName', $firstName, PDO::PARAM_STR);
			$query->bindParam(':lastName', $lastName, PDO::PARAM_STR);
			$query->bindParam(':email', $email, PDO::PARAM_STR);
			$query->execute();
			return true;
		} catch (Exception $e) {
			throw new Exception("Could not insert user into database.");
		}
	}


} 