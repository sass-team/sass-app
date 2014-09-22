<tr>
	<td class="text-center"><?php echo htmlentities($course[UserFetcher::DB_COLUMN_FIRST_NAME]); ?></td>
	<td class="text-center"><?php echo htmlentities($course[UserFetcher::DB_COLUMN_LAST_NAME]); ?></td>
	<td class="text-center"><?php echo htmlentities($course[CourseFetcher::DB_COLUMN_CODE]); ?></td>
	<td class="text-center"><?php echo htmlentities($course[CourseFetcher::DB_COLUMN_NAME]); ?></td>
	<td class="text-center"><?php echo htmlentities($course[TermFetcher::DB_TABLE . "_" . TermFetcher::DB_COLUMN_NAME]); ?></td>
</tr>