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
	const DB_COLUMN_SERVICE_TYPE = "service_type";

	public static function insertAccessToken($accessToken, $userId, $serviceType) {
		try {
			$query = "INSERT INTO `" . App::$dsn[App::DB_NAME] . "`.`" . self::DB_TABLE . "`
			(`" . self::DB_COLUMN_ACCESS_TOKEN . "`, `" . self::DB_COLUMN_USER_ID . "`, `" . self::DB_COLUMN_SERVICE_TYPE . "`)
				VALUES(
					:access_token, :user_id, :service_type
				)";

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':access_token', $accessToken, PDO::PARAM_STR);
			$query->bindParam(':user_id', $userId, PDO::PARAM_INT);
			$query->bindParam(':service_type', $serviceType, PDO::PARAM_STR);

			$query->execute();

			if ($query->rowCount() === 0) return FALSE;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not insert data into database.");
		}
		return true;
	}

	public static function existsAccessToken($serviceType) {
		try {
			$query = "SELECT COUNT(" . self::DB_COLUMN_ACCESS_TOKEN . ")
			FROM `" . App::$dsn[App::DB_NAME] . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_COLUMN_SERVICE_TYPE . "` = :service_type";
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':service_type', $serviceType, PDO::PARAM_STR);
			$query->execute();

			if (strcmp($query->fetchColumn(), '0') === 0) return false;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __DIR__);
			throw new Exception("There was a problem with the database. <br/> Please try again.");
		}

		return true;
	}


	public static function retrieveAccessToken($serviceType) {
		$query = "SELECT `" . self::DB_COLUMN_ACCESS_TOKEN . "`
		FROM `" . App::$dsn[App::DB_NAME] . "`.`" . self::DB_TABLE . "`
		WHERE `" . self::DB_COLUMN_SERVICE_TYPE . "` = :service_type";

		try {
			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);

			$query->bindParam(':service_type', $serviceType, PDO::PARAM_STR);

			$query->execute();
			return $query->fetch(PDO::FETCH_ASSOC);
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Something went wrong with database access.<br/>Please try again.");
		} // end catch
	}

	public static function disconnectServiceType($serviceType) {
		try {
			$query = "DELETE
			FROM `" . App::$dsn[App::DB_NAME] . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_COLUMN_SERVICE_TYPE . "` = :service_type";

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':service_type', $serviceType, PDO::PARAM_STR);
			$query->execute();
			return true;
		} catch (Exception $e) {
			Mailer::sendDevelopers($e->getMessage(), __FILE__);
			throw new Exception("Could not access database. <br/>Please try again.");
		}
	}
} 