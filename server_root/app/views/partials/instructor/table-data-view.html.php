<tr>
	<td class="text-center"><?php echo htmlentities($instructor[InstructorFetcher::DB_FIRST_NAME]); ?></td>
	<td class="text-center"><?php echo htmlentities($instructor[InstructorFetcher::DB_LAST_NAME]); ?></td>
	<td class="text-center">
		<a href="#updateInstructor" data-toggle="modal" class="btn btn-xs btn-primary btnUpdateInstructor"><i
				class="fa fa-pencil fa-lg"></i></a>
		<a href="#deleteInstructor" data-toggle="modal" class="btn btn-xs btn-secondary btnDeleteInstructor"><i
				class="fa fa-times fa-lg"></i></a>
		<input type="hidden" value="<?php echo $instructor[InstructorFetcher::DB_ID]; ?>"/>
	</td>
</tr>
