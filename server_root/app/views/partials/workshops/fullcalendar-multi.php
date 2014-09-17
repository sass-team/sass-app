<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/17/2014
 * Time: 8:40 AM
 */
?>
<?php
$course = get($courses, $appointments[$i][AppointmentFetcher::DB_COLUMN_COURSE_ID], CourseFetcher::DB_COLUMN_ID);
$courseName = $course[CourseFetcher::DB_COLUMN_NAME];
$dateStart = new DateTime($appointments[$i][AppointmentFetcher::DB_COLUMN_START_TIME]);
$dateEnd = new DateTime($appointments[$i][AppointmentFetcher::DB_COLUMN_END_TIME]);
?>
{
title: '<?php echo htmlentities($courseName); ?>',
start: '<?php echo $dateStart->format('Y-m-d H:i:s'); ?>',
end: '<?php echo $dateEnd->format('Y-m-d H:i:s'); ?>',
allDay: false,
className: 'fc-yellow'
},