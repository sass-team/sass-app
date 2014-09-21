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

		$appointmentId = AppointmentFetcher::insert($db, $dateStart, $dateEnd, $courseId, $studentsIds, $tutorId, $instructorsIds, $termId);
		Mailer::sendTutorNewAppointment($db, $appointmentId);
	}

	public static function getSingleTutor($db, $tutorId, $termId) {
		Tutor::validateId($db, $tutorId);
		Term::validateId($db, $termId);
		return AppointmentFetcher::retrieveSingleTutor($db, $tutorId, $termId);
	}

	public static function validateId($db, $id) {
		if (is_null($id) || !preg_match("/^[0-9]+$/", $id)) throw new Exception("Data has been tempered. Aborting process.");

		if (!AppointmentFetcher::existsId($db, $id)) {
			// TODO: sent email to developer relevant to this error.
			throw new Exception("Either something went wrong with a database query, or you're trying to hack this app. In either case, the developers were just notified about this.");
		}
	}

	public static function getTutorsOnTerm($db, $termId){
		Term::validateId($db, $termId);
		return AppointmentFetcher::retrieveTutors($db, $termId);
	}

}