<?php
/**
 * Created by PhpStorm.
 * User: Rizart
 * Date: 9/22/2014
 * Time: 2:42 PM
 */

?>
<tr>
    <td>
        <strong><?php echo $instructor[InstructorFetcher::DB_TABLE . "_" . InstructorFetcher::DB_COLUMN_FIRST_NAME] . " " .
                $instructor[InstructorFetcher::DB_TABLE . "_" . InstructorFetcher::DB_COLUMN_LAST_NAME];?></strong>
    </td>
</tr>