<?php

/**
 * Class User will contain prototype for users; tutors, secretary and admin.
 * Log In, Log Out.
 */
abstract class User extends Person
{

	const ADMIN = 'admin';
	const TUTOR = 'tutor';
	const SECRETARY = 'secretary';
	const ACTIVE_STRING = 'activateAccount';
	const DEACTIVE_STRING = 'deactivateAccount';
	const ACTIVE_STATUS = 1;
	const DEACTIVE_STATUS = 0;

	// representation of database info
	const DB_TABLE = "user";
	const DB_MOBILE_NUM = "mobile";
	const DB_FIRST_NAME = "f_name";
	const DB_LAST_NAME = "l_name";
	const DB_PROFILE_DESCRIPTION = "profile_description";

	private $avatarImgLoc;
	private $profileDescription;
	private $dateAccountCreated;
	private $userType;
	private $accountActiveStatus;


	/**
	 * Constructor
	 * @param $database
	 */
	public function __construct($db, $id, $firstName, $lastName, $email, $mobileNum, $avatarImgLoc, $profileDescription, $dateAccountCreated, $userType, $accountActiveStatus) {
		parent::__construct($db, $id, $firstName, $lastName, $email, $mobileNum);

		$this->setAvatarImgLoc($avatarImgLoc);
		$this->setProfileDescription($profileDescription);
		$this->setDateAccountCreated($dateAccountCreated);
		$this->setUserType($userType);
		$this->setActive($accountActiveStatus);
	}

	/**
	 * @param mixed $avatarImgLoc
	 */
	private function setAvatarImgLoc($avatarImgLoc) {
		$this->avatarImgLoc = $avatarImgLoc;
	}

	/**
	 * @param mixed $profileDescription
	 */
	private function setProfileDescription($profileDescription) {
		$this->profileDescription = $profileDescription;
	} // end __construct

	/**
	 * @param mixed $dateAccountCreated
	 */
	private function setDateAccountCreated($dateAccountCreated) {
		$this->dateAccountCreated = $dateAccountCreated;
	}

	/**
	 * @param mixed $userType
	 */
	private function setUserType($userType) {
		$this->userType = $userType;
	}

	/**
	 * @param mixed $active
	 */
	public function setActive($active) {
		$this->active = $active;
	}

	/**
	 * Returns all information of a user given his email.
	 * @param $id $email of user
	 * @return mixed If
	 */
	public static function  retrieve($db, $id) {
		$query = "SELECT user.email, user.id, user.`f_name`, user.`l_name`, user.`img_loc`,
						user.date, user.`profile_description`, user.mobile, user_types.type, user.active
					FROM `" . DB_NAME . "`.user
						LEFT OUTER JOIN user_types ON user.`user_types_id` = `user_types`.id
					WHERE user.id = :id";

		$query = $db->getConnection()->prepare($query);
		$query->bindValue(':id', $id, PDO::PARAM_INT);

		try {
			$query->execute();
			return $query->fetch();
		} catch (PDOException $e) {
			throw new Exception("Something terrible happened. Could not retrieve database." . $e->getMessage());
		} // end try
	}

