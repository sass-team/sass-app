<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/26/2014
 * Time: 9:50 PM
 */
class Report
{

	const LABEL_COLOR_WARNING = "warning";


	public static function getAllWithAppointmentId($db, $appointmentId) {
		Appointment::validateId($db, $appointmentId);
		return ReportFetcher::retrieveAllAllWithAppointmentId($db, $appointmentId);
	}

	public static function validateId($db, $id) {
		if (!preg_match('/^[0-9]+$/', $id) || !ReportFetcher::existsId($db, $id)) {
			throw new Exception("Data tempering detected.
			<br/>You&#39;re trying to hack this app.<br/>Developers are being notified about this.<br/>Expect Us.");
		}
	}

	public static function getSingle($db, $reportId) {
		self::validateId($db, $reportId);
		return ReportFetcher::retrieveSingle($db, $reportId);
	}

} 