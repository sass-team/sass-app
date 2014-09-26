<?php
/**
 *
 */
ob_start();

#starting the staff session
session_start();

require __DIR__ . "/../../app/config/app.php";

// identical to require; but php will include it only if it has not already been included
require_once ROOT_PATH . 'app/config/database.class.php';
require_once ROOT_PATH . "app/models/General.class.php";

require_once ROOT_PATH . "app/models/Person.class.php";

require_once ROOT_PATH . "app/models/User.class.php";
require_once ROOT_PATH . "app/models/UserFetcher.class.php";

require_once ROOT_PATH . "app/models/Admin.class.php";
require_once ROOT_PATH . "app/models/Tutor.class.php";
require_once ROOT_PATH . "app/models/TutorFetcher.class.php";
require_once ROOT_PATH . "app/models/TutorHasCourseHasTerm.class.php";
require_once ROOT_PATH . "app/models/TutorHasCourseHasTermFetcher.class.php";
require_once ROOT_PATH . "app/models/TermFetcher.class.php";
require_once ROOT_PATH . "app/models/Term.class.php";
require_once ROOT_PATH . "app/models/Appointment.class.php";
require_once ROOT_PATH . "app/models/AppointmentFetcher.class.php";
require_once ROOT_PATH . "app/models/AppointmentHasStudentFetcher.class.php";
require_once ROOT_PATH . "app/models/Schedule.class.php";
require_once ROOT_PATH . "app/models/ScheduleFetcher.class.php";

require_once ROOT_PATH . "app/models/ReportFetcher.class.php";
require_once ROOT_PATH . "app/models/Report.class.php";

require_once ROOT_PATH . "app/models/Secretary.class.php";

require_once ROOT_PATH . "app/models/InstructorFetcher.class.php";
require_once ROOT_PATH . "app/models/Instructor.class.php";
require_once ROOT_PATH . "app/models/UserTypes.class.php";
require_once ROOT_PATH . "app/models/MajorFetcher.class.php";
require_once ROOT_PATH . "app/models/Major.class.php";

require_once ROOT_PATH . "app/models/CourseFetcher.class.php";
require_once ROOT_PATH . "app/models/Course.class.php";

require_once ROOT_PATH . "app/models/StudentFetcher.class.php";
require_once ROOT_PATH . "app/models/Student.class.php";

require_once ROOT_PATH . "app/models/Dates.class.php";

require_once ROOT_PATH . "app/models/Mailer.class.php";

$db = new Database();

