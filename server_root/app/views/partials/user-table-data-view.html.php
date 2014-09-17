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


$first_name = $curUser['f_name'];
$last_name = $curUser['l_name'];
$email = $curUser['email'];
$position = $curUser['type'];
$mobile = $curUser['mobile'];
$id = $curUser['id'];
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

	<?php if (!$user->isTutor()): ?>
		<td class="text-center">
			<a class="btn btn-default btn-sm center-block ui-popover" data-toggle="tooltip" data-placement="right" data-trigger="hover" 
					data-content="Sed posuere consectetur est at lobortis. Aenean eu leo quam." title="Teaching Courses">
			        <i class="fa fa-book"></i> Courses
			</a>
		</td>
	<?php endif; ?>

	<?php if (!$user->isTutor()): ?>
		<td class="text-center">
			<a data-toggle="modal" href="#" class="btn btn-default btn-sm center-block">
				<i class="fa fa-calendar"></i> View
			</a>
		</td>
	<?php endif; ?>

	<?php if ($user->isAdmin()): ?>
		<td class="text-center">
			<a data-toggle="modal" href="<?php echo BASE_URL . "users/edit/:" . $id; ?>"
			   class="btn btn-default btn-sm center-block edit-user">
				<i class="fa fa-edit"></i> Edit
			</a>
			<input type="hidden" value=""/>
		</td>
	<?php endif; ?>

</tr>