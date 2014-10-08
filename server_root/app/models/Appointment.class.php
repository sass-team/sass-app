<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/16/2014
 * Time: 10:58 PM
 */
class Appointment
{
	const LABEL_MESSAGE_PENDING = "pending";
	const LABEL_COLOR_WARNING = "warning";
	const LABEL_MESSAGE_TUTOR_CANCELED = "canceled by tutor";
	const LABEL_MESSAGE_STUDENT_CANCELED = "canceled by student";
	const LABEL_MESSAGE_STUDENT_NO_SHOW = "no show by student";
	const LABEL_MESSAGE_TUTOR_NO_SHOW = "no show by tutor";

	const LABEL_COLOR_SUCCESS = "success";
	const LABEL_COLOR_CANCELED = "danger";
	const LABEL_COLOR_PENDING = "default";


	const LABEL_MESSAGE_COMPLETE = "complete";

	public static function add($db, $dateStart, $dateEnd, $courseId, $studentsIds, $tutorId, $instructorsIds, $termId,
	                           $secretaryName) {
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
		self::validateDates($db, $tutorId, $dateStart, $dateEnd);

		$appointmentId = AppointmentFetcher::insert($db, $dateStart, $dateEnd, $courseId, $studentsIds, $tutorId, $instructorsIds, $termId);
		Mailer::sendTutorNewAppointment($db, $appointmentId, $secretaryName);
	}

	public static function validateDates($db, $tutorId, $startDate, $endDate) {
		$nowDate = new DateTime();

		if ($nowDate > $startDate) throw new Exception("Starting datetime cannot be less than current datetime.");
		if (($endDate->getTimestamp() - $startDate->getTimestamp()) * 60 < 30) throw new Exception("Minimum duration of an appointment is 30min.");
		if (AppointmentFetcher::existsTutorsAppointmentsBetween($db, $tutorId, $startDate, $endDate)) {
			throw new Exception("There is a conflict with the start/end date with another appointment for selected tutor.");
		}
	}

	public static function  getCalendarAllAppointmentsOnTerm($db, $termId) {
		Term::validateId($db, $termId);

		$appointmentHours = Appointment::getTutorsOnTerm($db, $termId);
		$appointmentHoursJSON = array();
		foreach ($appointmentHours as $appointmentHour) {
			$appointmentTitle = $appointmentHour[CourseFetcher::DB_COLUMN_CODE] . " - " .
				$appointmentHour[UserFetcher::DB_COLUMN_FIRST_NAME] . " " . $appointmentHour[UserFetcher::DB_COLUMN_LAST_NAME];

			$students = AppointmentHasStudentFetcher::retrieveStudentsWithAppointment($db, $appointmentHour[AppointmentFetcher::DB_COLUMN_ID]);
			$appointmentTitle .= " - ";
			foreach ($students as $student) {
				$appointmentTitle .= $student[StudentFetcher::DB_TABLE . "_" . StudentFetcher::DB_COLUMN_FIRST_NAME] . " " .
					$student[StudentFetcher::DB_TABLE . "_" . StudentFetcher::DB_COLUMN_LAST_NAME] . ", ";
			}
			$appointmentTitle = rtrim($appointmentTitle, ", ");

			$startDate = new DateTime($appointmentHour[AppointmentFetcher::DB_COLUMN_START_TIME]);
			$endDate = new DateTime($appointmentHour[AppointmentFetcher::DB_COLUMN_END_TIME]);
			$appointmentUrl = "http://" . $_SERVER['SERVER_NAME'] . "/appointments/" . $appointmentHour[UserFetcher::DB_COLUMN_ID];

			$appointmentHoursJSON[] = array('title' => $appointmentTitle, 'start' => $startDate->format('Y-m-d H:i:s'), 'end' =>
				$endDate->format('Y-m-d H:i:s'), 'allDay' => false, 'url' => $appointmentUrl, 'color' => '#e5412d');
		}

		return json_encode($appointmentHoursJSON);
	}

	public static function getTutorsOnTerm($db, $termId) {
		Term::validateId($db, $termId);
		return AppointmentFetcher::retrieveTutors($db, $termId);
	}

	public static function getCalendarSingleTutorAppointments($db, $tutorId, $termId) {
		Tutor::validateId($db, $tutorId);
		Term::validateId($db, $termId);

		$appointmentHours = Appointment::getAllForSingleTutor($db, $tutorId, $termId);
		$appointmentHoursJSON = array();
		foreach ($appointmentHours as $appointmentHour) {
			$appointmentTitle = $appointmentHour[CourseFetcher::DB_COLUMN_CODE] . " - " .
				$appointmentHour[UserFetcher::DB_COLUMN_FIRST_NAME] . " " . $appointmentHour[UserFetcher::DB_COLUMN_LAST_NAME];

			$students = AppointmentHasStudentFetcher::retrieveStudentsWithAppointment($db, $appointmentHour[AppointmentFetcher::DB_COLUMN_ID]);
			$appointmentTitle .= " - ";
			foreach ($students as $student) {
				$appointmentTitle .= $student[StudentFetcher::DB_TABLE . "_" . StudentFetcher::DB_COLUMN_LAST_NAME] . " " .
					$student[StudentFetcher::DB_TABLE . "_" . StudentFetcher::DB_COLUMN_LAST_NAME] . ", ";
			}
			$appointmentTitle = rtrim($appointmentTitle, ", ");

			$startDate = new DateTime($appointmentHour[AppointmentFetcher::DB_COLUMN_START_TIME]);
			$endDate = new DateTime($appointmentHour[AppointmentFetcher::DB_COLUMN_END_TIME]);
			$appointmentUrl = "http://" . $_SERVER['SERVER_NAME'] . "/appointments/" . $appointmentHour[UserFetcher::DB_COLUMN_ID];

			$appointmentHoursJSON[] = array('title' => $appointmentTitle, 'start' => $startDate->format('Y-m-d H:i:s'), 'end' =>
				$endDate->format('Y-m-d H:i:s'), 'allDay' => false, 'url' => $appointmentUrl, 'color' => '#e5412d');
		}

		return json_encode($appointmentHoursJSON);
	}

