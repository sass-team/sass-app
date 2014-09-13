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


$first_name = $student[StudentFetcher::DB_COLUMN_FIRST_NAME];
$last_name = $student[StudentFetcher::DB_COLUMN_LAST_NAME];
$email = $student[StudentFetcher::DB_COLUMN_EMAIL];
$mobile = $student[StudentFetcher::DB_COLUMN_MOBILE];
$id = $student[StudentFetcher::DB_COLUMN_ID];
$ci = $student[StudentFetcher::DB_COLUMN_CI];
$credits = $student[StudentFetcher::DB_COLUMN_CREDITS];
$major = $student[MajorFetcher::DB_COLUMN_NAME];

?>
<tr>
	<td><?php echo $first_name . " " . $last_name; ?></td>
	<td><?php echo $email; ?></td>
	<td><?php echo $mobile; ?></td>
	<td><?php echo $major; ?></td>
	<td><?php echo $ci; ?></td>
	<td><?php echo $credits; ?></td>

	<td>
		<a data-toggle="modal" href="<?php echo BASE_URL . "academia/students/" . $id; ?>"
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
			<a data-toggle="modal" href="<?php echo BASE_URL . "academia/students/:" . $id; ?>"
			   class="btn btn-default btn-sm center-block edit-user">
				<i class="fa fa-edit"></i> Edit
			</a>
			<input type="hidden" value=""/>
		</td>
	<?php endif; ?>

</tr>