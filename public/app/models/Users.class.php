<?php

/**
 * Created by PhpStorm.
 * User: Rizart Dokollari
 * Date: 5/29/14
 * Time: 1:31 PM
 */
class Users {

	private $dbConnection;

	/**
	 * Constructor
	 * @param $database
	 */
	public function __construct($dbConnection) {
		$this->setDbConnection($dbConnection);
	}

	/**
	 * Verifies given credentials are correct. If login successfuly, returns true
	 * else return the error message.
	 *
	 * Dependancies:
	 * require_once ROOT_PATH . "inc/models/bcrypt.php";
	 * $bcrypt = new Bcrypt(12);
	 *
	 * @param $email $email of user
	 * @param $password $password of user
	 *
	 * @return bool|string
	 */
	public function login($email, $password) {

		if (empty($email) === true || empty($password) === true) {
			throw new Exception('Sorry, but we need both your email and password.');
		} else if ($this->email_exists($email) === false) {
			throw new Exception('Sorry that email doesn\'t exists.');
		}
		$query = "SELECT id, password, email FROM `" . DB_NAME . "`.user WHERE email = :email";
		$query = $this->dbConnection->prepare($query);
		$query->bindParam(':email', $email);

		try {

			$query->execute();
			$data = $query->fetch();
			$hash_password = $data['password'];

			// using the verify method to compare the password with the stored hashed password.
			if (!password_verify($password, $hash_password)) {
				throw new Exception('Sorry, that email/password is invalid.');
			}

			return $data['id'];
		} catch (PDOException $e) {
			// "Sorry could not connect to the database."
			throw new Exception("Sorry could not connect to the database: ");
		}
	} // end __construct



	/**
	 * @return mixed
	 */
	public function getDbConnection() {
		return $this->dbConnection;
	}

	/**
	 * @param mixed $dbConnection
	 */
	public function setDbConnection($dbConnection) {
		$this->dbConnection = $dbConnection;
	}

	public function getAll() {

	}







	public function validate_teaching_course($teaching_courses) {

	}
} 