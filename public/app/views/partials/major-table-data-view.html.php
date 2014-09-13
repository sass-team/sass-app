<tr>
	<td class="text-center"><?php echo htmlentities($major[MajorFetcher::DB_COLUMN_CODE]); ?></td>
	<td class="text-center"><?php echo htmlentities($major[MajorFetcher::DB_COLUMN_NAME]); ?></td>
	<td class="text-center">
		<a href="#updateMajor" data-toggle="modal" class="btn btn-xs btn-primary btnUpdateMajor"><i
				class="fa fa-pencil fa-lg"></i></a>
		<a href="#deleteMajor" data-toggle="modal" class="btn btn-xs btn-secondary btnDeleteMajor"><i
				class="fa fa-times fa-lg"></i></a>
		<input type="hidden" value="<?php echo $major[MajorFetcher::DB_COLUMN_ID]; ?>"/>
	</td>
</tr>
