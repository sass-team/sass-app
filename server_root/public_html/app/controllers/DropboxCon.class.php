<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 10/23/2014
 * Time: 12:37 AM
 */
class DropboxCon
{

	public static function insertDatabaseToken($accessToken, $adminUserId) {
		if (DropboxFetcher::existsAccessTokenAdmin($adminUserId)) throw new Exception(
			"There is already a Dropbox account connected.");
		if (!DropboxFetcher::insertDatabaseToken($accessToken, $adminUserId)) {
			throw new Exception("Data was not inserted into database. Please try again.");
		}
		return true;
	}
} 