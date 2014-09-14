<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/14/2014
 * Time: 4:55 AM
 */
class Tutor_has_course
{


	public static function addCourses($db, $id, $coursesIds) {
		self::validateCoursesId($db, $coursesIds);
		Tutor_has_courseFetcher::insertMany($db, $id, $coursesIds);
	}

	public static function validateCoursesId($db, $coursesId) {
		foreach ($coursesId as $courseId) {
			Course::validateId($db, $courseId);
		}
	}


}