<tr>
	<td class="text-center"><?php echo htmlentities($schedule[UserFetcher::DB_COLUMN_FIRST_NAME]); ?></td>
	<td class="text-center"><?php echo htmlentities($schedule[UserFetcher::DB_COLUMN_LAST_NAME]); ?></td>
	<td class="text-center"><?php echo $schedule[ScheduleFetcher::DB_COLUMN_START_TIME]; ?></td>
	<td class="text-center"><?php echo $schedule[ScheduleFetcher::DB_COLUMN_END_TIME]; ?></td>

	<td class="text-center">
		<a href="#updateCourse" data-toggle="modal" class="btn btn-xs btn-primary btnUpdateCourse"><i
				class="fa fa-pencil fa-lg"></i></a>
		<input type="hidden" value="<?php echo $schedule[ScheduleFetcher::DB_COLUMN_ID]; ?>"/>
	</td>
</tr>
