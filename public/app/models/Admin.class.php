<?php

/**
 * Created by PhpStorm.
 * User: rdk
 * Date: 5/29/14
 * Time: 2:13 PM
 */
class Admin extends User
{
	private $users;

	/**
	 * Constructor
	 * @param $database
	 */
	public function __construct($data, $db) {
		parent::__construct($data, $db);
	}

	public function isAdmin() {
		return true;
	}

	/**
	 * @return mixed
	 */
	public function getUsers() {
		if (!isset($this->users)) {
			$this->retrieveUsers();
		}

		return $this->users;
	}

	/**
	 * @param mixed $users
	 */
	public function setUsers($users) {
		$this->users = $users;
	}

	private function retrieveUsers() {
		$query = "SELECT user.id, user.f_name, user.l_name, user.img_loc, user.profile_description, user.date, user.mobile, user.email, user_types.type
		         FROM `" . DB_NAME . "`.user
						LEFT OUTER JOIN user_types ON user.`user_types_id` = `user_types`.id";
		$query = $this->getDb()->getConnection()->prepare($query);

		try {
			$query->execute();
			$rows = $query->fetchAll();

			$this->setUsers($rows);
		} catch (PDOException $e) {
			throw new Exception("Something terrible happened. Could not retrieve users data from database.: " . $e->getMessage());
		} // end catch
	}




	public function createUser($first_name, $last_name, $email, $user_type, $user_major_ext, $teaching_courses) {
		$this->validate_name($first_name);
		$this->validate_name($last_name);
		$this->validate_email($email);
		$this->validate_user_type($user_type);
		//$this->validate_user_major($user_major_ext);
		//$this->validate_teaching_course($teaching_courses);

		try {
			$query = "INSERT INTO `" . DB_NAME . "`.user (`email`, `f_name`, `l_name`, `user_types_id`)
				VALUES(
					:email,
					:first_name,
					:last_name,
					(SELECT id as 'user_types_id' FROM user_types WHERE user_types.type=:user_type )
				)";

			$query = $this->getDb()->getConnection()->prepare($query);
			$query->bindParam(':email', $email, PDO::PARAM_STR);
			$query->bindParam(':first_name', $first_name, PDO::PARAM_STR);
			$query->bindParam(':last_name', $last_name, PDO::PARAM_STR);
			$query->bindParam(':user_type', $user_type, PDO::PARAM_INT);
			$query->execute();
			return true;
		} catch (Exception $e) {
			throw new Exception("Could not insert user into database.");
		}

	}
}