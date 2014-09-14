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

	public static function existsMobileNum($db, $newMobileNum) {

		try {
			$sql = "SELECT COUNT(" . self::DB_COLUMN_MOBILE . ") FROM `" . DB_NAME . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_MOBILE . "` = :mobileNum";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':mobileNum', $newMobileNum, PDO::PARAM_INT);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if new mobile number already exists on database.");
		}

		return true;

	}

	public static function retrieveUsingEmail($db, $email) {
		$query = "SELECT `" . self::DB_COLUMN_ID . "`, `" . self::DB_COLUMN_FIRST_NAME . "`, `" .
			self::DB_COLUMN_LAST_NAME . "` FROM `" . DB_NAME . "`.`" . self::DB_TABLE . "` WHERE `" .
			self::DB_COLUMN_EMAIL . "`=:email";
		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':email', $email, PDO::PARAM_STR);

			$query->execute();
			return $query->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Something terrible happened. Could not retrieve database." . $e->getMessage());
		} // end try

	}

	public static function retrieveGenStringDate($db, $id) {
		$query = "SELECT `" . self::DB_COLUMN_GEN_STRING_UPDATE_AT . "` FROM `" . DB_NAME . "`.`" . self::DB_TABLE .
			"` WHERE `" . self::DB_COLUMN_ID . "`=:id";
		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_STR);

			$query->execute();
			return $query->fetchColumn();
		} catch (PDOException $e) {
			throw new Exception("Something terrible happened. Could not retrieve database." . $e->getMessage());
		} // end try
	}

	public static function retrieveSingle($db, $id) {
		$query = "SELECT `" . self::DB_TABLE . "`.email, user.id, user.`f_name`, user.`l_name`, user.`img_loc`,
						user.date, user.`profile_description`, user.mobile, user_types.type, user.active
					FROM `" . DB_NAME . "`.user
						LEFT OUTER JOIN user_types ON user.`user_types_id` = `user_types`.id
					WHERE user.id = :id";

		try {
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);

			$query->execute();
			return $query->fetch(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			throw new Exception("Something terrible happened. Could not retrieve database." . $e->getMessage());
		} // end try
	}


	public static function updateGenStringTimeUpdate($db, $id) {
		date_default_timezone_set('Europe/Athens');
		$date_modified = date("Y-m-d H:i:s");

		try {

			$query = "UPDATE `" . DB_NAME . "`.`" . self::DB_TABLE . "` SET `" . self::DB_COLUMN_GEN_STRING_UPDATE_AT . "`=
			 :data_modified WHERE `" . self::DB_COLUMN_ID . "`= :id";
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':id', $id);
			$query->bindParam(':data_modified', $date_modified);

			$query->execute();
		} catch (Exception $e) {
			throw new Exception("Could not update data for password recovery.");
		}
	}

	public static function generatedStringExists($db, $id, $generatedString) {
		try {
			$sql = "SELECT COUNT(" . self::DB_COLUMN_GEN_STRING . ") FROM `" . DB_NAME . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_ID . "` = :id AND `" . self::DB_COLUMN_GEN_STRING . "` = :genString";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->bindParam(':genString', $generatedString, PDO::PARAM_STR);

			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if user id already exists on database.");
		}
		return true;
	}

	public static function updatePassword($db, $id, $newPassword) {
		try {
			$new_password_hashed = password_hash($newPassword, PASSWORD_DEFAULT);

			$query = "UPDATE `" . DB_NAME . "`.`user` SET `password`= :password, `gen_string`='' WHERE `id`= :id";
			$query = $db->getConnection()->prepare($query);
			$query->bindParam(':id', $id);
			$query->bindParam(':password', $new_password_hashed);

			$query->execute();
		} catch (Exception $e) {
			throw new Exception("Could not update password.");
		}
	}

	public static function existsId($db, $id) {
		try {
			$sql = "SELECT COUNT(" . self::DB_COLUMN_ID . ") FROM `" . DB_NAME . "`.`" .
				self::DB_TABLE . "` WHERE `" . self::DB_COLUMN_ID . "` = :id";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if user id already exists on database.");
		}
		return true;
	}

	public static function updateGenString($db, $id, $generatedString) {
		try {
			$sql = "UPDATE `" . DB_NAME . "`.`user` SET `gen_string` = :gen_string WHERE `id` = :id";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':gen_string', $generatedString, PDO::PARAM_STR);
			$query->bindParam(':id', $id, PDO::PARAM_INT);

			$query->execute();
		} catch (Exception $e) {
			throw new Exception("Could not update generated string. Please re-send password link to user that was created.");
		}

	}
} 