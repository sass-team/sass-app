<?php
/**
 * Expects $major & $courses
 */
?>

<option
	 value="<?php echo $instructor[InstructorFetcher::DB_COLUMN_ID]; ?>">
	<?php echo $instructor[InstructorFetcher::DB_COLUMN_FIRST_NAME] . " " . $instructor[InstructorFetcher::DB_COLUMN_LAST_NAME]; ?>
</option>



