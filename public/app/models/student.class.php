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

	public function  __construct($id, $firstName, $lastName, $email, $mobileNum, $ci, $credits) {
		parent::__construct($id, $firstName, $lastName, $email, $mobileNum);

		$this->setCi($ci);
		$this->setCredits($credits);
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