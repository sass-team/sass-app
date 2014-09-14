<?php

/**
 * Created by PhpStorm.
 * User: rdk
 * Date: 5/29/14
 * Time: 2:13 PM
 */
class Admin extends User
{
	public function __construct($db, $id, $firstName, $lastName, $email, $mobileNum, $avatarImgLoc, $profileDescription, $dateAccountCreated, $userType, $accountActiveStatus) {
		parent::__construct($db, $id, $firstName, $lastName, $email, $mobileNum, $avatarImgLoc, $profileDescription, $dateAccountCreated, $userType, $accountActiveStatus);
	}

	public static function createUser($db, $first_name, $last_name, $email, $user_type, $majorId, $coursesIds) {
		self::validateName($first_name);
		self::validateName($last_name);
		self::validateEmail($db, $email, self::DB_TABLE);
		self::validateUserType($user_type);
		//$this->validate_teaching_course($teaching_courses);

		try {
			$queryInsertUser = "INSERT INTO `" . DB_NAME . "`.`" . User::DB_TABLE . "` (`" . User::DB_COLUMN_EMAIL . "`,
			`" . User::DB_COLUMN_FIRST_NAME . "`, `" . User::DB_COLUMN_LAST_NAME . "`, `" . User::DB_COLUMN_USER_TYPES_ID . "`)
				VALUES(
					:email,
					:first_name,
					:last_name,
					(SELECT `" . UserTypes::DB_COLUMN_ID . "` FROM `" . UserTypes::DB_TABLE . "` WHERE `" .
				UserTypes::DB_COLUMN_TYPE . "`=:user_type )
				)";

			$db->getConnection()->beginTransaction();

			$queryInsertUser = $db->getConnection()->prepare($queryInsertUser);
			$queryInsertUser->bindParam(':email', $email, PDO::PARAM_STR);
			$queryInsertUser->bindParam(':first_name', $first_name, PDO::PARAM_STR);
			$queryInsertUser->bindParam(':last_name', $last_name, PDO::PARAM_STR);
			$queryInsertUser->bindParam(':user_type', $user_type, PDO::PARAM_STR);

			$queryInsertUser->execute();

			// last inserted if of THIS connection
			$userId = $db->getConnection()->lastInsertId();

			if (strcmp($user_type, User::SECRETARY) === 0) {
				Major::validateId($db, $majorId);
				Tutor::insertMajor($db, $userId, $majorId);
				if ($coursesIds !== NULL) Tutor_has_course::addCourses($db, $userId, $coursesIds);
			}


			$db->getConnection()->commit();
			return $userId;
		} catch (Exception $e) {
			$db->getConnection()->rollback();
			throw new Exception("Could not insert user into database." . $e->getMessage());
		}
	}

	public function isAdmin() {
		return true;
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
	public function updateInfo($what, $field, $value, $id) {
		// I have only added few, but you can add more. However do not add 'password' even though the parameters will only be given by you and not the user, in our system.
		$allowed = array('id', 'username', 'f_name', 'l_name', 'email', 'COUNT(mobile)',
			'mobile', 'user', 'gen_string', 'COUNT(gen_string)', 'COUNT(id)', 'img_loc');
		if (!in_array($what, $allowed, true) || !in_array($field, $allowed, true)) {
			throw new InvalidArgumentException;
		} else {
			try {

				$sql = "UPDATE `" . DB_NAME . "`.`" . $field . "` SET `$what` = ? WHERE `id`= ?";

				$query = $this->getDb()->getConnection()->prepare($sql);
				$query->bindValue(1, $value, PDO::PARAM_STR);
				$query->bindValue(2, $id, PDO::PARAM_INT);
				$query->execute();
				return true;
			} catch (Exception $e) {
				throw new Exception($e->getMessage());
			}

		}
	}


}