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


$first_name = $currUser['f_name'];
$last_name = $currUser['l_name'];
$email = $currUser['email'];
$mobile = $currUser['mobile'];
$id = $currUser['id'];
?>
<tr>
	<td><?php echo $first_name . " " . $last_name; ?></td>
	<td><?php echo $email; ?></td>
	<td><?php echo $mobile ?></td>
	<td>
		<a data-toggle="modal" href="<?php echo BASE_URL . "users/" . $id; ?>"
		   class="btn btn-default btn-sm center-block">
			<i class="fa fa-user"></i> View
		</a>
	</td>

	<?php if (!$user->isTutor()): ?>
		<td>
			<a data-toggle="modal" href="#" class="btn btn-default btn-sm center-block">
				<i class="fa fa-calendar"></i> View
			</a>
		</td>
	<?php endif; ?>

	<?php if ($user->isAdmin()): ?>
		<td>
			<a data-toggle="modal" href="<?php echo BASE_URL . "users/edit/:" . $id; ?>"
			   class="btn btn-default btn-sm center-block edit-user">
				<i class="fa fa-edit"></i> Edit
			</a>
			<input type="hidden" value=""/>
		</td>
	<?php endif; ?>

</tr>