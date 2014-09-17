<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/16/2014
 * Time: 11:31 PM
 */
class Dates
{
	const DATE_FORMAT_IN = "Y-m-d H:i:s";
	const DATE_FORMAT_OUT = "m/d/Y g:i A";

	public static function initDateTime($dateString) {
		try {
			$dateString = new DateTime($dateString);
			return $dateString;
		} catch (Exception $e) {
			throw new Exception("Date have been malformed.");
		}

	}
} 