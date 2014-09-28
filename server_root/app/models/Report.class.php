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

	public static function updateProjectTopicOtherText($db, $reportId, $oldText, $newText) {
		if (strcmp($oldText, $newText) === 0) return false;
		self::validateId($db, $reportId);
		self::validateTextarea($newText);
		return ReportFetcher::updateSingle($db, $reportId, $newText, ReportFetcher::DB_COLUMN_PROJECT_TOPIC_OTHER);
	}

	public static function updateOtherText($db, $reportId, $oldText, $newText) {
		if (strcmp($oldText, $newText) === 0) return false;
		self::validateId($db, $reportId);
		self::validateTextarea($newText);
		return ReportFetcher::updateSingle($db, $reportId, $newText, ReportFetcher::DB_COLUMN_OTHER_TEXT_AREA);
	}

	public static function updateStudentsConcerns($db, $reportId, $oldText, $newText) {
		if (strcmp($oldText, $newText) === 0) return false;
		self::validateId($db, $reportId);
		self::validateTextarea($newText);
		return ReportFetcher::updateSingle($db, $reportId, $newText, ReportFetcher::DB_COLUMN_STUDENT_CONCERNS);
	}

	public static function updateRelevantFeedbackGuidelines($db, $reportId, $oldText, $newText) {
		if (strcmp($oldText, $newText) === 0) return false;
		self::validateId($db, $reportId);
		self::validateTextarea($newText);
		return ReportFetcher::updateSingle($db, $reportId, $newText, ReportFetcher::DB_COLUMN_RELEVANT_FEEDBACK_OR_GUIDELINES);
	}


	public static function validateTextarea($text) {
		if (!preg_match("/^[\\w\t\n\r\\ .,\\-]{0,512}$/", $text)) {
			throw new Exception("Textareas can contain only <a href='http://www.regular-expressions.info/shorthand.html'
			target='_blank'>word characters</a>, spaces, carriage returns, line feeds and special characters <strong>.,-2</strong> of max size 512 characters.");
		}

	}


} 