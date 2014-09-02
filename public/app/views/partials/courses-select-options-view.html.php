<?php
/**
 * Expects $major & $courses
 */
?>

<option
    value="<?php echo $course[CourseFetcher::DB_COLUMN_ID]; ?>">
    <?php echo $course['code'] . " " . $course['name']; ?>
</option>



