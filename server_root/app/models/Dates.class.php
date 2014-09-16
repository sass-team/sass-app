<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/16/2014
 * Time: 11:31 PM
 */
class Dates
{
	public static function validateSingleAsString($dateString) {
		try {
			$endDate = new DateTime($dateString);
		} catch (Exception $e) {
			throw new Exception("Date have been malformed.");
		}

	}
} 