<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) <year> <copyright holders>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
date_default_timezone_set('Europe/Athens');

/**
 * @author Rizart Dokollari
 * @author George Skarlatos
 * @since 9/15/2014
 */
class Term
{

	const MINIMUM_TERM_DAYS = 20;

	public static function create($db, $name, $startDate, $endDate) {
		date_default_timezone_set('Europe/Athens');

		self::validateName($db, $name);
		try {

			$startDate = new DateTime($startDate);
			$endDate = new DateTime($endDate);

			self::validateDateTypes($startDate, $endDate);
			TermFetcher::insert($db, $name, $startDate, $endDate);
		} catch (Exception $e) {
			throw new Exception("Dates have been malformed." . $e->getMessage());
		}

	}

	public static function  validateName($db, $name) {
		if (!preg_match('/^[\\w\\ ]{1,35}$/', $name))
			throw new Exception("Term names can only word characters of length 1-35.");

		if (TermFetcher::existsName($db, $name))
			throw new Exception("Term name already exists. Please choose another one.");
	}

	public static function validateDateTypes($startDate, $endDate) {

		$intervalDates = date_diff($startDate, $endDate);

		if ($intervalDates === FALSE)
			throw new Exception("Could not compare dates.");

		if ($intervalDates->days < self::MINIMUM_TERM_DAYS)
			throw new Exception("Minimum acceptable term period is 20 days.");
	}

	public static function updateName($db, $id, $newName, $oldName) {
		if (strcmp($newName, $oldName) === 0) return false;

		self::validateName($db, $newName);
		TermFetcher::updateName($db, $id, $newName);

		return true;
	}

	public static function updateStartingDate($db, $id, $newStartingDate, $oldStartingDate) {
		if (strcmp($newStartingDate, $oldStartingDate) === 0) return false;

		Dates::validateSingleAsString($newStartingDate, $oldStartingDate);
		TermFetcher::updateStartingDate($db, $id, $newStartingDate);

		return true;
	}



	public static function updateEndingDate($db, $id, $newEndingDate, $oldEndingDate) {
		if (strcmp($newEndingDate, $oldEndingDate) === 0) return false;

		Dates::validateSingleAsString($newEndingDate, $oldEndingDate);
		TermFetcher::updateSingleColumn($db, $id, TermFetcher::DB_COLUMN_END_DATE, $newEndingDate, PDO::PARAM_STR);
		return true;
	}

	public static function delete($db, $id) {
		self::validateId($db, $id);
		if (!TermFetcher::idExists($db, $id)) {
			throw new Exception("Could not retrieve course to be deleted from database. <br/>
                Maybe some other administrator just deleted it?");
		}

		TermFetcher::delete($db, $id);
	}

	public static function validateId($db, $id) {
		if (!preg_match('/^[0-9]+$/', $id) || (!TermFetcher::idExists($db, $id))) {
			throw new Exception("Data tempering detected.
			<br/>You&#39;re trying to hack this app.<br/>Developers are being notified about this.<br/>Expect Us.");
		}
	}
} 