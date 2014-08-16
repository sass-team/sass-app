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

if ($currentUserData['type'] == 'admin') {
	$tempUser = new Admin($currentUserData);
} else if ($currentUserData['type'] == 'tutor') {
	$tempUser = new Tutor($currentUserData);
} else if ($currentUserData['type'] == 'secretary') {
	$tempUser = new Secretary($currentUserData);
}

$first_name = $tempUser->getFirstName();
$last_name = $tempUser->getLastName();
$email = $tempUser->getEmail();
$position = $tempUser->getUserType();
$mobile = $tempUser->getMobileNum();
?>
<tr>
	<td><?php echo $first_name . " " . $last_name; ?></td>
	<td><?php echo $email; ?></td>
	<td><?php echo $position ?></td>
	<td><?php echo $mobile ?></td>
	<?php // TODO: CREATE public page for profile. crate page to edit user data? ?>
	<td class="hidden-xs hidden-sm">
		<a data-toggle="modal" href="#" class="btn btn-default btn-sm center-block">
			<i class="fa fa-calendar"></i> View
		</a>
	</td>
	<td class="hidden-xs hidden-sm">
		<a data-toggle="modal" href="#" class="btn btn-default btn-sm center-block">
			<i class="fa fa-calendar"></i> View
		</a>
	</td>
	<td class="hidden-xs hidden-sm">
		<a data-toggle="modal" href="#styledModal" class="btn btn-default btn-sm center-block">
			<i class="fa fa-edit"></i> Edit
		</a>
	</td>
</tr>