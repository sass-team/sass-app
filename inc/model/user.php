<?php

/**
 * Class User will contain prototype for users; tutors, secretary and admin.
 * Log In, Log Out.
 */
class User {

	// connection to db.
	private $db;

	/**
	 * Constructor
	 * @param $database
	 */
	public function __construct($database) {
		$this->db = $database;
	} // end __construct


	/**
	 * Verifies given credentials are correct. If login successfuly, returns true
	 * else return the error message.
	 *
	 * Dependancies:
	 * require_once ROOT_PATH . "inc/model/bcrypt.php";
	 * $bcrypt = new Bcrypt(12);
	 *
	 * @param $email $email of user
	 * @param $password $password of user
	 *
	 * @return bool|string
	 */
	public function login($email, $password) {

		if (empty($email) === true || empty($password) === true) {
			return 'Sorry, but we need both your email and password.';
		} else if ($this->email_exists($email) === false) {
			return 'Sorry that email doesn\'t exists.';
		}
		global $bcrypt; // global bcry variable
		$query = "SELECT password, email FROM user WHERE email = :email";
		$query = $this->db->prepare($query);
		$query->bindParam(':email', $email);

		try {

			$query->execute();
			$data = $query->fetch();
			$stored_password = $data['password'];

			// using the verify method to compare the password with the stored hashed password.
			if ($bcrypt->verify($password, $stored_password) === true) {
				return true;
			} else {
				return 'Sorry, that email/password is invalid';
			}

		} catch (PDOException $e) {
			die($e->getMessage());
		}
	}// end function login

	/**
	 * Verifies a user with given email exists.
	 * returns true if found; else false
	 *
	 * @param $email $email given email
	 * @return bool true if found; else false
	 */
	public function email_exists($email) {
		$email = trim($email);
		$query = "SELECT COUNT(`id`) FROM user WHERE `email`= ?";
		$query = $this->db->prepare($query);
		$query->bindValue(1, $email);

		try {
			$query->execute();
			$rows = $query->fetchColumn();

			if ($rows == 1) {
				return true;
			} else {
				return false;
			} // end else if

		} catch (PDOException $e) {
			die($e->getMessage());
		} // end catch
	} // end function user_exists


	/**
	 * Returns all information of a user given his email.
	 * @param $email $email of user
	 * @return mixed If
	 */
	function get_data($email) {
		$query = "SELECT * FROM user
	      LEFT OUTER JOIN user_types ON user.user_types_id = user_types.id
	      LEFT OUTER JOIN major ON user.major_id = major.id
	      WHERE email = ?";
		$query = $this->db->prepare($query);
		$query->bindValue(1, $email);

		try {
			$query->execute();
			return $query->fetch();
		} catch (PDOException $e) {
			die($e->getMessage());
		} // end try
	} // end function get_data

}

?>
