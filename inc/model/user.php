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

	function update_profile($first_name, $last_name, $mobile_num, $description, $img_loc, $email) {
		$first_name = trim($first_name);
		$last_name = trim($last_name);
		$mobile_num = trim($mobile_num);
		$description = trim($description);
		$img_loc = trim($img_loc);
		$email = trim($email);

		$is_profile_data_correct = $this->is_profile_data_correct($first_name, $last_name,
			$mobile_num);

		if ($is_profile_data_correct !== true) {
			return $is_profile_data_correct; // the array of errors messages
		}

		$query = "UPDATE `sass-ms_db`.`user`
					SET `f_name`= :first_name, `l_name`= :last_name, `mobile`= :mobile,
						`profile_description`= :profile_description
						WHERE `email`= :email";
		echo $query;
		try {
			$query = $this->db->prepare($query);

			$query->bindParam(':first_name', $first_name, PDO::PARAM_STR);
			$query->bindParam(':last_name', $last_name, PDO::PARAM_STR);
			$query->bindParam(':mobile', $mobile_num, PDO::PARAM_STR);
			$query->bindParam(':profile_description', $description, PDO::PARAM_STR);
			$query->bindParam(':email', $email, PDO::PARAM_STR);

			$query->execute();

			return true;
		} catch (PDOException $pe) {
			echo "Something terrible happened. Could not update database.";
			exit();
		}
	}

	function is_profile_data_correct($first_name, $last_name, $mobile_num) {
		$errors = array();

		if (!ctype_alpha($first_name)) {
			$errors[] = 'First name may contain only letters.';
		}

		if (!ctype_alpha($last_name)) {
			$errors[] = 'Last name may contain only letters.';
		}

		if (!preg_match('/^[0-9]{10}$/', $mobile_num)) {
			$errors[] = 'Mobile number should contain only digits of total length 10';
		}

		if (empty($errors)) {
			return true;
		} else {
			return $errors;
		}
	}
}

?>
