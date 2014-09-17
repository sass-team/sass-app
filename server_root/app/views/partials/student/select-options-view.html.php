<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/15/2014
 * Time: 9:21 PM
 */
?>

<option
	value="<?php echo $student[StudentFetcher::DB_COLUMN_ID]; ?>">
	<?php echo $student[StudentFetcher::DB_COLUMN_FIRST_NAME] . " " . $student[StudentFetcher::DB_COLUMN_LAST_NAME]; ?></option>
	<?php echo $student[StudentFetcher::DB_COLUMN_FIRST_NAME] . " " . $student[StudentFetcher::DB_COLUMN_LAST_NAME] . " - " . $student[StudentFetcher::DB_COLUMN_STUDENT_ID]; ?></option>