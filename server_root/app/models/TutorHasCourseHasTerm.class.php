<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/14/2014
 * Time: 4:55 AM
 */
class Tutor_has_course_has_schedule
{


	public static function addCourses($db, $id, $coursesIds, $termId) {
		self::validateCoursesId($db, $coursesIds);
		Term::validateId($db, $termId);
		Tutor_has_course_has_termFetcher::insertMany($db, $id, $coursesIds, $termId);
	}

	public static function validateCoursesId($db, $coursesId) {
		foreach ($coursesId as $courseId) {
			Course::validateId($db, $courseId);
		}
	}


}