	public static function getAllForSingleTutor($db, $tutorId, $termId) {
		Tutor::validateId($db, $tutorId);
		Term::validateId($db, $termId);
		return AppointmentFetcher::retrieveAllForSingleTutor($db, $tutorId, $termId);
	}

	public static function getSingle($db, $id) {
		self::validateId($db, $id);
		return AppointmentFetcher::retrieveSingle($db, $id);
	}

	public static function validateId($db, $id) {
		if (is_null($id) || !preg_match("/^[0-9]+$/", $id)) throw new Exception("Data has been tempered. Aborting process.");

		if (!AppointmentFetcher::existsId($db, $id)) {
			// TODO: sent email to developer relevant to this error.
			throw new Exception("Either something went wrong with a database query, or you're trying to hack this app. In either case, the developers were just notified about this.");
		}
	}

	public static function countWithLabelMessage($appopintments, $labelMessage) {
		$count = 0;
		foreach ($appopintments as $appopintment) {
			if (strcmp($appopintment[AppointmentFetcher::DB_COLUMN_LABEL_MESSAGE], $labelMessage) === 0) {
				$count++;
			}
		}

		return $count;
	}

	public static function countTutorsWithLabelMessage($tutorId, $appointments, $labelMessage) {
		$count = 0;
		foreach ($appointments as $appointment) {
			if (strcmp($tutorId, $appointment[AppointmentFetcher::DB_COLUMN_TUTOR_USER_ID]) === 0 &&
				strcmp($appointment[AppointmentFetcher::DB_COLUMN_LABEL_MESSAGE], $labelMessage) === 0
			) {
				$count++;
			}
		}

		return $count;
	}

	public static function updateStudents($db, $appointmentId, $oldStudentsIds, $newStudentsId) {
		if (!isset($newStudentsId) || empty($newStudentsId)) {
			throw new Exception("Data have been malformed.");
		}

		Student::validateIds($db, $newStudentsId);
		$update = false;
		$totStudents = sizeof($newStudentsId);

		for ($i = 0; $i < $totStudents; $i++) {
			$newStudentId = $newStudentsId[$i];
			$oldStudentId = $oldStudentsIds[$i][AppointmentHasStudentFetcher::DB_COLUMN_STUDENT_ID];
			if (strcmp($newStudentId, $oldStudentId) !== 0) {
				if (self::hasStudentId($newStudentId, $oldStudentsIds)) throw new Exception("Student already exist on appointment." . var_dump($oldStudentsIds));
			}
		}

		// check if there is a need to update data
		for ($i = 0; $i < $totStudents; $i++) {
			$newStudentId = $newStudentsId[$i];
			$oldStudentId = $oldStudentsIds[$i][AppointmentHasStudentFetcher::DB_COLUMN_STUDENT_ID];
			if (strcmp($newStudentId, $oldStudentId) !== 0) {
				AppointmentHasStudentFetcher::updateStudentId($db, $oldStudentId, $newStudentId, $appointmentId);
				// need to retrieve new data
				$update = true;
				break;
			}
		}

		return $update;

	}

	public static function hasStudentId($studentId, $studentsIds) {
		foreach ($studentsIds as $student) {
			if (strcmp($student[AppointmentHasStudentFetcher::DB_COLUMN_STUDENT_ID], $studentId) === 0) return true;
		}

		return false;
	}

	public static function getAllStudentsWithAppointment($db, $id) {
		self::validateId($db, $id);
		return AppointmentHasStudentFetcher::retrieveStudentsWithAppointment($db, $id);
	}

	public static function countWithLabelMessageS($appopintments, $labelMessages) {
		$count = 0;
		foreach ($appopintments as $appopintment) {
			foreach ($labelMessages as $labelMessage) {
				if (strcmp($appopintment[AppointmentFetcher::DB_COLUMN_LABEL_MESSAGE], $labelMessage) === 0) {
					$count++;
				}
			}
		}

		return $count;
	}

	public static function countTutorsWithLabelMessageS($tutorId, $appointments, $labelMessages) {
		$count = 0;
		foreach ($appointments as $appointment) {
			foreach ($labelMessages as $labelMessage) {
				if (strcmp($tutorId, $appointment[AppointmentFetcher::DB_COLUMN_TUTOR_USER_ID]) === 0 &&
					strcmp($appointment[AppointmentFetcher::DB_COLUMN_LABEL_MESSAGE], $labelMessage) === 0
				) {
					$count++;
				}
			}
		}

		return $count;
	}
}