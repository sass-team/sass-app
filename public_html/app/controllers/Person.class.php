<?php

/**
 * Created by PhpStorm.
 * User: Rizart
 * Date: 8/23/14
 * Time: 3:38 PM
 */
abstract class Person
{

	private  $id, $firstName, $lastName, $email, $mobileNum;

	public function __construct($id, $firstName, $lastName, $email, $mobileNum) {
		$this->setId($id);
		$this->setFirstName($firstName);
		$this->setLastName($lastName);
		$this->setEmail($email);
		$this->setMobileNum($mobileNum);
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param mixed $firstName
	 */
	public function setFirstName($firstName) {
		$this->firstName = $firstName;
	}

	/**
	 * @param mixed $lastName
	 */
	public function setLastName($lastName) {
		$this->lastName = $lastName;
	}

	/**
	 * @param mixed $email
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * @param mixed $mobileNum
	 */
	public function setMobileNum($mobileNum) {
		$this->mobileNum = $mobileNum;
	}

	/**
	 * @param $name
	 * @throws Exception
	 */
	public static function validateName($name) {
		if (!preg_match('/^[A-Za-z\\ ]{1,35}$/', $name)) {
			throw new Exception("Please enter a first/last name containing only letters of minimum length 1-35.");
		}
	}

	public static function validateNewEmail($newEmail, $table) {
		if (!isset($newEmail) || empty($newEmail)) {
			throw new Exception("Email is required");
		}
		if (filter_var($newEmail, FILTER_VALIDATE_EMAIL) === false) {
			throw new Exception("Please enter a valid email address");
		} else if (self::emailExists($newEmail, $table)) {
			throw new Exception('That email already exists. Please use another one.');
		} // end else if
	}

	/**
	 * Verifies a user with given email exists. returns true if found; else false
	 *
	 * @param $email
	 * @param $table
	 * @throws Exception
	 * @internal param $db
	 */
	public static function emailExists($email, $table) {
		$email = trim($email);
		$query = "SELECT COUNT(id) FROM `" . App::$dsn[App::DB_NAME] . "`.`" . $table . "` WHERE email = :email";

		$dbConnection = DatabaseManager::getConnection();
		$dbConnection = $dbConnection->prepare($query);
		$dbConnection->bindParam(':email', $email, PDO::PARAM_STR);

		try {
			$dbConnection->execute();
			$rows = $dbConnection->fetchColumn();

			if ($rows == 1) {
				return true;
			} else {
				return false;
			} // end else if

		} catch (PDOException $e) {
			throw new Exception("Something terrible happened. Could not access database.");
		} // end catch
	}

	public static function validateExistingEmail($newEmail, $table) {
		if (!isset($newEmail) || empty($newEmail)) {
			throw new Exception("Email is required");
		}
		if (filter_var($newEmail, FILTER_VALIDATE_EMAIL) === false) {
			throw new Exception("Please enter a valid email address");
		} else if (!self::emailExists($newEmail, $table)) {
			throw new Exception('Email was not found on database.');
		} // end else if
	} // end function get_data

	/**
	 * @return mixed
	 */
	public function getDb() {
		return $this->db;
	}


	/**
	 * @return mixed
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @return mixed
	 */
	public function getFirstName() {
		return $this->firstName;
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getLastName() {
		return $this->lastName;
	}

	/**
	 * @return mixed
	 */
	public function getMobileNum() {
		return $this->mobileNum;
	} // end __construct

} 