<?php
/**
 * This file displays a single user  in a table data views. It needs to receive
 * the following tutor details (as elements of an array named $jobs):
 *
 *
 * Created by PhpStorm.
 * Date: 4/1/14
 * Time: 3:24 AM
 *
 */
$id = $curUser[UserFetcher::DB_COLUMN_ID];
$first_name = $curUser[UserFetcher::DB_COLUMN_FIRST_NAME];
$last_name = $curUser[UserFetcher::DB_COLUMN_LAST_NAME];
$email = $curUser[UserFetcher::DB_COLUMN_EMAIL];
$position = $curUser[UserTypes::DB_COLUMN_TYPE];

if (User::isUserTypeTutor($position)) {
	$courses = TutorFetcher::retrieveCurrTermTeachingCourses($db, $id);
	$schedules = ScheduleFetcher::retrieveCurrWorkingHours($db, $id);
}
$mobile = $curUser[UserFetcher::DB_COLUMN_MOBILE];
?>
<tr>
	<td class="text-center"><?php echo $first_name . " " . $last_name; ?></td>
	<td class="text-center"><?php echo $email; ?></td>
	<td class="text-center"><?php echo $position ?></td>
	<td class="text-center"><?php echo $mobile ?></td>

	<!-- profile -->
	<td class="text-center">
		<a data-toggle="modal" href="<?php echo BASE_URL . "staff/" . $id; ?>"
		   class="btn btn-default btn-sm center-block">
			<i class="fa fa-user"></i> View
		</a>
	</td>

	<!--- teaching -->
	<?php if (!$user->isTutor()): ?>
		<td class="text-center">
			<?php if (Tutor::isUserTypeTutor($position)) { ?>
				<a class="btn btn-default btn-sm center-block ui-popover" data-toggle="tooltip" data-placement="right"
				   data-trigger="hover"
				   data-content="
			   <?php
				   if ((sizeof($courses) > 0)) {
					   for ($i = 0; $i < (sizeof($courses) - 1); $i++) {
						   echo $courses[$i][CourseFetcher::DB_COLUMN_CODE] . " |";
					   }
					   echo " " . $courses[sizeof($courses) - 1][CourseFetcher::DB_COLUMN_CODE];
				   }else {
				   	echo '<i>' . 'No current courses!' . '</i>';
				   }
				   ?>" title="Teaching Courses">
					<i class="fa fa-book"></i> Courses
				</a>
			<?php }else {
				echo '<i class="fa fa-minus"></i>';
			} ?>
		</td>
	<?php endif; ?>

	<!--- schedule -->
	<?php if (!$user->isTutor()): ?>
		<td class="text-center">
			<?php if (Tutor::isUserTypeTutor($position)) { ?>
				<a class="btn btn-default btn-sm center-block ui-popover" data-toggle="tooltip" data-placement="right"
				   data-trigger="hover"
				   data-html="true"
				   data-content="
			   <?php
			   if ((sizeof($schedules) > 0)) {
				   foreach ($schedules as $schedule) {
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
					
				   	echo "<strong>" . $days . "</strong>" . "</br>";
				   	echo $hourStart . " - " . $hourEnd . "</br>";
					}
				}else {
				   	echo '<i>' . 'No current schedule!' . '</i>';
				   }
				   ?>" title="Schedule">
					<i class="fa fa-calendar"></i> View
				</a>
			<?php }else {
				echo '<i class="fa fa-minus"></i>';
			} ?>
		</td>
	<?php endif; ?>


	<!--- Edit -->
	<?php if ($user->isAdmin()): ?>
		<td class="text-center">
			<a data-toggle="modal" href="<?php echo BASE_URL . "staff/edit/" . $id; ?>"
			   class="btn btn-default btn-sm center-block edit-user">
				<i class="fa fa-edit"></i> Edit
			</a>
			<input type="hidden" value=""/>
		</td>
	<?php endif; ?>

</tr>