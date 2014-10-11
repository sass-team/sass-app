<?php
/**
 *
 */
ob_start();

#starting the staff session
session_start();

require __DIR__ . "/app.php";

// identical to require; but php will include it only if it has not already been included
require_once ROOT_PATH . "models/DatabaseManager.class.php";
require_once ROOT_PATH . "models/General.class.php";

require_once ROOT_PATH . "models/Person.class.php";

require_once ROOT_PATH . "models/User.class.php";
require_once ROOT_PATH . "models/UserFetcher.class.php";

require_once ROOT_PATH . "models/Admin.class.php";
require_once ROOT_PATH . "models/Tutor.class.php";
require_once ROOT_PATH . "models/TutorFetcher.class.php";
require_once ROOT_PATH . "models/TutorHasCourseHasTerm.class.php";
require_once ROOT_PATH . "models/TutorHasCourseHasTermFetcher.class.php";
require_once ROOT_PATH . "models/TermFetcher.class.php";
require_once ROOT_PATH . "models/Term.class.php";
require_once ROOT_PATH . "models/Appointment.class.php";
require_once ROOT_PATH . "models/AppointmentFetcher.class.php";
require_once ROOT_PATH . "models/AppointmentHasStudentFetcher.class.php";
require_once ROOT_PATH . "models/Schedule.class.php";
require_once ROOT_PATH . "models/ScheduleFetcher.class.php";

require_once ROOT_PATH . "models/ReportFetcher.class.php";
require_once ROOT_PATH . "models/Report.class.php";

require_once ROOT_PATH . "models/Secretary.class.php";

require_once ROOT_PATH . "models/InstructorFetcher.class.php";
require_once ROOT_PATH . "models/Instructor.class.php";
require_once ROOT_PATH . "models/UserTypes.class.php";
require_once ROOT_PATH . "models/MajorFetcher.class.php";
require_once ROOT_PATH . "models/Major.class.php";

require_once ROOT_PATH . "models/CourseFetcher.class.php";
require_once ROOT_PATH . "models/Course.class.php";

require_once ROOT_PATH . "models/StudentFetcher.class.php";
require_once ROOT_PATH . "models/Student.class.php";

require_once ROOT_PATH . "models/Dates.class.php";

require_once ROOT_PATH . "models/Mailer.class.php";
require_once ROOT_PATH . "models/MailerFetcher.class.php";

