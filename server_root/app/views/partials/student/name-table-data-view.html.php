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
		<strong><?php echo $student[StudentFetcher::DB_TABLE . "_" . StudentFetcher::DB_COLUMN_FIRST_NAME] . " " .
				$student[StudentFetcher::DB_TABLE . "_" . StudentFetcher::DB_COLUMN_LAST_NAME];?></strong>
	</td>
</tr>