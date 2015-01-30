$(function () {
	// http://momentjs.com/docs/#/manipulating/add/
	// http://eonasdan.github.io/bootstrap-datetimepicker
	moment().format();

	var $courseId = $("#courseId");
	var $termId = $("#termId");
	var $tutorId = $("#tutorId");
	var $dateTimePickerStart = $('#dateTimePickerStart');
	var $dateTimePickerEnd = $('#dateTimePickerEnd');
	var $dateTimePickerEnd2 = $dateTimePickerEnd;
	var $instructorId = $("#instructorId1");
	var $studentId = $("#studentId1");
	var $calendarTitle = $('#calendar-title');
	var $calendar = $("#appointments-schedule-calendar");
	var $pendingAppointmentsBtn = $('#show-pending-appointments');
	var $workingHoursBtn = $("#show-only-working-hours");

	// Server side data
	var isAdmin = $('#isAdmin').val();
	var userId = $('#userId').val();
	var isBtnAddStudentPrsd = $('#isBtnAddStudentPrsd').val();
	var tutorIdServerSide = $('#tutorIdServerSide').val();
	var courseIdServerSide = $('#courseIdServerSide').val();
	var dateTimePickerStartServerSide = $('#dateTimePickerStartStartServerSide').val();
	var dateTimePickerEndServerSide = $('#dateTimePickerEndServerSide').val();
	var domainName = $('#domainName').val();

	// Config data
	var startDateDefault;
	var spinner;
	if (moment().minute() >= 30) {
		startDateDefault = moment().add('1', 'hours');
		startDateDefault.minutes(0);
	}
	else {
		startDateDefault = moment();
		startDateDefault.minutes(30);
	}
	var minimumStartDate = startDateDefault.clone();
	minimumStartDate.subtract('31', 'minutes');
	var minimumMaxDate = moment().add('14', 'day');
	var endDateDefault = startDateDefault.clone();
	endDateDefault.add('30', 'minutes');
	var minimumEndDate = endDateDefault.clone();
	minimumEndDate.subtract('31', 'minutes');
	// Config settings
	// spinner config
	var opts = {
		lines    : 13, // The number of lines to draw
		length   : 20, // The length of each line
		width    : 10, // The line thickness
		radius   : 30, // The radius of the inner circle
		corners  : 1, // corner roundness (0..1)
		rotate   : 0, // the rotation offset
		direction: 1, // 1: clockwise, -1: counterclockwise
		color    : '#000', // #rgb or #rrggbb or array of colors
		speed    : 2.2, // rounds per second
		trail    : 60, // afterglow percentage
		shadow   : false, // whether to render a shadow
		hwaccel  : false, // whether to use hardware acceleration
		className: 'spinner', // the css class to assign to the spinner
		zIndex   : 2e9, // the z-index (defaults to 2000000000)
		top      : '50%', // top position relative to parent
		left     : '50%' // left position relative to parent
	};
	var stack_bottomright = {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25};
	var pnotifySettingsInfo = {
		title        : 'Calendar Notice',
		text         : '',
		type         : 'info',
		delay        : 3000,
		history      : {history: true, menu: true},
		addclass     : "stack-bottomright", // This is one of the included default classes.
		stack        : stack_bottomright,
		animation    : "slide",
		animate_speed: "slow"
	};
	var pnotifySettingsWarning = {
		title        : 'Calendar Warning',
		text         : '',
		type         : 'error',
		delay        : 7000,
		history      : {history: true, menu: true},
		addclass     : "stack-bottomright", // This is one of the included default classes.
		stack        : stack_bottomright,
		animation    : "slide",
		animate_speed: "slow"
	};
	// init $calendar
	$calendar.fullCalendar({
		header      : {
			left  : 'prev,next',
			center: 'title',
			right : 'agendaWeek,month,agendaDay'
		},
		weekends    : false, // will hide saturdays and sundays
		minTime     : "09:00:00",
		maxTime     : "23:59:59",
		defaultView : "agendaWeek",
		editable    : false,
		droppable   : false,
		eventSources: []
	});
	$dateTimePickerStart.datetimepicker({
		defaultDate       : startDateDefault,
		minDate           : (userId == 9 || isAdmin) ? false : minimumStartDate,
		maxDate           : minimumMaxDate,
		minuteStepping    : 30,
		daysOfWeekDisabled: [0, 6],
		sideBySide        : true,
		strict            : true
	});
	$dateTimePickerEnd.datetimepicker({
		defaultDate       : endDateDefault,
		minDate           : (userId == 9 || isAdmin) ? false : minimumEndDate,
		minuteStepping    : 30,
		daysOfWeekDisabled: [0, 6],
		sideBySide        : true,
		strict            : true
	});
	$termId.select2();

	// Place holders
	$courseId.select2({
		placeholder: "Select one",
		allowClear : false
	});
	$instructorId.select2({
		placeholder: "Select one",
		allowClear : false
	});
	$studentId.select2({
		placeholder: "Select one",
		allowClear : false
	});
	$tutorId.select2({
		placeholder: "Course required",
		allowClear : false
	});

	// Event Listeners
	$courseId.click(function () {
		try {
			retrieveTutors();
			reloadCalendar('getAppointmentsAndSchedulesForCourse');
			$courseId.select2({
				placeholder: $courseId.select2("val")
			});
		}
		catch (err) {
			$tutorId.select2({
				placeholder: err.message
			});
		}
	});
	$termId.click(function () {
		try {
			retrieveCourses();
			retrieveTutors();
			reloadCalendar('term_change');
		}
		catch (err) {
			// clear options
			$tutorId.empty(); // remove old options
			// add new options
			$tutorId.append("<option></option>");
			$tutorId.select2({
				placeholder: err.message
			});
		}
	});
	$dateTimePickerStart.on("dp.change", function (e) {
		var newEndDateDefault = $(this).data("DateTimePicker").getDate().clone();

		newEndDateDefault.add('30', 'minutes');
		var newMinimumEndDate = newEndDateDefault.clone();
		newMinimumEndDate.subtract('31', 'minutes');

		$dateTimePickerEnd2.data("DateTimePicker").setMinDate(newMinimumEndDate);
		$dateTimePickerEnd2.data("DateTimePicker").setDate(newEndDateDefault);
	});
	$tutorId.click(function () {
		try {
			reloadCalendar('getAppointmentsAndSchedulesForSingleTutor');
		} catch (err) {
			// clear options
			$tutorId.empty().append("<option></option>");
			$tutorId.select2({
				placeholder: err.message
			});
		}
	});
	$("#show-only-working-hours").on('click', function () {
		reloadCalendar("working_hours_only");
	});
	$("#show-only-appointments").on('click', function () {
		reloadCalendar("appointments_only");
	});
	$('.addButton').on('click', function () {
		var index = $(this).data('index');

		if (!index) {
			index = 1;
			$(this).data('index', 1);
		}
		index++;
		$(this).data('index', index);

		var template = $(this).attr('data-template'),
			$templateEle = $('#' + template + 'Template'),
			$row = $templateEle.clone().removeAttr('id').insertBefore($templateEle).removeClass('hide'),
			$el = $row.find('input').eq(0).attr('name', template + '[]');

		var newStudentId = 'studentId' + index;
		var newInstructorId = 'instructorId' + index;

		var $curStudentRow = $('#student-instructor');
		//$curStudentRow.addClass('row');
		//$curStudentRow.parent.addClass('row');
		$studentId.select2("destroy");
		$instructorId.select2("destroy");

		var newRow = $curStudentRow.clone();
		$('#studentId1', newRow).attr('id', newStudentId);
		$('#instructorId1', newRow).attr('id', newInstructorId);

		$row.prepend(newRow);

		$("#studentId1").select2({
			placeholder: "Select one"
		});
		$("#instructorid1").select2({
			placeholder: "Select one"
		});
		$("#" + newStudentId).select2({
			placeholder: "Select one"
		});
		$("#" + newInstructorId).select2({
			placeholder: "Select one"
		});

		$row.on('click', '.removeButton', function (e) {
			$row.remove();
			newRow.remove();
		});
	});
	$('#toggle-details-calendar-partial').change(function () {
		var isCheckedDetails = $(this).prop('checked');
		var $portletDetails = $('#portletDetails');

		if (!isCheckedDetails) {
			$portletDetails.addClass('hide');
			return;
		}

		$portletDetails.removeClass('hide');
	});
	$pendingAppointmentsBtn.click(function () {
		reloadCalendar("getPendingAppointments");
	});
	// Custom functions
	function reloadCalendar(choice) {
		var calendar = document.getElementById('appointments-schedule-calendar');
		var spinner;

		$calendarTitle.text("");
//		$calendarTitle.append("<i class='fa fa-circle-o-notch fa-spin'></i>");

		if ($termId.val() === null || !$termId.select2('val').match(/^[0-9]+$/)) {
			throw new Error("term is missing");
		}

		// Require course id and term id

		/**
		 *
		 * @param subdomain
		 * @param methodType
		 * @param dataTypeFile
		 * @param serverAction
		 * @param termId
		 * @returns {{url: *, type: *, dataType: *, data: {action: *, termId: *}, error: Function, beforeSend: Function, complete: Function}}
		 */
		function formatCalendarEventSource(subdomain, methodType, dataTypeFile, serverAction, data) {
			return {
				url       : domainName + subdomain,
				type      : methodType,
				dataType  : dataTypeFile,
				data      : {
					action  : serverAction,
					termId  : data.termId,
					courseId: data.courseId,
					tutorId : data.tutorId
				},
				error     : function (xhr, status, error) {
					pnotifySettingsWarning.text = "Could not connect to database. Please try to refresh the web page.";
					new PNotify(pnotifySettingsWarning);
				},
				beforeSend: function () {
					if (spinner == null) {
						spinner = new Spinner(opts).spin(calendar);
					}

				},
				complete  : function () {
					if (spinner != null) {
						spinner.stop();
						spinner = null;
					}
				}
			};
		}

		var data = {
			termId: $termId.select2('val'), courseId: $courseId.select2('val'), tutorId: $tutorId.select2('val')
		};
		var allAppointmentsCalendar = formatCalendarEventSource('/api/appointments', 'get', 'json', 'getAppointments', data);
		var allSchedulesCalendar = formatCalendarEventSource('/api/schedules', 'get', 'json', 'getSchedules', data);
		var getAppointmentsWithCourse = formatCalendarEventSource('/api/appointments', 'get', 'json', 'getAppointmentsWithCourse', data);
		var getSchedulesWithCourse = formatCalendarEventSource('/api/schedules', 'get', 'json', 'getSchedulesWithCourse', data);
		var getAppointmentsForTutor = formatCalendarEventSource('/api/appointments', 'get', 'json', 'getAppointmentsForTutor', data);
		var getScheduleForTutor = formatCalendarEventSource('/api/schedules', 'get', 'json', 'getScheduleForTutor', data);
		var getAppointmentsForCourseAndTutor = formatCalendarEventSource('/api/appointments', 'get', 'json', 'getAppointmentWithCourseAndTutor', data);
		var pendingAppointments = formatCalendarEventSource('/api/appointments', 'get', 'json', 'getPendingAppointments', data);
		var pendingAppointmentsWithCourseAndTutor = formatCalendarEventSource('/api/appointments', 'get', 'json', 'getPendingAppointWithCourseAndTutor', data);
		var pendingAppointmentsWithCourse = formatCalendarEventSource('/api/appointments', 'get', 'json', 'getPendingAppointmentsWithCourse', data);

		$calendar.fullCalendar('removeEventSource', allAppointmentsCalendar);
		$calendar.fullCalendar('removeEventSource', allSchedulesCalendar);
		$calendar.fullCalendar('removeEventSource', getAppointmentsWithCourse);
		$calendar.fullCalendar('removeEventSource', getSchedulesWithCourse);
		$calendar.fullCalendar('removeEventSource', getAppointmentsForTutor);
		$calendar.fullCalendar('removeEventSource', getScheduleForTutor);
		$calendar.fullCalendar('removeEventSource', getAppointmentsForCourseAndTutor);
		$calendar.fullCalendar('removeEventSource', pendingAppointments);
		$calendar.fullCalendar('removeEventSource', pendingAppointmentsWithCourseAndTutor);
		$calendar.fullCalendar('removeEventSource', pendingAppointmentsWithCourse);

		switch (choice) {
			case 'all_appointments_schedule':
			case 'term_change':
				$calendar.fullCalendar('addEventSource', allAppointmentsCalendar);
				$calendar.fullCalendar('addEventSource', allSchedulesCalendar);
				break;
			case 'getAppointmentsAndSchedulesForSingleTutor':
				if (!$tutorId.select2('val').match(/^[0-9]+$/)) {
					pnotifySettingsWarning.text = "Tutor is missing";
					new PNotify(pnotifySettingsWarning);
				}
				if (!$courseId.select2("val").match(/^[0-9]+$/)) {
					pnotifySettingsWarning.text = "Course is missing";
					new PNotify(pnotifySettingsWarning);
				}
				if (!$termId.select2("val").match(/^[0-9]+$/)) {
					pnotifySettingsWarning.text = "Term is missing";
					new PNotify(pnotifySettingsWarning);
				}
				$calendar.fullCalendar('addEventSource', getAppointmentsForCourseAndTutor);
				$calendar.fullCalendar('addEventSource', getScheduleForTutor);
				pnotifySettingsInfo.text = "Retrieved appointments and working hours for " +
				$tutorId.select2('data').text + " for course " + $courseId.select2('data').text;
				new PNotify(pnotifySettingsInfo);
				break;
			case 'getAppointmentsAndSchedulesForCourse':
				if (!$courseId.select2("val").match(/^[0-9]+$/)) {
					throw new Error("Course is missing");
				}
				if (!$termId.select2("val").match(/^[0-9]+$/)) {
					throw new Error("Term is missing");
				}
				$calendar.fullCalendar('addEventSource', getSchedulesWithCourse);
				$calendar.fullCalendar('addEventSource', getAppointmentsWithCourse);

				pnotifySettingsInfo.text = "Retrieved all appointments and working hours for " + $courseId.select2('data').text;
				new PNotify(pnotifySettingsInfo);
				break;
			case 'working_hours_only':
				if (!$courseId.select2('val').match(/^[0-9]+$/) && !$tutorId.select2('val').match(/^[0-9]+$/)) {
					$calendar.fullCalendar('addEventSource', allSchedulesCalendar);
					pnotifySettingsInfo.text = "Retrieved all working hours";
					new PNotify(pnotifySettingsInfo);
				}
				else if ($courseId.select2('val').match(/^[0-9]+$/) && !$tutorId.select2('val').match(/^[0-9]+$/)) {
					$calendar.fullCalendar('addEventSource', getSchedulesWithCourse);
					pnotifySettingsInfo.text = "Retrieved all working hours for " + $courseId.select2('data').text + ".";
					new PNotify(pnotifySettingsInfo);
				}
				else if ($courseId.select2('val').match(/^[0-9]+$/) && $tutorId.select2('val').match(/^[0-9]+$/)) {
					//$calendar.fullCalendar('addEventSource', getSchedulesWithCourse);
					$calendar.fullCalendar('addEventSource', getScheduleForTutor);
					pnotifySettingsInfo.text = "Retrieved all working hours for " + $tutorId.select2('data').text + ".";
					new PNotify(pnotifySettingsInfo);
				}
				break;
			case 'appointments_only':
				if (!$courseId.select2('val').match(/^[0-9]+$/) && !$courseId.select2('val').match(/^[0-9]+$/)) {
					$calendar.fullCalendar('addEventSource', allAppointmentsCalendar);
					pnotifySettingsInfo.text = "Retrieved the appointments of all tutors.";
					new PNotify(pnotifySettingsInfo);
					break;
				}
				else if ($courseId.select2('val').match(/^[0-9]+$/) && !$tutorId.select2('val').match(/^[0-9]+$/)) {
					$calendar.fullCalendar('addEventSource', getAppointmentsWithCourse);
					pnotifySettingsInfo.text = "Retrieved the appointments with course " + $courseId.select2('data').text + ".";
					new PNotify(pnotifySettingsInfo);
				}
				else if ($courseId.select2('val').match(/^[0-9]+$/) && $tutorId.select2('val').match(/^[0-9]+$/)) {
					$calendar.fullCalendar('addEventSource', getAppointmentsForCourseAndTutor);
					pnotifySettingsInfo.text = "Retrieved the appointments of " + $tutorId.select2('data').text + ".";
					new PNotify(pnotifySettingsInfo);
				}
				break;
			case 'getPendingAppointments':
				// all pending appointments
				if (!$tutorId.select2('val').match(/^[0-9]+$/) && !$courseId.select2('val').match(/^[0-9]+$/)) {
					$calendar.fullCalendar('addEventSource', pendingAppointments);
					pnotifySettingsInfo.text = "Retrieved all pending appointments.";
					new PNotify(pnotifySettingsInfo);
					break;
				}
				/// pending appointments for selected course
				if (!$tutorId.select2('val').match(/^[0-9]+$/) && $courseId.select2('val').match(/^[0-9]+$/)) {
					$calendar.fullCalendar('addEventSource', pendingAppointmentsWithCourse);
					pnotifySettingsInfo.text = "Retrieved all pending appointments for " + $courseId.select2('data').text;
					new PNotify(pnotifySettingsInfo);
					break;
				}
				// pending appointments for selected tutor
				if ($tutorId.select2('val').match(/^[0-9]+$/) && $courseId.select2('val').match(/^[0-9]+$/)) {
					$calendar.fullCalendar('addEventSource', pendingAppointmentsWithCourseAndTutor);
					pnotifySettingsInfo.text = "Retrieved all pending appointments for " + $tutorId.select2('data').text
					+ " for course " + $courseId.select2('data').text;
					new PNotify(pnotifySettingsInfo);
					break;
				}
				// pending appointments for selected course and tutor
				break;
			default:
				break;
		}
		$calendar.fullCalendar('refetchEvents');
	}

	function retrieveTutors() {
		var courseId = $("#courseId").select2("val");
		var termId = $("#termId").select2("val");

		if (!courseId.match(/^[0-9]+$/)) {
			pnotifySettingsInfo.text = "Course is missing";
			new PNotify(pnotifySettingsInfo);
		}
		if (!termId.match(/^[0-9]+$/)) {
			pnotifySettingsInfo.text = "Term is missing";
			new PNotify(pnotifySettingsInfo);
		}

		var $labelInstructorText = $('#label-instructor-text');
		$labelInstructorText.text("");
		$labelInstructorText.append("<i class='fa fa-circle-o-notch fa-spin'></i>");

		var data = {
			"action"  : "tutor_has_courses",
			"courseId": courseId,
			"termId"  : termId
		};
		data = $(this).serialize() + "&" + $.param(data);

		$.ajax({
			type    : "get",
			dataType: "json",
			url     : domainName + "/api/courses",
			data    : data,
			success : function (indata) {
				// reset label test
				$('#label-instructor-text').text("Tutors");

				// prepare new data for options
				var newTutors = [];
				$.each(indata, function (idx, obj) {
					newTutors.push({
						id  : obj.id,
						text: obj.f_name + " " + obj.l_name
					});
				});

				// clear options
				var $el = $("#tutorId");
				$el.empty(); // remove old options

				// add new options
				$el.append("<option></option>");
				$.each(newTutors, function (key, value) {
					$el.append($("<option></option>")
						.attr("value", value.id).text(value.text));
				});

				var placeholder = jQuery.isEmptyObject(indata) ? "Not tutors for select course" : "Select one";
				$el.select2({
					placeholder: placeholder,
					allowClear : false
				});

				if (isBtnAddStudentPrsd) {
					$tutorId.select2("val", tutorIdServerSide);
				}
			},
			error   : function (e) {
				$labelInstructorText.text("connection errors.");
			}
		});
	}

	function retrieveCourses() {
		var termId = $("#termId").select2("val");
		if (!termId.match(/^[0-9]+$/)) {
			throw new Error("term is missing");
		}

		var $labelCourseText = $('#label-course-text');
		$labelCourseText.text("");
		$labelCourseText.append("<i class='fa fa-circle-o-notch fa-spin'></i>");

		var data = {
			"action": "courses_on_term",
			"termId": termId
		};

		data = $(this).serialize() + "&" + $.param(data);

		$.ajax({
			type    : "get",
			dataType: "json",
			url     : domainName + "/api/courses",
			data    : data,
			success : function (indata) {
				console.log(indata);
				// reset label test
				$('#label-course-text').text("courses");

				// prepare new data for options
				var newCourses = [];
				$.each(indata, function (idx, obj) {
					newCourses.push({
						id  : obj.id,
						text: obj.code + " - " + obj.name
					});
				});

				// clear options
				var $el = $courseId;
				$el.empty(); // remove old options

				// add new options
				$el.append("<option></option>");
				$.each(newCourses, function (key, value) {
					$el.append($("<option></option>")
						.attr("value", value.id).text(value.text));
				});

				var placeholder = jQuery.isEmptyObject(indata) ? "no courses found" : "Select one";
				$el.select2({
					placeholder: placeholder,
					allowClear : false
				});
				if (isBtnAddStudentPrsd) {
					$tutorId.select2("val", tutorIdServerSide);
				}
			},
			error   : function (e) {
				$('#label-course-text').text("connection errors.");
			}
		});
	}

	if (isBtnAddStudentPrsd) {
		$courseId.select2("val", courseIdServerSide);
	}
	//retrieveTutors();
	//try {
	//	reloadCalendar('getAppointmentsAndSchedulesForSingleTutor');
	//} catch (err) {
	//	// clear options
	//	$tutorId.empty().append("<option></option>");
	//	$tutorId.select2({
	//		placeholder: err.message
	//	});
	//}
	if (isBtnAddStudentPrsd) {
		$dateTimePickerStart.data("DateTimePicker").setDate(dateTimePickerStartServerSide);
		$dateTimePickerEnd.data("DateTimePicker").setDate(dateTimePickerStartServerSide);
	}

	reloadCalendar("appointments_only");

	//$calendar.fullCalendar({
	//	events: domainName + '/api/appointments',
	//	action: 'getAppointments',
	//	termId: $termId.val()
	//})
	// ;

});