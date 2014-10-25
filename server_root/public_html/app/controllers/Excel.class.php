<?php

/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 10/24/2014
 * Time: 8:31 PM
 */
class Excel
{
	const TITLE_VISIT_LOG = "Visit Log";

	private static $styleVisitLogHeader = array(
		'font' => array(
			'bold' => true,
			'name' => 'Times New Roman',
			'size' => 12
		),
		'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
		),
		'borders' => array(
			'bottom' => array(
				'style' => PHPExcel_Style_Border::BORDER_THICK,
			),
			'right' => array(
				'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
			)
		),
//		'fill' => array(
//			'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
//			'rotation' => 90,
//			'startcolor' => array(
//				'argb' => 'FFA0A0A0',
//			),
//			'endcolor' => array(
//				'argb' => 'FFFFFFFF',
//			),
//		),
	);

	public static function exportAppointmentsOnTerm($termId) {
		Term::validateId($termId);

		require_once ROOT_PATH . 'plugins/PHPExcel_1.8.0_doc/Classes/PHPExcel.php';
		date_default_timezone_set('Europe/Athens');

		$allAppointments = AppointmentFetcher::retrieveForTerm($termId);
		$appointmentsHaveStudents = AppointmentHasStudentFetcher::retrieveForTerm($termId);
		$primaryFocusOfConferences = PrimaryFocusOfConferenceFetcher::retrieveForTerm($termId);

		$termData = TermFetcher::retrieveSingle($termId);
		$termStart = new DateTime($termData[TermFetcher::DB_COLUMN_START_DATE]);
		$termEnd = new DateTime($termData[TermFetcher::DB_COLUMN_END_DATE]);
		$termName = $termData[TermFetcher::DB_COLUMN_NAME];

//		$termWeekStart = -(int)$termStart->format('W');
//		$termWeekEnd = (int)($termEnd->format('W'));

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator(App::NAME)
			->setLastModifiedBy(App::NAME)
			->setTitle(self::TITLE_VISIT_LOG . " - " . $termName)
			->setSubject("Appointments")
			->setDescription("List of appointments for $termName.")
			->setKeywords("office sass appointments php")
			->setCategory("Appointments");

		$sheetIndex = 0;
		for ($workingDateTime = $termStart; $workingDateTime <= $termEnd; $workingDateTime->modify('+1 week')) {
			$curWeek = $workingDateTime->format('W');
			$appointmentsWeekly = self::getAppointments($allAppointments, $curWeek);


			$startWeekDate = self::getWorkingDates($workingDateTime->format('Y'), $workingDateTime->format('W'));
			$endWeekDate = self::getWorkingDates($workingDateTime->format('Y'), $workingDateTime->format('W'), false);
			$periodTime = date("d M", strtotime($startWeekDate)) . "-" . date("d M, o", strtotime('-1 day', strtotime($endWeekDate)));


			$objPHPExcel->createSheet($sheetIndex);
			$objPHPExcel->setActiveSheetIndex($sheetIndex)
				->setCellValue('A1', 'Day')
				->setCellValue('B1', 'Date')
				->setCellValue('C1', 'Month')
				->setCellValue('D1', 'Year')
				->setCellValue('E1', 'Time')
				->setCellValue('F1', 'LF Lname')
				->setCellValue('G1', 'Stud Lname')
				->setCellValue('H1', 'Stud Fname')
				->setCellValue('I1', 'Stud ID')
				->setCellValue('J1', 'Major')
				->setCellValue('K1', 'Mobile')
				->setCellValue('L1', 'e-mail')
				->setCellValue('M1', 'Course Code')
				->setCellValue('N1', 'Course Instructor')
				->setCellValue('O1', '# of Students')
				->setCellValue('P1', 'Outcome')
				->setCellValue('Q1', 'Actual Duration')
				->setCellValue('R1', 'Purpose');
			$sheetIndex++;

			$objPHPExcel->getActiveSheet()->getStyle('A1:R1')->applyFromArray(self::$styleVisitLogHeader);
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(24);

			$objPHPExcel->getActiveSheet()->setTitle($periodTime);

			$row = 2;
			foreach ($appointmentsWeekly as $appointment) {
				$appointmentStartDateTime = new DateTime($appointment[AppointmentFetcher::DB_COLUMN_START_TIME]);
				$appointmentEndDateTime = new DateTime($appointment[AppointmentFetcher::DB_COLUMN_END_TIME]);

				$appointmentId = $appointment[AppointmentFetcher::DB_COLUMN_ID];
				$students = self::getStudentsForAppointment($appointmentsHaveStudents, $appointmentId);
				$numOfStudents = sizeof($students);

				// TODO: REMOVE autosize from loop
				$col = 0;    // day
				$day = $appointmentStartDateTime->format('l');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $day);

				$col++; // date
				$date = $appointmentStartDateTime->format('d');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $date);

				$col++; // month
				$month = $appointmentStartDateTime->format('M');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $month);

				$col++; // year
				$year = $appointmentStartDateTime->format('Y');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $year);

				$col++; // time
				$time = $appointmentStartDateTime->format('H:i');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $time);

				$col++; // tutor last name
				$tutorLastName = $appointment[UserFetcher::DB_COLUMN_LAST_NAME];
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $tutorLastName);

				// TODO: add student that first requested the appointment.

				$col++; // student last name
				$studentLastName = $students[0][StudentFetcher::DB_COLUMN_LAST_NAME];
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $studentLastName);

				$col++; // student first name
				$studentFirstName = $students[0][StudentFetcher::DB_COLUMN_FIRST_NAME];
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $studentFirstName);

				$col++; // student ID
				$studentId = $students[0][StudentFetcher::DB_COLUMN_STUDENT_ID];
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $studentId);

				$col++; // student major
				$majorCode = $students[0][MajorFetcher::DB_COLUMN_CODE];
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $majorCode);

				$col++; // student mobile
				$mobile = $students[0][StudentFetcher::DB_COLUMN_MOBILE];
				$objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($col, $row, $mobile);

				$col++; // student email
				$email = $students[0][StudentFetcher::DB_COLUMN_EMAIL];
				$objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($col, $row, $email);

				$col++; // course code
				$courseCode = $appointment[CourseFetcher::DB_COLUMN_CODE];
				$objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($col, $row, $courseCode);

				$col++; // instructor for first student
				$instructorLastName = $students[0][InstructorFetcher::DB_COLUMN_LAST_NAME];
				$objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($col, $row, $instructorLastName);

				$col++; // instructor for first student
				$objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($col, $row, $numOfStudents);

				$col++; // appointment outcome
				$appointmentOutcome = $appointment[AppointmentFetcher::DB_COLUMN_LABEL_MESSAGE];
				$objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($col, $row, $appointmentOutcome);

				$col++; // appointment duration
				$appointmentDuration = ($appointmentEndDateTime->getTimestamp() - $appointmentStartDateTime->getTimestamp()) / 60;
				$objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($col, $row, $appointmentDuration);

				$col++; // purpose
				$primaryFocusOfConferencesString = self::getFocusOfConferencesForAppointment($primaryFocusOfConferences, $appointmentId);

				if (!empty($primaryFocusOfConferencesString)) {
					$primaryFocusOfConferencesString = Report::convertFocusOfConferenceToString($primaryFocusOfConferencesString[0]);
					$objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($col, $row, $primaryFocusOfConferencesString);
					//$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);
				}

				switch ($appointment[AppointmentFetcher::DB_COLUMN_LABEL_COLOR]) {
					case Appointment::LABEL_COLOR_PENDING:
						$color = '888888';
						break;
					case Appointment::LABEL_COLOR_CANCELED:
						$color = 'e5412d';
						break;
					case Appointment::LABEL_COLOR_SUCCESS:
						$color = '3fa67a';
						break;
					case Appointment::LABEL_COLOR_WARNING:
						$color = 'f0ad4e';
						break;
					default:
						$color = '444';
						break;
				}

				$styleRow = array(
					'font' => array(
						'bold' => false,
						'name' => 'Times New Roman',
						'size' => 12
					),
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
					),
					'borders' => array(
						'bottom' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN,
						),
						'right' => array(
							'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
						)
					),
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
						'rotation' => 90,
						'startcolor' => array(
							'argb' => $color,
						),
						'endcolor' => array(
							'argb' => $color,
						),
					),
				);
				$objPHPExcel->getActiveSheet()->getStyle("A$row:R$row")->applyFromArray($styleRow);
				$row++;

			} // end foreach

			// enable all filters
			$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());

			// source: http://stackoverflow.com/a/5578240 - autosize all columns
			$lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
			$lastColumn++;
			for ($column = 'A'; $column != $lastColumn; $column++) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
			} // end for

		} // end foreach

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save(ROOT_PATH . "storage/excel/" . self::TITLE_VISIT_LOG . " - $termName.xlsx");

		echo date('H:i:s'), " Peak memory usage: ", (memory_get_peak_usage(true) / 1024 / 1024), " MB";

	} // end function

	public static function getAppointments($appointments, $week) {
		$appointmentsOut = [];

		foreach ($appointments as $appointment) {
			$dateTime = new DateTime($appointment[AppointmentFetcher::DB_COLUMN_START_TIME]);
			if (strcmp($week, $dateTime->format('W')) === 0) $appointmentsOut[] = $appointment;
		}

		return $appointmentsOut;
	}

	/**
	 * http://blog.ekini.net/2009/07/09/php-get-start-and-end-dates-of-a-week-from-datew/
	 *
	 * @param $year
	 * @param $week
	 * @param bool $start
	 * @return bool|string
	 */
	private static function getWorkingDates($year, $week, $start = true) {
		$from = date(Dates::DATE_FORMAT_IN, strtotime("{$year}-W{$week}-1")); //Returns the date of monday in week
		$to = date(Dates::DATE_FORMAT_IN, strtotime("{$year}-W{$week}-6"));   //Returns the date of saturday in week

		if ($start) {
			return $from;
		} else {
			return $to;
		}
		//return "Week {$week} in {$year} is from {$from} to {$to}.";
	}

	private static function getStudentsForAppointment($appointmentsHaveStudents, $appointmentId) {
		$students = [];

		foreach ($appointmentsHaveStudents as $appointmentsHaveStudent) {
			if (strcmp($appointmentsHaveStudent[AppointmentHasStudentFetcher::DB_COLUMN_APPOINTMENT_ID], $appointmentId) === 0) {
				$students[] = $appointmentsHaveStudent;
			}
		}
		return $students;
	}

	/**
	 * TODO: improve fill algorithm
	 * @param $focusOfConferences
	 * @param $appointmentId
	 * @return array
	 */
	private static function getFocusOfConferencesForAppointment($focusOfConferences, $appointmentId) {
		$students = [];

		foreach ($focusOfConferences as $focusOfConference) {
			if (strcmp($focusOfConference[AppointmentHasStudentFetcher::DB_COLUMN_APPOINTMENT_ID], $appointmentId) === 0) {
				$students[] = $focusOfConference;
			}
		}
		return $students;
	}

}