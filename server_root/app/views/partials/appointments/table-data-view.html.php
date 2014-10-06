<tr>
	<td class="text-center"><?php echo htmlentities($appointment[AppointmentFetcher::DB_TABLE . "_" . AppointmentFetcher::DB_COLUMN_ID]); ?></td>
	<td class="text-center"><?php echo htmlentities($appointment[UserFetcher::DB_COLUMN_FIRST_NAME]) . " " . htmlentities($appointment[UserFetcher::DB_COLUMN_LAST_NAME]); ?></td>
	<td class="text-center"><?php echo htmlentities($appointment[AppointmentFetcher::DB_COLUMN_START_TIME]); ?></td>
	<td class="text-center"><?php echo htmlentities($appointment[AppointmentFetcher::DB_COLUMN_END_TIME]); ?></td>
	<td class="text-center"><?php echo htmlentities($appointment[CourseFetcher::DB_COLUMN_CODE]); ?></td>
	<td class="text-center"><?php echo htmlentities($appointment[CourseFetcher::DB_COLUMN_NAME]); ?></td>
	<td class="text-center"><?php echo htmlentities($appointment[TermFetcher::DB_TABLE . "_" . TermFetcher::DB_COLUMN_NAME]); ?></td>
</tr>