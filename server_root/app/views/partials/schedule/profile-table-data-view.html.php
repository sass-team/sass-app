<?php
$weekTimeStart = new DateTime($schedule[ScheduleFetcher::DB_COLUMN_START_TIME]);
$weekTimeStart = $weekTimeStart->format("l g:ia");
$weekTimeEnd = new DateTime($schedule[ScheduleFetcher::DB_COLUMN_END_TIME]);
$weekTimeEnd = $weekTimeEnd->format("l g:ia");

?>
<tr>
	<td class="text-center"><?php echo $weekTimeStart; ?></td>
	<td class="text-center"><?php echo $weekTimeEnd; ?></td>
</tr>
