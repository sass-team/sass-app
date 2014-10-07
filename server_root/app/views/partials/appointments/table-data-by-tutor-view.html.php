<tr>
	<td class="text-center"><?php echo htmlentities($appointmentByTutor[AppointmentFetcher::DB_TABLE . "_" . AppointmentFetcher::DB_COLUMN_ID]); ?></td>
	<td class="text-center"><?php echo htmlentities($appointmentByTutor[AppointmentFetcher::DB_COLUMN_START_TIME]); ?></td>
	<td class="text-center"><?php echo htmlentities($appointmentByTutor[AppointmentFetcher::DB_COLUMN_END_TIME]); ?></td>
	<td class="text-center"><?php echo htmlentities($appointmentByTutor[CourseFetcher::DB_COLUMN_CODE]); ?></td>
	<td class="text-center"><?php echo htmlentities($appointmentByTutor[CourseFetcher::DB_COLUMN_NAME]); ?></td>
	<td class="text-center"><?php echo htmlentities($appointmentByTutor[TermFetcher::DB_TABLE . "_" . TermFetcher::DB_COLUMN_NAME]); ?></td>
</tr>