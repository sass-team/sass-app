<?php
$dateStart = new DateTime($appointment[AppointmentFetcher::DB_COLUMN_START_TIME]);
$dateEnd = new DateTime($appointment[AppointmentFetcher::DB_COLUMN_END_TIME]);
?>
<tr>
	<td class="text-center"><?php echo $appointment[AppointmentFetcher::DB_TABLE . "_" . AppointmentFetcher::DB_COLUMN_ID]; ?></td>
	<td class="text-center">
		<span class="label label-<?php echo $appointment[AppointmentFetcher::DB_COLUMN_LABEL_COLOR]; ?>">
		<?php echo $appointment[AppointmentFetcher::DB_COLUMN_LABEL_MESSAGE]; ?></span>
	</td>
	<td class="text-center">
		<?php foreach($reports as $report): ?>
			<span class="label label-<?php echo $report[ReportFetcher::DB_COLUMN_LABEL_COLOR]; ?>">
		<?php echo $report[ReportFetcher::DB_COLUMN_LABEL_MESSAGE]; ?></span>
		<?php endforeach; ?>
	</td>
	<td class="text-center">
		<a data-toggle="modal"
		   href="<?php echo BASE_URL . "appointments/" . $appointment[AppointmentFetcher::DB_TABLE . "_" . AppointmentFetcher::DB_COLUMN_ID]; ?>"
		   class="btn btn-default btn-sm center-block">
			<i class="fa fa-edit"></i> View
		</a>
	</td>
	<td class="text-center"><?php echo $dateStart->format('H:i') . " - " . $dateEnd->format('H:i, jS F Y'); ?></td>
	<td class="text-center"><?php echo htmlentities($appointment[CourseFetcher::DB_COLUMN_CODE]) . " " . htmlentities($appointment[CourseFetcher::DB_COLUMN_NAME]); ?></td>
	<td class="text-center"><?php echo htmlentities($appointment[TermFetcher::DB_TABLE . "_" . TermFetcher::DB_COLUMN_NAME]); ?></td>

</tr>