<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/16/2014
 * Time: 10:58 PM
 */
class Appointment
{

	public static function add($db, $dateStart, $dateEnd, $courseId, $studentsIds, $instructorsId,
	                           $termId) {
		Dates::validateSingleAsString($dateStart);
		Dates::validateSingleAsString($dateEnd);
		Course::validateId($db, $courseId);
		Student::validateIds($db, $studentsIds);
		Instructor::validateId($db, $instructorsId);
		Term::validateId($db, $termId);

	}
} 