	public static function updateActiveStatus($db, $id, $newStatus, $oldStatus) {
		$oldStatus = $oldStatus == 1 ? self::ACTIVE_STRING : self::DEACTIVE_STRING;

		if ((strcmp($newStatus, $oldStatus) === 0) || (strcmp($newStatus, self::ACTIVE_STRING) !== 0 && strcmp($newStatus, self::DEACTIVE_STRING))) {
			throw new Exception("Tampered data detected. Aborting.");
		}

		$accountStatus = strcmp($newStatus, self::ACTIVE_STRING) === 0 ? self::ACTIVE_STATUS : self::DEACTIVE_STATUS;

		try {
			$query = "UPDATE `" . DB_NAME . "`.`user` SET `active`= :accountStatus WHERE `id`=:id";
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':accountStatus', $accountStatus, PDO::PARAM_INT);
			$query->bindParam(':id', $id, PDO::PARAM_INT);

			$query->execute();

			return true;
		} catch (PDOException $e) {
			throw new Exception("Something terrible happened. Could not retrieve users data from database.: " . $e->getMessage());
		} // end catch
	}

	static function updateProfile($db, $id, $firstName, $lastName, $prevMobileNum, $newMobileNum, $description) {
		$firstName = trim($firstName);
		$lastName = trim($lastName);
		$newMobileNum = trim($newMobileNum);
		$description = trim($description);

		$isProfileDataCorrect = self::isProfileDataCorrect($firstName, $lastName,
			 $newMobileNum);

		if ($isProfileDataCorrect !== true) {
			throw new Exception(implode("<br>", $isProfileDataCorrect)); // the array of errors messages
		}


		$query = "UPDATE `" . DB_NAME . "`.user
					SET `f_name`= :first_name, `l_name`= :last_name, `mobile`= :mobile,
						`profile_description`= :profile_description
						WHERE `id`= :id";
		try {
			$query = $db->getConnection()->prepare($query);

			$query->bindParam(':first_name', $firstName, PDO::PARAM_STR);
			$query->bindParam(':last_name', $lastName, PDO::PARAM_STR);
			$query->bindParam(':mobile', $newMobileNum, PDO::PARAM_INT);
			$query->bindParam(':profile_description', $description, PDO::PARAM_STR);
			$query->bindParam(':id', $id, PDO::PARAM_INT);

			$query->execute();

			return true;
		} catch (Exception $e) {
			throw new Exception("Something terrible happened. Could not update profile." . $e->getMessage());
		}
	}

	public static function updateName($db, $id, $column, $newFirstName) {
		$newFirstName = trim($newFirstName);

		if (!preg_match("/^[a-zA-Z]{1,35}$/", $newFirstName)) {
			throw new Exception('Names may contain only letters of size 1-35 letters.');
		}

		$query = "UPDATE `" . DB_NAME . "`.`" . self::DB_TABLE . "`
					SET `" . $column . "`= :newFirstName WHERE `id`= :id";
		try {
			$query = $db->getConnection()->prepare($query);

			$query->bindParam(':newFirstName', $newFirstName, PDO::PARAM_STR);
			$query->bindParam(':id', $id, PDO::PARAM_INT);

			$query->execute();

			return true;
		} catch (Exception $e) {
			throw new Exception("Something terrible happened. Could not update profile." . $e->getMessage());
		}
	}

//	static function isProfileDataCorrect($first_name, $last_name, $mobile_num) {
//		$errors = array();
//
//		if (!ctype_alpha($first_name)) {
//			$errors[] = 'First name may contain only letters.';
//		}
//
//		if (!ctype_alpha($last_name)) {
//			$errors[] = 'Last name may contain only letters.';
//		}
//

