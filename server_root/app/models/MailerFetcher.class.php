<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 10/10/2014
 * Time: 4:14 AM
 */
class MailerFetcher
{
	const DB_TABLE = "mail";
	const DB_COLUMN_LAST_SENT = "last_sent";
	const MAX_MAILS_PER_MINUTE = 10;

	public static function canSendMail() {
		date_default_timezone_set('Europe/Athens');
		$dateNow = new DateTime();
		$dateNow = $dateNow->format(Dates::DATE_FORMAT_IN);

		try {
			$sql = "SELECT COUNT(`" . self::DB_COLUMN_LAST_SENT . "`)
			FROM `" . DatabaseManager::$dsnProduction[DatabaseManager::DB_NAME] . "`.`" . self::DB_TABLE . "`
			WHERE `" . self::DB_COLUMN_LAST_SENT . "` >= :now - INTERVAL 1 MINUTE";

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($sql);
			$query->bindParam(':now', $dateNow, PDO::PARAM_STR);
			$query->execute();

			if ($query->fetchColumn() >= self::MAX_MAILS_PER_MINUTE) return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if system can send mails. Aborting." . $e->getMessage());
		}

		return true;
	}

	public static function updateMailSent() {
		date_default_timezone_set('Europe/Athens');
		$dateNow = new DateTime();
		$dateNow = $dateNow->format(Dates::DATE_FORMAT_IN);


		try {

			$query = "INSERT INTO `" . DatabaseManager::$dsnProduction[DatabaseManager::DB_NAME] . "`.`" .
				self::DB_TABLE . "`
				VALUES(
					:now
				)";

			$dbConnection = DatabaseManager::getConnection();
			$query = $dbConnection->prepare($query);
			$query->bindParam(':now', $dateNow, PDO::PARAM_STR);
			$query->execute();
			return true;
		} catch (Exception $e) {
			throw new Exception("Could not data into database.");
		}
	}
} 