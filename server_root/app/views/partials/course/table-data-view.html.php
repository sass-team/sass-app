<tr>
	<td class="text-center"><?php echo htmlentities($course[CourseFetcher::DB_COLUMN_CODE]); ?></td>
	<td class="text-center"><?php echo htmlentities($course[CourseFetcher::DB_COLUMN_NAME]); ?></td>
	<td class="text-center">
		<a href="#updateCourse" data-toggle="modal" class="btn btn-xs btn-primary btnUpdateCourse"><i
				class="fa fa-pencil fa-lg"></i></a>
		<a href="#deleteCourse" data-toggle="modal" class="btn btn-xs btn-secondary btnDeleteCourse"><i
				class="fa fa-times fa-lg"></i></a>
		<input type="hidden" value="<?php echo $course[CourseFetcher::DB_COLUMN_ID]; ?>"/>
	</td>
</tr>
