<?php
/**
 * Expects $major & $courses
 */
?>

<option
	 value="<?php echo $instructor[InstructorFetcher::DB_ID]; ?>">
	<?php echo $instructor[InstructorFetcher::DB_FIRST_NAME] . " " . $instructor[InstructorFetcher::DB_LAST_NAME]; ?>
</option>



