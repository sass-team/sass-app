<?php
$weekTimeStart = new DateTime($schedule[ScheduleFetcher::DB_COLUMN_START_TIME]);
$weekTimeStart = $weekTimeStart->format("l g:ia");
$weekTimeEnd = new DateTime($schedule[ScheduleFetcher::DB_COLUMN_END_TIME]);
$weekTimeEnd = $weekTimeEnd->format("l g:ia");

?>
<tr>
	<td class="text-center"><?php echo htmlentities($schedule[UserFetcher::DB_COLUMN_FIRST_NAME]); ?></td>
	<td class="text-center"><?php echo htmlentities($schedule[UserFetcher::DB_COLUMN_LAST_NAME]); ?></td>
	<td class="text-center"><?php echo $weekTimeStart; ?></td>
	<td class="text-center"><?php echo $weekTimeEnd; ?></td>
	<td class="text-center"><?php echo htmlentities($schedule[TermFetcher::DB_COLUMN_NAME]); ?></td>

	<td class="text-center">
		<a href="#deleteSchedule" data-toggle="modal" class="btn btn-xs btn-secondary btnDeleteSchedule"><i
				class="fa fa-times fa-lg"></i></a>
		<input type="hidden" value="<?php echo $schedule[ScheduleFetcher::DB_COLUMN_ID]; ?>"/>
	</td>
</tr>