//
//		if (empty($errors)) {
//			return true;
//		} else {
//			return $errors;
//		}
//	}

	public static function updateProfileDescription($db, $id, $newProfileDescription) {

		if (!preg_match("/^[\\w\t\n\r .,\\-]{0,512}$/", $newProfileDescription)) {
			throw new Exception("Description can contain only <a href='http://www.regular-expressions.info/shorthand.html'
			target='_blank'>word characters</a>, spaces, carriage returns, line feeds and special characters <strong>.,-2</strong> of max size 512 characters.");
		}

		$query = "UPDATE `" . DB_NAME . "`.`" . self::DB_TABLE . "`
					SET `" . self::DB_PROFILE_DESCRIPTION . "`= :newProfileDescription WHERE `id`= :id";
		try {
			$query = $db->getConnection()->prepare($query);

			$query->bindParam(':newProfileDescription', $newProfileDescription, PDO::PARAM_STR);
			$query->bindParam(':id', $id, PDO::PARAM_INT);

			$query->execute();

			return true;
		} catch (Exception $e) {
			throw new Exception("Something terrible happened. Could not update profile description.");
		}
	}

	public static function updateMobileNumber($db, $id, $newMobileNum) {
		if (!preg_match('/^[0-9]{10}$/', $newMobileNum)) {
			throw new Exception('Mobile number should contain only digits of total length 10');
		}

		if (self::existsMobileNum($db, $newMobileNum)) {
			throw new Exception("Mobile entered number already exists in database."); // the array of errors messages
		}

		try {

			$query = "UPDATE `" . DB_NAME . "`.`" . self::DB_TABLE . "`
					SET `mobile`= :mobile WHERE `id`= :id";
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':mobile', $newMobileNum, PDO::PARAM_INT);
			$query->bindParam(':id', $id, PDO::PARAM_INT);

			$query->execute();

			return true;
		} catch (Exception $e) {
			throw new Exception("Something terrible happened. Could not update profile." . $e->getMessage());
		}
	}

	public static function existsMobileNum($db, $newMobileNum) {

		try {
			$sql = "SELECT COUNT(" . self::DB_MOBILE_NUM . ") FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "` WHERE `" . self::DB_MOBILE_NUM . "` = :mobileNum";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':mobileNum', $newMobileNum, PDO::PARAM_INT);
			$query->execute();
			if ($query->fetchColumn() === '0') {
				return false;
			}

			return true;
		} catch (Exception $e) {
			throw new Exception("Could not check if new mobile number already exists on database.");
		}
	}

	public static function updatePassword($db, $id, $oldPassword, $newPassword1, $newPassword2) {

		if ($newPassword1 !== $newPassword2) {
			throw new Exception("There was a mismatch with the new passwords");
		}

		if (!self::validatePassword($newPassword1)) {
			throw new Exception("Password should:
			<ul>
				<li>Contain at least one capitalized letter. [A-Z]</li>
				<li>Contain at least one lowercase letter. [a-z]</li>
				<li>Contain at least one special character. [!@#$%^&*()\-_=+{};:,<.>]</li>
				<li>Be at least 8 characters long</li>
			</ul> ");
		}

		$old_password_hashed = self::getHashedPassword($db, $id);
		if (!password_verify($oldPassword, $old_password_hashed)) {
			throw new Exception("Sorry, the old password is incorrect.");
		}

		try {
			$new_password_hashed = password_hash($newPassword1, PASSWORD_DEFAULT);

			$query = "UPDATE `" . DB_NAME . "`.`user` SET `password`= :password WHERE `id`= :id";
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':id', $id);
			$query->bindParam(':password', $new_password_hashed);

			$query->execute();
		} catch (Exception $e) {
			throw new Exception("Could not connect to database.");
		}
	}

	public static function validatePassword($password) {
		$r1 = '/[A-Z]/'; //Uppercase
		$r2 = '/[a-z]/'; //lowercase
		$r3 = '/[!@#$%^&*()\-_=+{};:,<.>]/'; // whatever you mean by 'special char'
		$r4 = '/[0-9]/'; //numbers

		if (preg_match_all($r1, $password, $o) < 2) return FALSE;

		if (preg_match_all($r2, $password, $o) < 2) return FALSE;

		if (preg_match_all($r3, $password, $o) < 2) return FALSE;

		if (preg_match_all($r4, $password, $o) < 2) return FALSE;

		if (strlen($password) < 8) return FALSE;

		return TRUE;

	}

	public static function getHashedPassword($db, $id) {
		$query = "SELECT password FROM `" . DB_NAME . "`.user WHERE id = :id";
		$query = $db->getConnection()->prepare($query);
		$query->bindParam(':id', $id);

		try {

			$query->execute();
			$data = $query->fetch();
			$hash_password = $data['password'];
			return $hash_password;
		} catch (Exception $e) {
			throw new Exception("Could not connect to database.");
		}
	}

	/**
	 * @param $name
	 * @throws Exception
	 */
	public static function validateName($name) {
		if (!preg_match('/^[A-Za-z]+$/', $name)) {
			throw new Exception("Please enter a first/last name containing only letters of minimum length 1.");
		}
	} // end __construct

	public static function validateEmail($db, $email) {
		if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
			throw new Exception("Please enter a valid email address");
		} else if (self::emailExists($db, $email)) {
			throw new Exception('That email already exists. Please use another one.');
		} // end else if
	}

	/**
	 * Verifies a user with given email exists.
	 * returns true if found; else false
	 *
	 * @param $email $email given email
	 * @return bool true if found; else false
	 */
	public static function emailExists($db, $email) {
		$email = trim($email);
		$query = "SELECT COUNT(id) FROM `" . DB_NAME . "`.user WHERE email = :email";

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
	}

	public static function validateUserMajor($db, $user_major_ext) {
		if ($user_major_ext === "null") {
			return true;
		}
		$query = "SELECT COUNT(1)  FROM `" . DB_NAME . "`.major WHERE major.extension=':extension'";
		$query = $db->getDbConnection()->prepare($query);
		$query->bindParam(':extension', $user_major_ext);

		try {

			$query->execute();
			$data = $query->fetch();
		} catch (Exception $e) {
			throw new Exception("Could not connect to database.");
		}

		if ($data === 1) {
			return true;
		} else {
			// TODO: sent email to developer relavant to this error.
			throw new Exception("Either something went wrong with a database query, or you're trying to hack this app. In either case, the developers were just notified about this.");
		}
	}

	public static function retrieveAll($db) {
		$query = "SELECT user.id, user.f_name, user.l_name, user.img_loc, user.profile_description, user.date, user.mobile, user.email, user_types.type
		         FROM `" . DB_NAME . "`.user
						LEFT OUTER JOIN user_types ON user.`user_types_id` = `user_types`.id";
		$query = $db->getConnection()->prepare($query);

		try {
			$query->execute();
			$rows = $query->fetchAll();

			return $rows;
		} catch (PDOException $e) {
			throw new Exception("Something terrible happened. Could not retrieve users data from database.: " . $e->getMessage());
		} // end catch
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
	public static function login($db, $email, $password) {

		if (empty($email) === true || empty($password) === true) {
			throw new Exception('Sorry, but we need both your email and password.');
		} else if (self::emailExists($db, $email) === false) {
			throw new Exception('Sorry that email doesn\'t exists.');
		}
		$query = "SELECT id, active, password, email FROM `" . DB_NAME . "`.user WHERE email = :email";
		$query = $db->getConnection()->prepare($query);
		$query->bindParam(':email', $email);

		try {

			$query->execute();
			$data = $query->fetch();
			$hash_password = $data['password'];

			// using the verify method to compare the password with the stored hashed password.
			if (!password_verify($password, $hash_password)) {
				throw new Exception('That email/password is invalid.');
			}

			if ($data['active'] == 0) {
				throw new Exception('Your account has been deactivated.');
			}
			return $data['id'];
		} catch (PDOException $e) {
			// "Sorry could not connect to the database."
			throw new Exception("Could not connect to the database: ");
		}
	} // end function get_data

	/**
	 * @return mixed
	 */
	public function getAccountActiveStatus() {
		return $this->accountActiveStatus;
	}

	/**
	 * @param mixed $accountActiveStatus
	 */
	public function setAccountActiveStatus($accountActiveStatus) {
		$this->accountActiveStatus = $accountActiveStatus;
	}

	/**
	 * @return mixed
	 */
	public function isActive() {
		return $this->active;
	}

	public function updateUserType($userType) {
		$this->validateUserType($userType);
		$id = $this->getId();

		try {
			$userTypeId = $this->fetchInfo("id", "user_types", "type", $userType);

			$query = "UPDATE  `" . DB_NAME . "`.`user` SET `user_types_id` = :userTypeId WHERE `id`= :id";

			$query = $this->getDb()->getConnection()->prepare($query);
			$query->bindParam(':userTypeId', $userTypeId, PDO::PARAM_INT);
			$query->bindParam(':id', $id, PDO::PARAM_INT);

			$query->execute();

			return true;
		} catch (Exception $e) {
			throw new Exception("Could not update user type.");
		}
	}

	public static function validateUserType($user_type) {
		switch ($user_type) {
			case self::TUTOR:
			case self::SECRETARY:
			case self::ADMIN:
				return true;
			default:
				throw new Exception('Incorrect user type.');
		}
	} // end getAllData

	/**
	 * Returns a single column from the next row of a result set or FALSE if there are no more rows.
	 *
	 * @param $what
	 * @param $field
	 * @param $value
	 * @return mixed
	 * @throws InvalidArgumentException
	 */
	public function fetchInfo($what, $field, $where, $value) {
		// I have only added few, but you can add more. However do not add 'password' even though the parameters will only be given by you and not the user, in our system.
		$allowed = array('id', 'username', 'f_name', 'l_name', 'email', 'COUNT(mobile)',
			 'mobile', 'user', 'gen_string', 'COUNT(gen_string)', 'COUNT(id)', 'img_loc', 'user_types', 'type');
		if (!in_array($what, $allowed, true) || !in_array($field, $allowed, true)) {
			throw new InvalidArgumentException;
		} else {
			try {
				$sql = "SELECT $what FROM `" . DB_NAME . "`.`" . $field . "` WHERE $where = ?";
				$query = $this->getDb()->getConnection()->prepare($sql);
				$query->bindValue(1, $value, PDO::PARAM_STR);
				$query->execute();
				return $query->fetchColumn();
				//return $sql;
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}

		}
	} // end getAllData

	public function updateAvatarImg($avatar_img_loc) {
		$id = $this->getId();

		try {

			$query = "UPDATE `" . DB_NAME . "`.user SET `img_loc`= :avatar_img WHERE `id`= :user_id";
			$query = $this->getDb()->getConnection()->prepare($query);
			$query->bindParam(':avatar_img', $avatar_img_loc, PDO::PARAM_STR);
			$query->bindParam(':user_id', $id, PDO::PARAM_INT);

			$query->execute();
			return true;
		} catch (PDOException $e) {
			throw new Exception("Something terrible happened. Could not update database.");
		} // end try catch
	}

	/**
	 * @return mixed
	 */
	public function getAvatarImgLoc() {
		return $this->avatarImgLoc;
	}

	/**
	 * @return mixed
	 */
	public function getDateAccountCreated() {
		return $this->dateAccountCreated;
	}

	/**
	 * @return mixed
	 */
	public function getProfileDescription() {
		return $this->profileDescription;
	}

	public function isAdmin() {
		return false;
	}

	public function isTutor() {
		return false;
	}

	public function isSecretary() {
		return false;
	}

	/**
	 * @return mixed
	 */
	public function getUserType() {
		return $this->userType;
	}

	/**
	 * @param mixed $users
	 */
	public function setUsers($users) {
		$this->users = $users;
	}
}

?>
