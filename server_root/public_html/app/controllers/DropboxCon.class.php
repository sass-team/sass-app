<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 10/23/2014
 * Time: 12:37 AM
 */
class DropboxCon
{
	const SERVICE_APP_DATABASE_BACKUP = "service_app_database_backup";
	const SERVICE_APP_EXCEL_BACKUP = "service_app_excel_backup";

	public static function insertAccessToken($accessToken, $userId, $serviceType) {
		if (DropboxFetcher::existsAccessToken($serviceType)) throw new Exception(
			"There is already a Dropbox account connected with chosen service.");
		if (!DropboxFetcher::insertAccessToken($accessToken, $userId, $serviceType)) {
			throw new Exception("No changes made on database. Please try again.");
		}
		return true;
	}

	public static function verifyServiceType($serviceType) {
		switch ($serviceType) {
			case self::SERVICE_APP_DATABASE_BACKUP:
			case self::SERVICE_APP_EXCEL_BACKUP:
				break;
			default:
				throw new Exception("Data has been malformed.");
		}
	}

	public static function disconnectService($serviceType) {

	}
} 