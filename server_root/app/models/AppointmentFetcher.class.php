<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/16/2014
 * Time: 8:02 PM
 */
class AppointmentFetcher
{
	const DB_TABLE = "appointment";
	const DB_COLUMN_START_TIME = "start_time";
	const DB_COLUMN_END_TIME = "end_time";
	const DB_COLUMN_COURSE_ID = "end_time";
	const DB_COLUMN_TUTOR_USER_ID = "end_time";
	const DB_COLUMN_TERM_ID = "end_time";

	public static function insert($db, $startTime, $endTime, $courseId, $tutorUserId, $termId) {
		date_default_timezone_set('Europe/Athens');

	}

} 