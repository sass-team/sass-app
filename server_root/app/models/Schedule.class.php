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

	public static function validateId($db, $id) {
		if (is_null($id) || !preg_match("/^[0-9]+$/", $id)) {
			throw new Exception("Data has been tempered. Aborting process.");
		}

		if (!ScheduleFetcher::existsId($db, $id)) {
			// TODO: sent email to developer relevant to this error.
			throw new Exception("Either something went wrong with a database query, or you're trying to hack this app. In either case, the developers were just notified about this.");

		}
	}

	public static function getSingleTutor($db, $tutorId, $termId) {
		Tutor::validateId($db, $tutorId);
		Term::validateId($db, $termId);
		return ScheduleFetcher::retrieveSingleTutor($db, $tutorId, $termId);
	}

	public static function getTutorsOnTerm($db, $termId) {
		Term::validateId($db, $termId);
		return ScheduleFetcher::retrieveTutorsOnTerm($db, $termId);
	}

}