<?php

/**
 * Created by PhpStorm.
 * User: Rizart
 * Date: 8/23/14
 * Time: 2:15 PM
 */
class Student extends Person
{
	private $ci, $credits;

	public function  __construct($db, $id, $firstName, $lastName, $email, $mobileNum, $ci, $credits) {
		parent::__construct($db, $id, $firstName, $lastName, $email, $mobileNum);

		$this->setCi($ci);
		$this->setCredits($credits);
	}

	public static function add($db, $firstName, $lastName, $email, $mobileNum, $courses, $ci, $credits){

	}

	/**
	 * @param mixed $credits
	 */
	public function setCredits($credits) {
		$this->credits = $credits;
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

	public static function create($db, $firstName, $lastName, $email){
//		self::validateName($first_name);
//		self::validateName($last_name);
//		self::validateEmail($db, $email);
//		self::validateUserType($user_type);
//		//$this->validate_user_major($user_major_ext);
//		//$this->validate_teaching_course($teaching_courses);
//
//		try {
//			$query = "INSERT INTO `" . DB_NAME . "`.user (`email`, `f_name`, `l_name`, `user_types_id`)
//				VALUES(
//					:email,
//					:first_name,
//					:last_name,
//					(SELECT id as 'user_types_id' FROM user_types WHERE user_types.type=:user_type )
//				)";
//
//			$query = $db->getConnection()->prepare($query);
//			$query->bindParam(':email', $email, PDO::PARAM_STR);
//			$query->bindParam(':first_name', $first_name, PDO::PARAM_STR);
//			$query->bindParam(':last_name', $last_name, PDO::PARAM_STR);
//			$query->bindParam(':user_type', $user_type, PDO::PARAM_INT);
//			$query->execute();
//			return true;
//		} catch (Exception $e) {
//			throw new Exception("Could not insert user into database.");
//		}
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