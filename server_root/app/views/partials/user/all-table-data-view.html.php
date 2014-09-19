<?php
/**
 * This file displays a single user  in a table data views. It needs to receive
 * the following product details (as elements of an array named $jobs):
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
	<td class="text-center">
		<?php if (!$user->isTutor() && Tutor::isUserTypeTutor($position)): ?>

			<a class="btn btn-default btn-sm center-block ui-popover" data-toggle="tooltip" data-placement="right"
			   data-trigger="hover"
			   data-content="
			   <?php
			   if ((sizeof($courses) > 0)) {
				   for ($i = 0; $i < (sizeof($courses) - 1); $i++) {
					   echo $courses[$i][CourseFetcher::DB_COLUMN_CODE] . " |";
				   }
				   echo " " . $courses[sizeof($courses) - 1][CourseFetcher::DB_COLUMN_CODE];
			   }
			   ?>" title="Teaching Courses">
				<i class="fa fa-book"></i> Courses
			</a>
		<?php endif; ?>
	</td>

	<td class="text-center">
		<?php if (!$user->isTutor() && Tutor::isUserTypeTutor($position)): ?>

			<a data-toggle="modal" href="#" class="btn btn-default btn-sm center-block">
				<i class="fa fa-calendar"></i> View
			</a>
		<?php endif; ?>
	</td>


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