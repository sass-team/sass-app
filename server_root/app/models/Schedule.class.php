<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/19/2014
 * Time: 11:07 AM
 */

class Schedule {
	public static function add($db, $dateStart, $dateEnd, $tutorId, $termId) {
		$dateStart = Dates::initDateTime($dateStart);
		$dateEnd = Dates::initDateTime($dateEnd);
		Tutor::validateId($db, $tutorId);
		Term::validateId($db, $termId);

		$appointmentId = ScheduleFetcher::insert($db, $dateStart, $dateEnd, $tutorId, $termId);
		// TODO: add option for admin to check if he wants an automatic email to be said on said user on his email
	}
} 