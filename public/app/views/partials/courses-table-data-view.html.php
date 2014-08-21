<tr>
	<td class="text-center"><?php echo $course['Code'] ?></td>
	<td class="text-center"><?php echo $course['Name']; ?></td>
	<td class="text-center">
		<a href="#" class="btn btn-xs btn-primary"><i class="fa fa-pencil fa-lg"></i></a>
		<a href="#deleteCourse" data-toggle="modal" class="btn btn-xs btn-secondary btnDeleteCourse"><i class="fa fa-times fa-lg"></i></a>
		<input type="hidden" value="<?php echo $course['id'];?>"/>
	</td>
</tr>
