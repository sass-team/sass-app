<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/17/2014
 * Time: 8:40 AM
 */
?>
<?php
$dateStart = new DateTime($schedules[$i][ScheduleFetcher::DB_COLUMN_START_TIME]);
$dateEnd = new DateTime($schedules[$i][ScheduleFetcher::DB_COLUMN_END_TIME]);
?>
{
title: '<?php echo "Working Hours"; ?>',
start: '<?php echo $dateStart->format('Y-m-d H:i:s'); ?>',
end: '<?php echo $dateEnd->format('Y-m-d H:i:s'); ?>',
allDay: false,
className: 'fc-yellow'
},