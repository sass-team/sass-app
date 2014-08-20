<?php

/**
 * Class User will contain prototype for users; tutors, secretary and admin.
 * Log In, Log Out.
 */
abstract class User
{

	const ADMIN = 'admin';
	const TUTOR = 'tutor';
	const SECRETARY = 'secretary';
	private $id;
	private $firstName;
	private $lastName;
	private $avatarImgLoc;
	private $profileDescription;
	private $dateAccountCreated;
	private $userType;
	private $mobileNum;
	private $email;
	private $db;

	/**
	 * Constructor
	 * @param $database
	 */
	public function __construct($data, $db) {
		$this->setId($data['id']);
		$this->setFirstName($data['f_name']);
		$this->setLastName($data['l_name']);
		$this->setAvatarImgLoc($data['img_loc']);
		$this->setProfileDescription($data['profile_description']);
		$this->setDateAccountCreated($data['date']);
		$this->setMobileNum($data['mobile']);
		$this->setEmail($data['email']);

		// initialize tutor/secretary/admin class depending on type.
		$this->setUserType($data['type']);

		$this->setDb($db);
	}

	/**
	 * @param mixed $id
	 */
	private function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param mixed $firstName
	 */
	private function setFirstName($firstName) {
		$this->firstName = $firstName;
	}

	/**
	 * @param mixed $lastName
	 */
	private function setLastName($lastName) {
		$this->lastName = $lastName;
	} // end fetch_info

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
	}

	/**
	 * @param mixed $dateAccountCreated
	 */
	private function setDateAccountCreated($dateAccountCreated) {
		$this->dateAccountCreated = $dateAccountCreated;
	}

	/**
	 * @param mixed $mobileNum
	 */
	private function setMobileNum($mobileNum) {
		$this->mobileNum = $mobileNum;
	}

	/**
	 * @param mixed $email
	 */
	private function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * @param mixed $userType
	 */
	private function setUserType($userType) {
		$this->userType = $userType;
	} // end __construct

	function updateProfile($first_name, $last_name, $prevMobileNum, $new_mobile_num, $description) {
		$first_name = trim($first_name);
		$last_name = trim($last_name);
		$new_mobile_num = trim($new_mobile_num);
		$description = trim($description);
		$id = $this->getId();

		$isProfileDataCorrect = $this->isProfileDataCorrect($first_name, $last_name,
			 $new_mobile_num);

		if ($isProfileDataCorrect !== true) {
			throw new Exception(implode("<br>", $isProfileDataCorrect)); // the array of errors messages
		}


		if ($prevMobileNum !== $new_mobile_num && $this->fetchInfo('COUNT(mobile)', 'user', 'mobile', $new_mobile_num) != 0) {
			throw new Exception("Mobile entered number already exists in database."); // the array of errors messages
		}

		$query = "UPDATE `" . DB_NAME . "`.user
					SET `f_name`= :first_name, `l_name`= :last_name, `mobile`= :mobile,
						`profile_description`= :profile_description
						WHERE `id`= :id";
		try {
			$query = $this->getDb()->getConnection()->prepare($query);

			$query->bindParam(':first_name', $first_name, PDO::PARAM_STR);
			$query->bindParam(':last_name', $last_name, PDO::PARAM_STR);
			$query->bindParam(':mobile', $new_mobile_num, PDO::PARAM_INT);
			$query->bindParam(':profile_description', $description, PDO::PARAM_STR);
			$query->bindParam(':id', $id, PDO::PARAM_INT);

			$query->execute();

			return true;
		} catch (PDOException $pe) {
			throw new Exception("Something terrible happened. Could not update profile.");
		}
	}

	// end function login

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	function isProfileDataCorrect($first_name, $last_name, $mobile_num) {
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

	/**
	 * @return mixed
	 */
	public function getDb() {
		return $this->db;
	} // end function get_data

	/**
	 * @param mixed $db
	 */
	public function setDb($db) {
		$this->db = $db;
	}

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

	public function updatePassword($userId, $oldPassword, $newPassword1, $newPassword2) {

		$old_password_hashed = $this->getHashedPassword($userId);
		if (!password_verify($oldPassword, $old_password_hashed)) {
			throw new Exception("Sorry, the old password is incorrect.");
		}

		if ($newPassword1 !== $newPassword2) {
			throw new Exception("There was a mismatch with the new passwords");
		}

		try {
			$new_password_hashed = password_hash($newPassword1, PASSWORD_DEFAULT);

			$query = "UPDATE `" . DB_NAME . "`.`user` SET `password`= :password WHERE `id`= :id";
			$query = $this->getDb()->getConnection()->prepare($query);
			$query->bindParam(':id', $userId);
			$query->bindParam(':password', $new_password_hashed);

			$query->execute();
		} catch (Exception $e) {
			throw new Exception("Could not connect to database.");
		}
	}


	public function getHashedPassword($id) {
		$query = "SELECT password FROM `" . DB_NAME . "`.user WHERE id = :id";
		$query = $this->getDb()->getConnection()->prepare($query);
		$query->bindParam(':id', $id);

		try {

			$query->execute();
			$data = $query->fetch();
			$hash_password = $data['password'];
			return $hash_password;
		} catch (Exception $e) {
			throw new Exception("Could not connect to database.");
		}
	} // end getAllData


	/**
	 * @param $name
	 * @throws Exception
	 */
	public function validate_name($name) {
		if (!preg_match('/^[A-Za-z]+$/', $name)) {
			throw new Exception("Please enter a first/last name containing only letters of minimum length 1.");
		}
	}

	public function validate_email($email) {
		// TODO: validate using phpmailer.
		if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
			throw new Exception("Please enter a valid email address");
		} else if ($this->getDb()->emailExists($email)) {
			throw new Exception('That email already exists. Please use another one.');
		} // end else if
	}

	public function validate_user_type($user_type) {
		switch ($user_type) {
			case "tutor":
			case "secretary":
			case "admin":
				return true;
			default:
				throw new Exception('Incorrect user type.');
		}
	}

	public function validate_user_major($user_major_ext) {
		if ($user_major_ext === "null") {
			return true;
		}
		$query = "SELECT COUNT(1)  FROM `" . DB_NAME . "`.major WHERE major.extension=':extension'";
		$query = $this->getDbConnection()->prepare($query);
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
	public function getLastName() {
		return $this->lastName;
	}

	/**
	 * @return mixed
	 */
	public function getMobileNum() {
		return $this->mobileNum;
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


}

?>
