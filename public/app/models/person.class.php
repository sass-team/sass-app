<?php

/**
 * Created by PhpStorm.
 * User: Rizart
 * Date: 8/23/14
 * Time: 3:38 PM
 */
abstract class Person
{

	private $db, $id, $firstName, $lastName, $email, $mobileNum;

	public function __construct($db, $id, $firstName, $lastName, $email, $mobileNum) {
		$this->setId($id);
		$this->setFirstName($firstName);
		$this->setLastName($lastName);
		$this->setEmail($email);
		$this->setMobileNum($mobileNum);
		$this->setDb($db);
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
		if (!preg_match('/^[A-Za-z]+$/', $name)) {
			throw new Exception("Please enter a first/last name containing only letters of minimum length 1.");
		}
	}

	public static function validateEmail($db, $newEmail, $table) {
		if (!isset($newEmail) || empty($newEmail)) {
			throw new Exception("Email is required");
		}
		if (filter_var($newEmail, FILTER_VALIDATE_EMAIL) === false) {
			throw new Exception("Please enter a valid email address");
		} else if (self::emailExists($db, $newEmail, $table)) {
			throw new Exception('That email already exists. Please use another one.');
		} // end else if
	}

	/**
	 * Verifies a user with given email exists. returns true if found; else false
	 *
	 * @param $db
	 * @param $email
	 * @param $table
	 * @throws Exception
	 */
	public static function emailExists($db, $email, $table) {
		$email = trim($email);
		$query = "SELECT COUNT(id) FROM `" . DB_NAME . "`.`" . $table . "` WHERE email = :email";

		$query = $db->getConnection()->prepare($query);
		$query->bindParam(':email', $email, PDO::PARAM_STR);

		try {
			$query->execute();
			$rows = $query->fetchColumn();

			if ($rows == 1) {
				return true;
			} else {
				return false;
			} // end else if

		} catch (PDOException $e) {
			throw new Exception("Something terrible happened. Could not access database.");
		} // end catch
	} // end function get_data

	public static function validateId($id) {
		if (!preg_match('/^[0-9]+$/', $id)) {
			throw new Exception("Data tempering detected.
			<br/>You&#39;re trying to hack this app.<br/>Developers are being notified about this.<br/>Expect Us.");
		}
	}

	/**
	 * @return mixed
	 */
	public function getDb() {
		return $this->db;
	}

	/**
	 * @param mixed $db
	 */
	public function setDb($db) {
		$this->db = $db;
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