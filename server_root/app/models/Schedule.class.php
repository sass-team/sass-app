<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/19/2014
 * Time: 11:07 AM
 */
class Schedule
{
	public static function add($db, $dateStart, $dateEnd, $tutorId, $termId) {

		Tutor::validateId($db, $tutorId);
		Term::validateId($db, $termId);

		$dateStart = self::validateDate($db, $dateStart, $tutorId);
		$dateEnd = self::validateDate($db, $dateEnd, $tutorId);

		$appointmentId = ScheduleFetcher::insert($db, $dateStart, $dateEnd, $tutorId, $termId);
		// TODO: add option for admin to check if he wants an automatic email to be said on said user on his email
	}

	public static function validateDate($db, $date, $tutorId) {
		$date = Dates::initDateTime($date);
//		if (ScheduleFetcher::existDatesBetween($db, $dateStart, $dateEnd, $tutorId)) {
//			throw new Exception("Sorry, the days/hours you inputted conflict with existing working hours.");
//		}
		return $date;
	}
} 