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

			self::validateDates($startDate, $endDate);
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

	public static function validateDates($startDate, $endDate) {

		$intervalDates = date_diff($startDate, $endDate);

		if ($intervalDates === FALSE)
			throw new Exception("Could not compare dates.");

		if ($intervalDates->days < self::MINIMUM_TERM_DAYS)
			throw new Exception("Minimum acceptable term period is 20 days.");
	}
} 