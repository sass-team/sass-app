<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 9/17/2014
 * Time: 1:08 AM
 */
?>
<option
	value="<?php echo $tutor[TutorFetcher::DB_COLUMN_USER_ID]; ?>">
	<?php echo $tutor[UserFetcher::DB_COLUMN_FIRST_NAME] . " " . $tutor[UserFetcher::DB_COLUMN_LAST_NAME]; ?></option>
