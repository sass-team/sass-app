<?php
/**
 * Created by PhpStorm.
 * User: Riza
 * Date: 5/12/14
 * Time: 11:49 PM
 */

/*
PDO function:
*	@param mysql:host=localhost
*	1st part of string: type of database
*	2nd depending on type of db, on this case host=.
*	3rd name of database
*	4th if sql uses default port, this is not need. else you need to specify it like:
*
*	@param  the user name
*	@param
*/
try { // connects to database
	$db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME .";port=" . DB_PORT,DB_USER,DB_PASS);
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); // CHANGE THE ERROR MODE, THROW AN EXCEPTION WHEN AN ERROR IS FOUND
	$db->exec("SET NAMES 'utf8'");
} catch (Exception $e) { // program ends if exception is found
	echo "Could not connect to the database: <br>" . $e;
	exit;
} // end
?>