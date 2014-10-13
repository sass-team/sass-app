<?php

/**
 * Created by PhpStorm.
 * User: Rizart
 * Date: 9/2/2014
 * Time: 1:07 PM
 */
class UserFetcher
{
	const DB_TABLE = "user";
	const DB_COLUMN_ID = "id";
	const DB_COLUMN_MOBILE = "mobile";
	const DB_MOBILE_NUM = "mobile";
	const DB_COLUMN_FIRST_NAME = "f_name";
	const DB_COLUMN_LAST_NAME = "l_name";
	const DB_COLUMN_PROFILE_DESCRIPTION = "profile_description";
	const DB_COLUMN_EMAIL = "email";
	const DB_COLUMN_GEN_STRING = "gen_string";
	const DB_COLUMN_USER_TYPES_ID = "user_types_id";
	const DB_COLUMN_GEN_STRING_UPDATE_AT = "gen_string_update_at";
	const DB_COLUMN_ACTIVE = "active";
	const DB_COLUMN_IMG_LOC = "img_loc";
	const DB_COLUMN_DATE_CREATED = "date";


	public static function existsMobileNum($newMobileNum) {

		try {
			$query = "SELECT COUNT(" . self::DB_COLUMN_MOBILE . ") FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_MOBILE . "` = :mobileNum";
			$dbConnection = DatabaseManager::getConnection();

			$query = $dbConnection->prepare($query);
			$query->bindParam(':mobileNum', $newMobileNum, PDO::PARAM_INT);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if new mobile number already exists on database.");
		}

		return true;

	}

	public static function retrieveUsingEmail($email) {
		$query = "SELECT `" . self::DB_COLUMN_ID . "`, `" . self::DB_COLUMN_FIRST_NAME . "`, `" .
			self::DB_COLUMN_LAST_NAME . "` , `" . self::DB_COLUMN_ACTIVE . "`
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			WHERE `" .
			self::DB_COLUMN_EMAIL . "`=:email";
		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':email', $email, PDO::PARAM_STR);

			$query->execute();
			return $query->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Something terrible happened. Could not retrieve email data.");
			// end try
		}
	}

	public static function retrieveGenStringDate($id) {
		$query = "SELECT `" . self::DB_COLUMN_GEN_STRING_UPDATE_AT . "`
		FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
		WHERE `" . self::DB_COLUMN_ID . "`=:id";
		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_STR);

			$query->execute();
			return $query->fetchColumn();
		} catch (PDOException $e) {
			throw new Exception("Something terrible happened. Could not retrieve database.");
			// end try
		}
	}

	public static function retrieveSingle($id) {
		$query =
			"SELECT `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_EMAIL . "`, `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_ID . "`, `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_FIRST_NAME . "`, `" . self::DB_TABLE
			. "`.`" . self::DB_COLUMN_LAST_NAME . "`, `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_IMG_LOC . "`,
				`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_DATE_CREATED . "`, `" . self::DB_TABLE . "`.`" .
			self::DB_COLUMN_PROFILE_DESCRIPTION . "`, `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_MOBILE . "`,
			`" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ACTIVE . "`, `" . UserTypesFetcher::DB_TABLE . "`.`" .
			UserTypesFetcher::DB_COLUMN_TYPE . "`
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			INNER JOIN `" . UserTypesFetcher::DB_TABLE . "`
				ON `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_USER_TYPES_ID . "` = `" . UserTypesFetcher::DB_TABLE . "`.`" .
			UserTypesFetcher::DB_COLUMN_ID . "`
			WHERE `" . self::DB_TABLE . "`.`" . self::DB_COLUMN_ID . "` = :id";


		try {
			$dbConnection = DatabaseManager::getConnection();

			$dbConnection = $dbConnection->prepare($query);
			$dbConnection->bindParam(':id', $id, PDO::PARAM_INT);

			$dbConnection->execute();
			return $dbConnection->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Could not retrieve user data." . $e->getMessage());
			// end try
		}
	}


	public
	static function updateGenStringTimeUpdate($id) {
		date_default_timezone_set('Europe/Athens');
		$date_modified = date("Y-m-d H:i:s");

		try {

			$query = "UPDATE `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			SET `" . self::DB_COLUMN_GEN_STRING_UPDATE_AT . "`= :data_modified
			WHERE `" . self::DB_COLUMN_ID . "`= :id";
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':id', $id);
			$query->bindParam(':data_modified', $date_modified);

			$query->execute();
		} catch (Exception $e) {
			throw new Exception("Could not update data for password recovery.");
		}
	}

	public static function generatedStringExists($id, $generatedString) {
		try {
			$query = "SELECT COUNT(" . self::DB_COLUMN_GEN_STRING . ")
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" .	self::DB_TABLE . "`
			WHERE `" . self::DB_COLUMN_ID . "` = :id
			AND `" . self::DB_COLUMN_GEN_STRING . "` = :genString";

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->bindParam(':genString', $generatedString, PDO::PARAM_STR);

			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if user id already exists on database.");
		}
		return true;
	}

	public static function updatePassword($id, $newPassword) {
		try {
			$new_password_hashed = password_hash($newPassword, PASSWORD_DEFAULT);

			$query = "UPDATE `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`user`
			SET `password`= :password, `gen_string`=''
			WHERE `id`= :id";

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':id', $id);
			$query->bindParam(':password', $new_password_hashed);

			$query->execute();
		} catch (Exception $e) {
			throw new Exception("Could not update password.");
		}
	}

	public static function existsId($id) {
		try {
			$sql = "SELECT COUNT(" . self::DB_COLUMN_ID . ") FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_ID . "` = :id";

			$dbConnection = DatabaseManager::getConnection();
			$dbConnection = $dbConnection->prepare($sql);
			$dbConnection->bindParam(':id', $id, PDO::PARAM_INT);
			$dbConnection->execute();

			if ($dbConnection->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if user id already exists on database.");
		}
		return true;
	}

	public static function updateGenString($id, $generatedString) {
		try {
			$query = "UPDATE `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`user` SET `gen_string` = :gen_string WHERE `id` = :id";

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':gen_string', $generatedString, PDO::PARAM_STR);
			$query->bindParam(':id', $id, PDO::PARAM_INT);

			$query->execute();
		} catch (Exception $e) {
			throw new Exception("Could not update generated string. Please re-send password link to user that was created.");
		}

	}
} 