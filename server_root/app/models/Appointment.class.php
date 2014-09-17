<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/16/2014
 * Time: 10:58 PM
 */
class Appointment
{

	public static function add($db, $dateStart, $dateEnd, $courseId, $studentsIds, $tutorId, $instructorsIds, $termId) {
		$dateStart = Dates::initDateTime($dateStart);
		$dateEnd = Dates::initDateTime($dateEnd);
		Course::validateId($db, $courseId);

		if (sizeof($studentsIds) != sizeof($instructorsIds)) {
			throw new Exception("An instructor is required for each student.");
		}

		Student::validateIds($db, $studentsIds);
		Instructor::validateIds($db, $instructorsIds);
		Tutor::validateId($db, $tutorId);
		Term::validateId($db, $termId);

		AppointmentFetcher::insert($db, $dateStart, $dateEnd, $courseId, $studentsIds, $tutorId, $instructorsIds, $termId);
	}
}