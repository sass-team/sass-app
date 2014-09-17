<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/17/2014
 * Time: 8:36 AM
 */
?>
<?php
$courseName = $course[CourseFetcher::DB_COLUMN_NAME];
$dateStart = new DateTime($appointment[AppointmentFetcher::DB_COLUMN_START_TIME]);
$dateEnd = new DateTime($appointment[AppointmentFetcher::DB_COLUMN_END_TIME]);
?>
{
title: '<?php echo htmlentities($courseName); ?>',
start: '<?php echo $dateStart->format('Y-m-d H:i:s'); ?>',
end: '<?php echo $dateEnd->format('Y-m-d H:i:s'); ?>',
allDay: false,
className: 'fc-yellow'
}