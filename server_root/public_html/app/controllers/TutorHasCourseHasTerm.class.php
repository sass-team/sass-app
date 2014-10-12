<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/14/2014
 * Time: 4:55 AM
 */
class Tutor_has_course_has_schedule
{


	public static function addCourses($id, $coursesIds, $termId) {
		self::validateCoursesId($coursesIds);
		Term::validateId($termId);
		Tutor_has_course_has_termFetcher::insertMany($id, $coursesIds, $termId);
	}

	public static function validateCoursesId($coursesId) {
		foreach ($coursesId as $courseId) {
			Course::validateId($courseId);
		}
	}


}