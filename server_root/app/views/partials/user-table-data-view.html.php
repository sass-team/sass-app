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
	//$courses = get($courses, $id, TutorFetcher::)
}
$mobile = $curUser[UserFetcher::DB_COLUMN_MOBILE];
?>
<tr>
	<td class="text-center"><?php echo $first_name . " " . $last_name; ?></td>
	<td class="text-center"><?php echo $email; ?></td>
	<td class="text-center"><?php echo $position ?></td>
	<td class="text-center"><?php echo $mobile ?></td>

	<td class="text-center">
		<a data-toggle="modal" href="<?php echo BASE_URL . "users/" . $id; ?>"
		   class="btn btn-default btn-sm center-block">
			<i class="fa fa-user"></i> View
		</a>
	</td>

	<td class="text-center">
		<?php if (!$user->isTutor() && User::isUserTypeTutor($position)): ?>
			<a class="btn btn-default btn-sm center-block ui-popover" data-toggle="tooltip" data-placement="right"
			   data-trigger="hover"
			   data-content="Sed posuere consectetur est at lobortis. Aenean eu leo quam." title="Teaching Courses">
				<i class="fa fa-book"></i> Courses
			</a>
		<?php endif; ?>
	</td>

	<td class="text-center">
		<?php if (!$user->isTutor()): ?>
			<a data-toggle="modal" href="#" class="btn btn-default btn-sm center-block">
				<i class="fa fa-calendar"></i> View
			</a>
		<?php endif; ?>
	</td>

	<td class="text-center">
		<?php if ($user->isAdmin()): ?>
			<a data-toggle="modal" href="<?php echo BASE_URL . "users/edit/:" . $id; ?>"
			   class="btn btn-default btn-sm center-block edit-user">
				<i class="fa fa-edit"></i> Edit
			</a>
			<input type="hidden" value=""/>
		<?php endif; ?>
	</td>

</tr>