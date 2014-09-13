<?php

/**
 * Created by PhpStorm.
 * User: Rizart
 * Date: 9/2/2014
 * Time: 1:07 PM
 */
class UserFetcher
{
	public static function existsMobileNum($db, $newMobileNum) {

		try {
			$sql = "SELECT COUNT(" . StudentFetcher::DB_COLUMN_MOBILE . ") FROM `" . DB_NAME . "`.`" .
				StudentFetcher::DB_TABLE . "` WHERE `" . StudentFetcher::DB_COLUMN_MOBILE . "` = :mobileNum";
			$query = $db->getConnection()->prepare($sql);
			$query->bindParam(':mobileNum', $newMobileNum, PDO::PARAM_INT);
			$query->execute();

			if ($query->fetchColumn() === '0') return false;
		} catch (Exception $e) {
			throw new Exception("Could not check if new mobile number already exists on database.");
		}

		return true;

	}
} 