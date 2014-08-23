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
	 * @param mixed $db
	 */
	public function setDb($db) {
		$this->db = $db;
	}

	/**
	 * @return mixed
	 */
	public function getDb() {
		return $this->db;
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
	 * @param mixed $id
	 */
	public function setId($id) {
		$this->id = $id;
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
} 