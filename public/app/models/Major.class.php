<?php

/**
 * Created by PhpStorm.
 * User: Rizart
 * Date: 9/2/2014
 * Time: 10:50 AM
 */
class Major
{

	public static function validateId($db, $id) {
		if (is_null($id)) throw new Exception("Major is required.");

		if (!preg_match("/^[0-9]+$/", $id)) {
			throw new Exception("Data has been tempered. Aborting process.");
		}

		if (!MajorFetcher::idExists($db, $id)) {
			// TODO: sent email to developer relevant to this error.
			throw new Exception("Either something went wrong with a database query, or you're trying to hack this app. In either case, the developers were just notified about this.");
		}
	}

	public static function create($db, $code, $name) {
		$code = self::validateCode($db, $code);
		$name = self::validateName($db, $name);
		MajorFetcher::insert($db, $code, $name);

	}

	public static function  validateCode($db, $code) {
		$code = trim($code);

		if (!preg_match("/^[A-Z0-9]{1,10}$/", $code)) {
			throw new Exception("Major code can contain capital letters in the range of A-Z, numbers 0-9 and of length 1-10.");
		}

		if (MajorFetcher::codeExists($db, $code)) {
			throw new Exception("Major code already exists on database. Please insert a different one.");
		}

		return $code;
	}

	public static function  validateName($db, $name) {

		if (!preg_match("/^[\\w\\[ *\\w]* ?$/", $name)) {
			throw new Exception("Major name can only contain <a href='http://www.regular-expressions.info/shorthand.html'
            target='_blank'>word characters</a> and spaces of length 1-50. Example: 'Psychology', 'Information Technology'");
		}

		if (MajorFetcher::nameExists($db, $name)) {
			throw new Exception("Major name already exists on database. Please insert a different one.");
		}

		return $name;
	}


}