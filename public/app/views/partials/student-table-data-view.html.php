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

$id = $student[StudentFetcher::DB_COLUMN_ID];
$first_name = $student[StudentFetcher::DB_COLUMN_FIRST_NAME];
$last_name = $student[StudentFetcher::DB_COLUMN_LAST_NAME];
$studentId = $student[StudentFetcher::DB_COLUMN_STUDENT_ID];
$email = $student[StudentFetcher::DB_COLUMN_EMAIL];
$mobile = $student[StudentFetcher::DB_COLUMN_MOBILE];
$ci = $student[StudentFetcher::DB_COLUMN_CI];
$credits = $student[StudentFetcher::DB_COLUMN_CREDITS];
$majorName = $student[MajorFetcher::DB_COLUMN_NAME];
$majorCode = $student[MajorFetcher::DB_COLUMN_CODE];
$majorId = $student[StudentFetcher::DB_COLUMN_MAJOR_ID];

?>
<tr>
	<td><span><?php echo $first_name; ?></span>&#32;<span><?php echo $last_name; ?></span></td>
	<td><?php echo $studentId; ?></td>
	<td><?php echo $email; ?></td>
	<td><?php echo $mobile; ?></td>
	<td><?php echo $majorCode . " " . $majorName; ?>
		<input type="hidden" value="<?php echo $majorId; ?>" id="majorId"/>

	</td>
	<td><?php echo $ci; ?></td>
	<td><?php echo $credits; ?></td>

	<?php if (!$user->isTutor()): ?>
		<td>
			<a data-toggle="modal" href="#" class="btn btn-default btn-sm center-block">
				<i class="fa fa-calendar"></i> View
			</a>
		</td>
	<?php endif; ?>

	<?php if ($user->isAdmin()): ?>
		<td class="text-center">
			<a href="#updateStudent" data-toggle="modal" class="btn btn-xs btn-primary btnUpdateStudent"><i
					class="fa fa-pencil fa-lg"></i></a>
			<input type="hidden" value="<?php echo $id; ?>" id="id" name="id"/>
		</td>
	<?php endif; ?>

</tr>