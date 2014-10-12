<?php
/**
 *  * Created by PhpStorm.
 *  * User: rdok
 *  * Date: 9/18/2014
 *  * Time: 2:35 PM
 *
 */

try {
	require __DIR__ . '/init.php';
	date_default_timezone_set('Europe/Athens');
	$curWorkingDate = new DateTime();
	$curWorkingHour = intval($curWorkingDate->format('H'));
	// save resources - only run cron at hours 08:00 - 18:00
	if ($curWorkingHour < App::WORKING_HOUR_START || $curWorkingHour > App::WORKING_HOUR_END) exit();

	include_once(ROOT_PATH . '/plugins/mysqldump-php-1.4.1/src/Ifsnop/Mysqldump/Mysqldump.php');
	$filename = ROOT_PATH . 'storage/backups/database_backup_' . date('G_a_F_d_Y') . '.sql';


	$dumpSettings = array(
		'compress' => Ifsnop\Mysqldump\Mysqldump::GZIP,
		'no-data' => false,
		'add-drop-table' => true,
		'single-transaction' => false,
		'lock-tables' => true,
		'add-locks' => true,
		'extended-insert' => true,
		'disable-foreign-keys-check' => true,
		'skip-triggers' => false,
		'add-drop-trigger' => true,
		'databases' => false,
		'add-drop-database' => false,
		'hex-blob' => true
	);

	$dump = new Ifsnop\Mysqldump\Mysqldump(DatabaseManager::$dsn[DatabaseManager::DB_NAME],
		DatabaseManager::$dsn[DatabaseManager::DB_USERNAME],
		DatabaseManager::$dsn[DatabaseManager::DB_PASSWORD],
		DatabaseManager::$dsn[DatabaseManager::DB_HOST], 'mysql', $dumpSettings);
	Mailer::sendDevelopers("Backup created: " . $filename, __FILE__);
	$dump->start($filename);

} catch (\Exception $e) {
	Mailer::sendDevelopers('mysqldump-php error: ' . $e->getMessage(), __FILE__);
}