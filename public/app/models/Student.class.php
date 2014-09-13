<?php

/**
 * Created by PhpStorm.
 * User: Rizart
 * Date: 8/23/14
 * Time: 2:15 PM
 */
class Student extends Person
{
	private $ci, $credits, $major;

	public function  __construct($db, $id, $firstName, $lastName, $email, $mobileNum, $ci, $credits, $major) {
		parent::__construct($db, $id, $firstName, $lastName, $email, $mobileNum);

		$this->setCi($ci);
		$this->setCredits($credits);
		$this->setMajor($major);
	}

	/**
	 * @param mixed $credits
	 */
	public function setCredits($credits) {
		$this->credits = $credits;
	}

	/**
	 * @param mixed $major
	 */
	public function setMajor($major) {
		$this->major = $major;
	}

	public static function add($db, $firstName, $lastName, $email, $mobileNum, $courses, $ci, $credits) {

	}

	/**
	 * @return mixed
	 */
	public static function retrieve($db) {
		$query = "SELECT id, email, f_name, l_name, mobile, ci, credits
		         FROM `" . DB_NAME . "`.student";
		$query = $db->getConnection()->prepare($query);

		try {
			$query->execute();
			$rows = $query->fetchAll(PDO::FETCH_ASSOC);

			return $rows;
		} catch (PDOException $e) {
			throw new Exception("Something terrible happened. Could not retrieve users data from database.: " . $e->getMessage());
		} // end catch
	}

	public static function create($db, $firstName, $lastName, $email, $studentId, $mobileNum, $majorId, $ci, $credits) {
		// Validate data
		Person::validateName($firstName);
		Person::validateName($lastName);
		Person::validateEmail($db, $email, StudentFetcher::DB_TABLE);
		self::validateStudentId($db, $studentId);
		$mobileNum = self::validateMobileNumber($db, $mobileNum);
		if (!empty($majorId)) Major::validate($db, $majorId);
		$ci = Student::validateCi($ci);
		$credits = Student::validateCredits($credits);

		// Insert into database
		StudentFetcher::insert($db, $firstName, $lastName, $email, $studentId, $mobileNum, $majorId, $ci, $credits);
	}

	public static function validateStudentId($db, $studentId) {
		if (!preg_match("/^[0-9]{6,7}$/", $studentId)) {
			throw new Exception("Student id can contain only numbers of length 6 to 7");
		}

		if (StudentFetcher::existsStudentId($db, $studentId)) {
			throw new Exception("Student id entered already exists in database."); // the array of errors messages
		}
	}

	/**
	 * @param $db
	 * @param $newMobileNum
	 * @return null
	 * @throws Exception
	 */
	public static function validateMobileNumber($db, $newMobileNum) {
		if (empty($newMobileNum) === TRUE) {
			return NULL; // no mobilenumber
		}
		if (!preg_match('/^[0-9]{10}$/', $newMobileNum)) {
			throw new Exception('Mobile number should contain only digits of total length 10');
		}

		if (StudentFetcher::existsMobileNum($db, $newMobileNum)) {
			throw new Exception("Mobile entered number already exists in database."); // the array of errors messages
		}

		return $newMobileNum;
	}

	public static function validateCi($ci) {
		if (empty($ci) === TRUE) return NULL;
		$ci = trim($ci);
		if (!preg_match('/^[0-4](\.[0-9]+)?$/', $ci)) throw new Exception('CI must similar to 3.5; 0 =< CI <= 4');

		return $ci;
	}

	public static function validateCredits($credits) {
		if (empty($credits) === TRUE) return null;
		$credits = trim($credits);
		if (!preg_match('/^[0-9]+$/', $credits)) throw new Exception('CI must similar to 3.5; 0 =< CI <= 4');

		return $credits;
	}

	/**
	 * @return mixed
	 */
	public function getMajor() {
		return $this->major;
	}

	/**
	 * @return mixed
	 */
	public function getCredits() {
		return $this->credits;
	}

	/**
	 * @return mixed
	 */
	public function getCi() {
		return $this->ci;
	}

	/**
	 * @param mixed $ci
	 */
	public function setCi($ci) {
		$this->ci = $ci;
	}


}