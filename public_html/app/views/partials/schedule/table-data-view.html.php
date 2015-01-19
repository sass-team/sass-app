<?php
$hourStart = new DateTime($schedule[ScheduleFetcher::DB_COLUMN_START_TIME]);
$hourStart = $hourStart->format("l g:ia");
$hourStart = date('h:i A', strtotime($hourStart));

$hourEnd = new DateTime($schedule[ScheduleFetcher::DB_COLUMN_END_TIME]);
$hourEnd = $hourEnd->format("l g:ia");
$hourEnd = date('h:i A', strtotime($hourEnd));

$days = $schedule[ScheduleFetcher::DB_COLUMN_MONDAY] == 1 ? "M " : "";
$days .= $schedule[ScheduleFetcher::DB_COLUMN_TUESDAY] == 1 ? "T " : "";
$days .= $schedule[ScheduleFetcher::DB_COLUMN_WEDNESDAY] == 1 ? "W " : "";
$days .= $schedule[ScheduleFetcher::DB_COLUMN_THURSDAY] == 1 ? "R " : "";
$days .= $schedule[ScheduleFetcher::DB_COLUMN_FRIDAY] == 1 ? "F" : "";


?>
<tr>
    <td class="text-center"><?php echo htmlentities($schedule[UserFetcher::DB_COLUMN_FIRST_NAME]); ?></td>
    <td class="text-center"><?php echo htmlentities($schedule[UserFetcher::DB_COLUMN_LAST_NAME]); ?></td>
    <td class="text-center"><?php echo $days; ?></td>
    <td class="text-center"><?php echo $hourStart . " - " . $hourEnd; ?></td>
    <td class="text-center"><?php echo htmlentities($schedule[TermFetcher::DB_COLUMN_NAME]); ?></td>

<?php if ($user->isAdmin()): ?>
    <td class="text-center">
        <a href="#deleteSchedule" data-toggle="modal" class="btn btn-xs btn-secondary btnDeleteSchedule"><i
                class="fa fa-times fa-lg"></i></a>
        <input type="hidden" value="<?php echo $schedule[ScheduleFetcher::DB_COLUMN_ID]; ?>"/>
    </td>
<?php endif; ?>
</tr>
