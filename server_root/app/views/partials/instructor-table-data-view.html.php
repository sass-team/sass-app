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


$first_name = $instructor['f_name'];
$last_name = $instructor['l_name'];
$email = $instructor['email'];
$id = $instructor['id'];

?>
<tr>
	<td><?php echo $first_name . " " . $last_name; ?></td>
	<td><?php echo $email; ?></td>

	<td>
		<a data-toggle="modal" href="<?php echo BASE_URL . "academia/students/" . $id; ?>"
		   class="btn btn-default btn-sm center-block">
			<i class="fa fa-user"></i> View
		</a>
	</td>

	<?php if ($user->isAdmin()): ?>
		<td class="text-center">
			<a href="#updateCourse" data-toggle="modal" class="btn btn-xs btn-primary btnUpdateCourse"><i class="fa fa-pencil fa-lg"></i></a>
			<a href="#deleteCourse" data-toggle="modal" class="btn btn-xs btn-secondary btnDeleteCourse"><i class="fa fa-times fa-lg"></i></a>
			<input type="hidden" value="<?php echo $id;?>"/>
		</td>
	<?php endif; ?>

</tr>