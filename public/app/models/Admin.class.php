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

	/**
	 * Returns a single column from the next row of a result set or FALSE if there are no more rows.
	 *
	 * @param $what
	 * @param $field
	 * @param $value
	 * @return mixed
	 * @throws InvalidArgumentException
	 */
	public function updateInfo($what, $field, $where, $value) {
		// I have only added few, but you can add more. However do not add 'password' even though the parameters will only be given by you and not the user, in our system.
		$allowed = array('id', 'username', 'f_name', 'l_name', 'email', 'COUNT(mobile)',
			 'mobile', 'user', 'gen_string', 'COUNT(gen_string)', 'COUNT(id)', 'img_loc');
		if (!in_array($what, $allowed, true) || !in_array($field, $allowed, true)) {
			throw new InvalidArgumentException;
		} else {
			try {
				$sql = "SELECT $what FROM `" . DB_NAME . "`.`" . $field . "` WHERE $where = ?";
				UPDATE `sass-ms`.`user` SET `f_name`='tutor' WHERE `id`='8' and`user_types_id`='9';

				UPDATE `" . DB_NAME . "`.`" . $field . "` SET `$what`= ? WHERE `id`='8' and`user_types_id`='9';

				$query = $this->getConnection()->prepare($sql);
				$query->bindValue(1, $value, PDO::PARAM_STR);
				$query->execute();
				return $query->fetchColumn();
				//return $sql;
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}

		}
	} // end getAllData


	public function createUser($first_name, $last_name, $email, $user_type, $user_major_ext, $teaching_courses) {
		$this->validate_name($first_name);
		$this->validate_name($last_name);
		$this->validateEmail($email);
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