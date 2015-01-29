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
	var $maximizeChoices = $("#maximize-inputs");

	// Server side data
	var isAdmin = $('#isAdmin').val();
	var userId = $('#userId').val();
	var isBtnAddStudentPrsd = $('#isBtnAddStudentPrsd').val();
	var tutorIdServerSide = $('#tutorIdServerSide').val();
	var courseIdServerSide = $('#courseIdServerSide').val();
	var dateTimePickerStartServerSide = $('#dateTimePickerStartStartServerSide').val();
	var dateTimePickerEndServerSide = $('#dateTimePickerEndServerSide').val();
	var domainName = $('#domainName').val();

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

	// init $calendar
	$calendar.fullCalendar({
		header      : {
			left  : 'prev,next',
			center: 'title',
			right : 'agendaWeek,month,agendaDay'
		},
		weekends    : false, // will hide saturdays and sundays
		minTime     : "09:00:00",
		maxTime     : "22:00:00",
		defaultView : "agendaWeek",
		editable    : false,
		droppable   : false,
		eventSources: []
	});

	$courseId.select2({
		placeholder: "select a course",
		allowClear : false
	});

	$courseId.click(function () {
		try {
			retrieveTutors();
			reloadCalendar('appointments_and_schedules_for_single_course');
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

	var startDateDefault;
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
	})
	;
	$dateTimePickerStart.on("dp.change", function (e) {
		var newEndDateDefault = $(this).data("DateTimePicker").getDate().clone();

		newEndDateDefault.add('30', 'minutes');
		var newMinimumEndDate = newEndDateDefault.clone();
		newMinimumEndDate.subtract('31', 'minutes');

		$dateTimePickerEnd2.data("DateTimePicker").setMinDate(newMinimumEndDate);
		$dateTimePickerEnd2.data("DateTimePicker").setDate(newEndDateDefault);
	});
	$termId.select2();
	$instructorId.select2({
		placeholder: "select an instructor"
	});
	$studentId.select2({
		placeholder: "Select one"
	});
	$tutorId.select2({
		placeholder: "first select a course"
	});

	$tutorId.click(function () {
		try {
			reloadCalendar('single_tutor_appointment_and_schedule');
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

	function reloadCalendar(choice) {
		var calendar = document.getElementById('appointments-schedule-calendar');
		var spinner;

		$calendarTitle.text("");
//		$calendarTitle.append("<i class='fa fa-circle-o-notch fa-spin'></i>");

		if ($termId.val() === null || !$termId.select2('val').match(/^[0-9]+$/)) {
			throw new Error("term is missing");
		}

		var data = [];
		var singleTutorScheduleCalendar = {
			url       : domainName + "/api/schedules",
			type      : 'get',
			dataType  : "json",
			data      : {
				action : 'single_tutor_working_hours',
				tutorId: $tutorId.select2('val'),
				termId : $termId.select2('val')
			},
			error     : function (xhr, status, error) {
				$calendarTitle.text("there was an error while retrieving schedules");
				console.log(xhr.responseText);
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
		var singleTutorAppointmentsCalendar = {
			url       : domainName + "/api/appointments",
			type      : 'get',
			dataType  : "json",
			data      : {
				action : 'single_tutor_appointments',
				tutorId: $tutorId.select2('val'),
				termId : $termId.select2('val')
			},
			error     : function (xhr, status, error) {
				$calendarTitle.text("there was an error while fetching tutor's appointments");
				console.log(xhr.responseText);
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

		var manyTutorScheduleCalendar = {
			url       : domainName + "/api/schedules",
			type      : 'get',
			dataType  : "json",
			data      : {
				action  : 'many_tutor_working_hours',
				courseId: $courseId.select2('val'),
				termId  : $termId.select2('val')
			},
			error     : function (xhr, status, error) {
				$calendarTitle.text("there was an error while retrieving schedules");
				console.log(xhr.responseText);
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
		var manyTutorAppointmentsCalendar = {
			url       : domainName + "/api/appointments",
			type      : 'get',
			dataType  : "json",
			data      : {
				action  : 'many_tutors_appointments',
				courseId: $courseId.select2('val'),
				termId  : $termId.select2('val')
			},
			error     : function (xhr, status, error) {
				$calendarTitle.text("could not fetch tutor's appointments.");
				console.log(xhr.responseText);
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
		var allSchedulesCalendar = {
			url       : domainName + "/api/schedules",
			type      : 'get',
			dataType  : "json",
			data      : {
				action: 'all_tutors_working_hours',
				termId: $termId.val()
			},
			error     : function (xhr, status, error) {
				$('#calendar-title').text("there was an error while retrieving schedules");
				console.log(xhr.responseText);

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
		var allAppointmentsCalendar = {
			url       : domainName + "/api/appointments",
			type      : 'get',
			dataType  : "json",
			data      : {
				action: 'all_tutors_appointments',
				termId: $termId.val()
			},
			error     : function (xhr, status, error) {
				$('#calendar-title').text("there was an error while fetching all appointments");
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
		$calendar.fullCalendar('removeEventSource', singleTutorScheduleCalendar);
		$calendar.fullCalendar('removeEventSource', singleTutorAppointmentsCalendar);
		$calendar.fullCalendar('removeEventSource', allSchedulesCalendar);
		$calendar.fullCalendar('removeEventSource', allAppointmentsCalendar);
		$calendar.fullCalendar('removeEventSource', manyTutorAppointmentsCalendar);
		$calendar.fullCalendar('removeEventSource', manyTutorScheduleCalendar);

		switch (choice) {
			case 'all_appointments_schedule':
			case 'term_change':
				$calendar.fullCalendar('addEventSource', allAppointmentsCalendar);
				$calendar.fullCalendar('addEventSource', allSchedulesCalendar);
				break;
			case 'single_tutor_appointment_and_schedule':
				if (!$tutorId.select2('val').match(/^[0-9]+$/)) {
					throw new Error("tutor is missing");
				}
				if (!$courseId.select2("val").match(/^[0-9]+$/)) {
					throw new Error("course is missing");
				}
				if (!$termId.select2("val").match(/^[0-9]+$/)) {
					throw new Error("term is missing");
				}
				$calendar.fullCalendar('addEventSource', singleTutorScheduleCalendar);
				$calendar.fullCalendar('addEventSource', singleTutorAppointmentsCalendar);
				break;
			case 'appointments_and_schedules_for_single_course':
				if (!$courseId.select2("val").match(/^[0-9]+$/)) {
					throw new Error("course is missing");
				}
				if (!$termId.select2("val").match(/^[0-9]+$/)) {
					throw new Error("term is missing");
				}
				$calendar.fullCalendar('addEventSource', manyTutorScheduleCalendar);
				$calendar.fullCalendar('addEventSource', manyTutorAppointmentsCalendar);
				break;
			case 'working_hours_only':
				if (!$tutorId.select2('val').match(/^[0-9]+$/)) {
					$calendar.fullCalendar('addEventSource', allSchedulesCalendar);
				}
				else {
					$calendar.fullCalendar('addEventSource', singleTutorScheduleCalendar);
				}
				break;
			case 'appointments_only':
				if (!$tutorId.select2('val').match(/^[0-9]+$/)) {
					$calendar.fullCalendar('addEventSource', allAppointmentsCalendar);
				}
				else {
					$calendar.fullCalendar('addEventSource', singleTutorAppointmentsCalendar);
				}
				break;
			default:
				break;
		}

		$calendar.fullCalendar('refetchEvents');

		if (!$tutorId.select2('val').match(/^[0-9]+$/)) {
			$calendarTitle.text("Appointments & Working Hours");
		}
		else {
			$calendarTitle.text($tutorId.select2('data').text);
		}

	}

	function loadAllCalendars() {
		try {
			reloadCalendar('single_tutor_appointment_and_schedule');
		} catch (err) {
			// clear options
			$tutorId.empty().append("<option></option>");
			$tutorId.select2({
				placeholder: err.message
			});
		}

		try {
			reloadCalendar('all_appointments_schedule');
		} catch (err) {
			$calendarTitle.text(err);
		}
	}

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

	function retrieveTutors() {
		var courseId = $("#courseId").select2("val");
		var termId = $("#termId").select2("val");

		if (!courseId.match(/^[0-9]+$/)) {
			throw new Error("course is missing");
		}
		if (!termId.match(/^[0-9]+$/)) {
			throw new Error("term is missing");
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

				var placeholder = jQuery.isEmptyObject(indata) ? "no tutors found" : "select a tutor";
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
	try {
		reloadCalendar('single_tutor_appointment_and_schedule');
	} catch (err) {
		// clear options
		$tutorId.empty().append("<option></option>");
		$tutorId.select2({
			placeholder: err.message
		});
	}
	if (isBtnAddStudentPrsd) {
		$dateTimePickerStart.data("DateTimePicker").setDate(dateTimePickerStartServerSide);
		$dateTimePickerEnd.data("DateTimePicker").setDate(dateTimePickerStartServerSide);
	}

	$('#toggle-details-calendar-partial').change(function () {
		var isCheckedDetails = $(this).prop('checked');
		var $portletCalendar = $('#portletCalendar');
		var $portletDetails = $('#portletDetails');

		if (!isCheckedDetails) {
			$portletDetails.addClass('hide');
			return;
		}

		$portletDetails.removeClass('hide');
		//loadAllCalendars();
	});
});