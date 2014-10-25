<?php
/**
 * Created by PhpStorm.
 * User: rdok
 * Date: 10/24/2014
 * Time: 9:29 PM
 */

require __DIR__ . '/../app/init.php';

try {
	Excel::downloadAppointments(2);
} catch (Exception $e) {
	var_dump($e->getMessage());
}
