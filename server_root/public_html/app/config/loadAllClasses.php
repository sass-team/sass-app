<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 10/23/2014
 * Time: 10:30 AM
 */

require ROOT_PATH . "config/DatabaseManager.class.php";

// identical to require; but php will include it only if it has not already been included
require_once ROOT_PATH . "controllers/General.class.php";
require_once ROOT_PATH . "controllers/App.class.php";
require_once ROOT_PATH . "controllers/Person.class.php";
require_once ROOT_PATH . "controllers/User.class.php";
require_once ROOT_PATH . "controllers/Admin.class.php";
require_once ROOT_PATH . "controllers/Tutor.class.php";
require_once ROOT_PATH . "controllers/TutorHasCourseHasTerm.class.php";
require_once ROOT_PATH . "controllers/Term.class.php";
require_once ROOT_PATH . "controllers/Appointment.class.php";
require_once ROOT_PATH . "controllers/Schedule.class.php";
require_once ROOT_PATH . "controllers/Report.class.php";
require_once ROOT_PATH . "controllers/Secretary.class.php";
require_once ROOT_PATH . "controllers/Instructor.class.php";
require_once ROOT_PATH . "controllers/Major.class.php";
require_once ROOT_PATH . "controllers/Course.class.php";
require_once ROOT_PATH . "controllers/Student.class.php";
require_once ROOT_PATH . "controllers/Dates.class.php";
require_once ROOT_PATH . "controllers/Mailer.class.php";
require_once ROOT_PATH . "controllers/DropboxCon.class.php";
require_once ROOT_PATH . "controllers/Excel.class.php";

require_once ROOT_PATH . "models/UserFetcher.class.php";
require_once ROOT_PATH . "models/TutorFetcher.class.php";
require_once ROOT_PATH . "models/TutorHasCourseHasTermFetcher.class.php";
require_once ROOT_PATH . "models/TermFetcher.class.php";
require_once ROOT_PATH . "models/AppointmentFetcher.class.php";
require_once ROOT_PATH . "models/AppointmentHasStudentFetcher.class.php";
require_once ROOT_PATH . "models/ScheduleFetcher.class.php";
require_once ROOT_PATH . "models/ReportFetcher.class.php";
require_once ROOT_PATH . "models/InstructorFetcher.class.php";
require_once ROOT_PATH . "models/UserTypesFetcher.class.php";
require_once ROOT_PATH . "models/MajorFetcher.class.php";
require_once ROOT_PATH . "models/CourseFetcher.class.php";
require_once ROOT_PATH . "models/StudentFetcher.class.php";
require_once ROOT_PATH . "models/StudentBroughtAlongFetcher.class.php";
require_once ROOT_PATH . "models/MailerFetcher.class.php";
require_once ROOT_PATH . "models/PrimaryFocusOfConferenceFetcher.class.php";
require_once ROOT_PATH . "models/ConclusionWrapUpFetcher.class.php";
require_once ROOT_PATH . "models/DropboxFetcher.class.php";