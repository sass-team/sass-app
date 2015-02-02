$(function () {
	// http://momentjs.com/docs/#/manipulating/add/
	// http://eonasdan.github.io/bootstrap-datetimepicker
	moment().format();

	var $termId = $("#termId");
	var $calendarTitle = $('#calendar-title');
	var $calendar = $("#schedule-calendar");

	// Server side data
	var userId = $('#userId').val();
	var domainName = $('#domainName').val();

	// Config data
	var spinner;
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
	$termId.select2();

	// Event Listeners
	$termId.click(function () {
		reloadCalendar('term_change');
	});
	// Custom functions
	function reloadCalendar(choice) {
		var calendar = document.getElementById('appointments-schedule-calendar');
		var spinner;

		$calendarTitle.text("");
//		$calendarTitle.append("<i class='fa fa-circle-o-notch fa-spin'></i>");

		if ($termId.val() === null || !$termId.select2('val').match(/^[0-9]+$/)) {
			pnotifySettingsWarning.text = "Could not detect a term. Please try to fresh the web page.";
			new PNotify(pnotifySettingsWarning);
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
			termId: $termId.select2('val'), tutorId: userId
		};
		var getScheduleForTutor = formatCalendarEventSource('/api/schedules', 'get', 'json', 'getScheduleForTutor', data);

		$calendar.fullCalendar('removeEventSource', getScheduleForTutor);

		switch (choice) {
			case 'working_hours_only':
					//$calendar.fullCalendar('addEventSource', getSchedulesWithCourse);
					$calendar.fullCalendar('addEventSource', getScheduleForTutor);
					pnotifySettingsInfo.text = "Retrieved all working hours.";
					new PNotify(pnotifySettingsInfo);
				break;
			default:
				break;
		}
		$calendar.fullCalendar('refetchEvents');
	}

	reloadCalendar("working_hours_only");

});