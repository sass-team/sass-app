<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 10/22/2014
 * Time: 11:35 PM
 */
class DropboxFetcher
{
	const DB_TABLE = "dropbox";
	const DB_COLUMN_USER_ID = "user_id";
	const DB_COLUMN_ACCESS_TOKEN = "access_token";

	public static function insertDatabaseToken($accessToken, $userId) {
		try {
			$query = "INSERT INTO `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			(`" . self::DB_COLUMN_ACCESS_TOKEN . "`, `" . self::DB_COLUMN_USER_ID . "`)
				VALUES(
					:access_token, :user_id
				)";

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':access_token', $accessToken, PDO::PARAM_STR);
			$query->bindParam(':user_id', $userId, PDO::PARAM_INT);

			$query->execute();

			if ($query->rowCount() === 0) return FALSE;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not insert data into database.");
		}
		return true;
	}

	public static function existsAccessToken($accessToken) {
		try {
			$query = "SELECT COUNT(" . self::DB_COLUMN_ACCESS_TOKEN . ")
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_COLUMN_ACCESS_TOKEN . "` = :dropbox_access_token";
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':dropbox_access_token', $accessToken, PDO::PARAM_STR);
			$query->execute();

			if (strcmp($query->fetchColumn(), '0') === 0) return false;
		} catch (Exception $e) {
			throw new Exception("There was a problem with database verification. <br/> Please try again."
				. $e->getMessage());
		}

		return true;
	}

	public static function existsAccessTokenAdmin($adminId) {
		try {
			$query = "SELECT COUNT(" . self::DB_COLUMN_ACCESS_TOKEN . ")
			FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			INNER JOIN  `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . UserFetcher::DB_TABLE . "`
			ON `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . UserFetcher::DB_TABLE . "`.`" .
				UserFetcher::DB_COLUMN_ID . "`  = `" .
				self::DB_TABLE . "`.`" . self::DB_COLUMN_USER_ID . "`
			INNER JOIN  `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . UserTypesFetcher::DB_TABLE . "`
			ON `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . UserTypesFetcher::DB_TABLE . "`.`" .
				UserTypesFetcher::DB_COLUMN_ID . "`  = `" .
				self::DB_TABLE . "`.`" . self::DB_COLUMN_USER_ID . "`
			WHERE `" . self::DB_COLUMN_USER_ID . "` = :user_id
			AND `" . UserTypesFetcher::DB_COLUMN_TYPE . "` = " . User::ADMIN;


			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':user_id', $adminId, PDO::PARAM_INT);

			$query->execute();

			if (strcmp($query->fetchColumn(), '0') === 0) return false;
		} catch (Exception $e) {
			throw new Exception("There was a problem with database verification. <br/> Please try again."
				. $e->getMessage());
		}

		return true;
	}

	public static function retrieveSingle($userId) {
		$query = "SELECT `" . self::DB_COLUMN_ACCESS_TOKEN . "`
		FROM `" . DatabaseManager::$dsn[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
		WHERE `" . self::DB_COLUMN_USER_ID . "` = :user_id";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);

			$query->bindParam(':user_id', $userId, PDO::PARAM_INT);

			$query->execute();
			return $query->fetch(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Something went wrong with database access.<br/> Please try again.");
		} // end catch
	}

} 