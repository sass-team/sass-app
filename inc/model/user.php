<?php

/**
 * Class User will contain prototype for users; tutors, secretary and admin.
 * Log In, Log Out.
 */
abstract class User {

	private $id;
	private $firstName;
	private $lastName;
	private $avatarImgLoc;
	private $profileDescription;
	private $dateAccountCreated;
	private $userType;
	private $mobileNum;
	private $tutorMajor;
	private $email;

	/**
	 * Constructor
	 * @param $database
	 */
	public function __construct($allUserData) {
		$this->setId($allUserData['id']);
		$this->setFirstName($allUserData['f_name']);
		$this->setLastName($allUserData['l_name']);
		$this->setAvatarImgLoc($allUserData['img_loc']);
		$this->setProfileDescription($allUserData['profile_description']);
		$this->setDateAccountCreated($allUserData['date']);
		$this->setMobileNum($allUserData['mobile']);
		$this->setEmail($allUserData['email']);

		// initialize tutor/secretary/admin class depending on type.
		$this->setUserType($allUserData['type']);
		$this->setTutorMajor($allUserData['name']);
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
	} // end __construct

	/**
	 * @param mixed $avatarImgLoc
	 */
	private function setAvatarImgLoc($avatarImgLoc) {
		$this->avatarImgLoc = $avatarImgLoc;
	}

	// end function login

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
	} // end getAllData

	/**
	 * @param mixed $mobileNum
	 */
	private function setMobileNum($mobileNum) {
		$this->mobileNum = $mobileNum;
	} // end function get_data

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
	}

	/**
	 * @param mixed $tutorMajor
	 */
	private function setTutorMajor($tutorMajor) {
		$this->tutorMajor = $tutorMajor;
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
	}

	/**
	 * @return mixed
	 */
	public function getProfileDescription() {
		return $this->profileDescription;
	}

	/**
	 * @return mixed
	 */
	public function getTutorMajor() {
		return $this->tutorMajor;
	}

	/**
	 * @return mixed
	 */
	public function getUserType() {
		return $this->userType;
	}

	abstract protected function isAdmin();

	abstract protected function isTutor();

	abstract protected function isSecretary();

}

?>
