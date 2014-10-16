<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 10/15/2014
 * Time: 5:31 PM
 */

require __DIR__ . '/../app/init.php';

try {
	var_dump(Appointment::delete(9));

} catch (Exception $e) {
	echo $e->getMessage();
}
