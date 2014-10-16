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

	const LABEL_MESSAGE_TUTOR_CANCELED = "canceled by tutor";
	const LABEL_MESSAGE_STUDENT_CANCELED = "canceled by student";
	const LABEL_MESSAGE_STUDENT_NO_SHOW = "no show by student";
	const LABEL_MESSAGE_TUTOR_NO_SHOW = "no show by tutor";
	const LABEL_MESSAGE_COMPLETE = "complete";
	const LABEL_MESSAGE_ADMIN_DISABLED = "disabled by admin";

	const LABEL_COLOR_SUCCESS = "success";
	const LABEL_COLOR_CANCELED = "danger";
	const LABEL_COLOR_PENDING = "default";
	const LABEL_COLOR_WARNING = "warning";

	public static function delete($appointmentId){
		self::validateId($appointmentId);
		return AppointmentFetcher::delete($appointmentId);
	}

	public static function updateTerm($appointmentId, $tutorId, $user, $startDate, $endDate, $newTermId, $oldTermId) {
		if (strcmp($newTermId, $oldTermId) === 0) return false;
		Term::validateId($newTermId);
		date_default_timezone_set('Europe/Athens');
		$startDate = Dates::initDateTime($startDate);
		$endDate = Dates::initDateTime($endDate);
		self::validateNewDates($user, $newTermId, $tutorId, $startDate, $endDate, $appointmentId);
		return AppointmentFetcher::updateTerm($appointmentId, $newTermId);
	}

	public static function validateNewDates($user, $termId, $tutorId, $startDate, $endDate, $existingAppointmentId = false) {
		$nowDate = new DateTime();
		date_default_timezone_set('Europe/Athens');

		// TODO: remove hardcoded $user
		if ($nowDate > $startDate && !$user->isAdmin()) throw new Exception("Starting datetime cannot be less than current datetime.");
		$minutesAppointmentDuration = ($endDate->getTimestamp() - $startDate->getTimestamp()) / 60;
		if ($minutesAppointmentDuration < 30 || $minutesAppointmentDuration > 480) throw new Exception("Appointment's duration can be between 30 min and 8 hours.");
		if (!$user->isAdmin() && !ScheduleFetcher::existsTutorsSchedulesBetween($tutorId, $termId, $startDate, $endDate)) {
			throw new Exception("There is a conflict with start/end date with tutor's schedule. ");
		}
		if (AppointmentFetcher::existsTutorsAppointmentsBetween($tutorId, $termId, $startDate, $endDate, $existingAppointmentId)) {
			throw new Exception("There is a conflict with the start/end date with another appointment for selected tutor.");
		}
	}

	public static function updateDuration($appointmentId, $termId, $tutorId, $user, $oldStartTime, $newStartTime, $newEndTime, $oldEndTime) {
		date_default_timezone_set('Europe/Athens');
		$newStartTime = Dates::initDateTime($newStartTime);
		$newEndTime = Dates::initDateTime($newEndTime);
		$oldStartTime = Dates::initDateTime($oldStartTime);
		$oldEndTime = Dates::initDateTime($oldEndTime);

		if ($newStartTime == $oldStartTime && $newEndTime == $oldEndTime) return false;

		self::validateNewDates($user, $termId, $tutorId, $newStartTime, $newEndTime, $appointmentId);
		return AppointmentFetcher::updateDuration($appointmentId, $newStartTime, $newEndTime);
	}

	public static function updateCourse($appointmentId, $oldCourseId, $newCourseId) {
		Course::validateId($newCourseId);
		if (strcmp($oldCourseId, $newCourseId) === 0) return false;

		return AppointmentFetcher::updateCourse($appointmentId, $newCourseId);
	}

	public static function updateTutor($user, $termId, $appointmentId, $oldTutorId, $newTutorId, $dateStart, $dateEnd) {
		Tutor::validateId($newTutorId);
		if (strcmp($oldTutorId, $newTutorId) === 0) return false;

		$dateStart = Dates::initDateTime($dateStart);
		$dateEnd = Dates::initDateTime($dateEnd);
		self::validateNewDates($user, $termId, $newTutorId, $dateStart, $dateEnd, $appointmentId);

		return AppointmentFetcher::updateTutor($appointmentId, $newTutorId);
	}

	public static function add($user, $dateStart, $dateEnd, $courseId, $studentsIds, $tutorId, $instructorsIds, $termId, $secretaryName) {
		$dateStart = Dates::initDateTime($dateStart);
		$dateEnd = Dates::initDateTime($dateEnd);
		Course::validateId($courseId);

		if (sizeof($studentsIds) != sizeof($instructorsIds)) {
			throw new Exception("An instructor is required for each student.");
		}

		Student::validateIds($studentsIds);
		Instructor::validateIds($instructorsIds);
		Tutor::validateId($tutorId);
		Term::validateId($termId);
		self::validateNewDates($user, $termId, $tutorId, $dateStart, $dateEnd);

		$appointmentId = AppointmentFetcher::insert($dateStart, $dateEnd, $courseId, $studentsIds, $tutorId, $instructorsIds, $termId);
		Mailer::sendTutorNewAppointment($appointmentId, $secretaryName);
	}

	public static function  getCalendarAllAppointmentsOnTerm($termId) {
		Term::validateId($termId);

		$appointmentHours = Appointment::getTutorsOnTerm($termId);
		$appointmentHoursJSON = array();
		foreach ($appointmentHours as $appointmentHour) {
			$appointmentTitle = $appointmentHour[CourseFetcher::DB_COLUMN_CODE] . " - " .
				$appointmentHour[UserFetcher::DB_COLUMN_FIRST_NAME] . " " . $appointmentHour[UserFetcher::DB_COLUMN_LAST_NAME];

			$students = AppointmentHasStudentFetcher::retrieveStudentsWithAppointment($appointmentHour[AppointmentFetcher::DB_COLUMN_ID]);
			$appointmentTitle .= " - ";
			foreach ($students as $student) {
				$appointmentTitle .= $student[StudentFetcher::DB_TABLE . "_" . StudentFetcher::DB_COLUMN_FIRST_NAME] . " " .
					$student[StudentFetcher::DB_TABLE . "_" . StudentFetcher::DB_COLUMN_LAST_NAME] . ", ";
			}
			$appointmentTitle = rtrim($appointmentTitle, ", ");

			$startDate = new DateTime($appointmentHour[AppointmentFetcher::DB_COLUMN_START_TIME]);
			$endDate = new DateTime($appointmentHour[AppointmentFetcher::DB_COLUMN_END_TIME]);
			$appointmentUrl = "http://" . $_SERVER['SERVER_NAME'] . "/appointments/" . $appointmentHour[UserFetcher::DB_COLUMN_ID];

			switch ($appointmentHour[AppointmentFetcher::DB_COLUMN_LABEL_COLOR]) {
				case Appointment::LABEL_COLOR_PENDING:
					$color = '#888888';
					break;
				case Appointment::LABEL_COLOR_CANCELED:
					$color = '#e5412d';
					break;
				case Appointment::LABEL_COLOR_SUCCESS:
					$color = '#3fa67a';
					break;
				case Appointment::LABEL_COLOR_WARNING:
					$color = '#f0ad4e';
					break;
				default:
					$color = '#444';
					break;
			}
			$appointmentHoursJSON[] = array('title' => $appointmentTitle, 'start' => $startDate->format('Y-m-d H:i:s'), 'end' =>
				$endDate->format('Y-m-d H:i:s'), 'allDay' => false, 'url' => $appointmentUrl, 'color' => $color);
		}

		return json_encode($appointmentHoursJSON);
	}

	public static function getTutorsOnTerm($termId) {
		Term::validateId($termId);
		return AppointmentFetcher::retrieveTutors($termId);
	}

	public static function getCalendarSingleTutorAppointments($tutorId, $termId) {
		Tutor::validateId($tutorId);
		Term::validateId($termId);

		$appointmentHours = Appointment::getAllForSingleTutor($tutorId, $termId);
		$appointmentHoursJSON = array();
		foreach ($appointmentHours as $appointmentHour) {
			$appointmentTitle = $appointmentHour[CourseFetcher::DB_COLUMN_CODE] . " - " .
				$appointmentHour[UserFetcher::DB_COLUMN_FIRST_NAME] . " " . $appointmentHour[UserFetcher::DB_COLUMN_LAST_NAME];

			$students = AppointmentHasStudentFetcher::retrieveStudentsWithAppointment($appointmentHour[AppointmentFetcher::DB_COLUMN_ID]);
			$appointmentTitle .= " - ";
			foreach ($students as $student) {
				$appointmentTitle .= $student[StudentFetcher::DB_TABLE . "_" . StudentFetcher::DB_COLUMN_FIRST_NAME] . " " .
					$student[StudentFetcher::DB_TABLE . "_" . StudentFetcher::DB_COLUMN_LAST_NAME] . ", ";
			}
			$appointmentTitle = rtrim($appointmentTitle, ", ");

			$startDate = new DateTime($appointmentHour[AppointmentFetcher::DB_COLUMN_START_TIME]);
			$endDate = new DateTime($appointmentHour[AppointmentFetcher::DB_COLUMN_END_TIME]);
			$appointmentUrl = "http://" . $_SERVER['SERVER_NAME'] . "/appointments/" . $appointmentHour[UserFetcher::DB_COLUMN_ID];

			switch ($appointmentHour[AppointmentFetcher::DB_COLUMN_LABEL_COLOR]) {
				case Appointment::LABEL_COLOR_PENDING:
					$color = '#888888';
					break;
				case Appointment::LABEL_COLOR_CANCELED:
					$color = '#e5412d';
					break;
				case Appointment::LABEL_COLOR_SUCCESS:
					$color = '#3fa67a';
					break;
				case Appointment::LABEL_COLOR_WARNING:
					$color = '#f0ad4e';
					break;
				default:
					$color = '#444';
					break;
			}

			$appointmentHoursJSON[] = array('title' => $appointmentTitle, 'start' => $startDate->format('Y-m-d H:i:s'), 'end' =>
				$endDate->format('Y-m-d H:i:s'), 'allDay' => false, 'url' => $appointmentUrl, 'color' => $color);
		}

		return json_encode($appointmentHoursJSON);
	}

	public static function getAllForSingleTutor($tutorId, $termId) {
		Tutor::validateId($tutorId);
		Term::validateId($termId);
		return AppointmentFetcher::retrieveAllForSingleTutor($tutorId, $termId);
	}

	public static function getSingle($id) {
		self::validateId($id);
		return AppointmentFetcher::retrieveSingle($id);
	}

	public static function validateId($id) {
		if (is_null($id) || !preg_match("/^[0-9]+$/", $id)) throw new Exception("Data has been tempered. Aborting process.");

		if (!AppointmentFetcher::existsId($id)) {
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

	public static function updateStudents($appointmentId, $oldStudentsIds, $newStudentsId) {
		if (!isset($newStudentsId) || empty($newStudentsId)) {
			throw new Exception("Data have been malformed.");
		}

		Student::validateIds($newStudentsId);
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
				AppointmentHasStudentFetcher::updateStudentId($oldStudentId, $newStudentId, $appointmentId);
				$update = true;
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

	public static function updateInstructors($appointmentId, $oldInstructorsIds, $newInstructorsIds) {
		if (!isset($newInstructorsIds) || empty($newInstructorsIds)) {
			throw new Exception("Data have been malformed.");
		}

		Instructor::validateIds($newInstructorsIds);
		$update = false;
		$totInstructors = sizeof($newInstructorsIds);
		// check if there is a need to update data
		for ($i = 0; $i < $totInstructors; $i++) {
			$newInstructorId = $newInstructorsIds[$i];
			$oldInstructorId = $oldInstructorsIds[$i][AppointmentHasStudentFetcher::DB_COLUMN_INSTRUCTOR_ID];
			$studentId = $oldInstructorsIds[$i][AppointmentHasStudentFetcher::DB_COLUMN_STUDENT_ID];
			if (strcmp($newInstructorId, $oldInstructorId) !== 0) {
				AppointmentHasStudentFetcher::updateInstructorIdForStudentId($oldInstructorId, $studentId, $newInstructorId, $appointmentId);
				// need to retrieve new data
				$update = true;
			}
		}

		return $update;
	}

	public static function getAllStudentsWithAppointment($id) {
		self::validateId($id);
		return AppointmentHasStudentFetcher::retrieveStudentsWithAppointment($id);
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