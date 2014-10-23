<?php
/**
 *  * Created by PhpStorm.
 *  * User: rdok
 *  * Date: 9/18/2014
 *  * Time: 2:35 PM
 *
 */

try {
	require "../app/config/app.php";

	$appInfoFile = ROOT_PATH . "config/dropbox.app";
# Include the Dropbox SDK libraries
	require_once ROOT_PATH . "plugins/dropbox-php-sdk-1.1.3/lib/Dropbox/autoload.php";
	use Dropbox as dbx;

	include_once(ROOT_PATH . '/plugins/mysqldump-php-1.4.1/src/Ifsnop/Mysqldump/Mysqldump.php');

	date_default_timezone_set('Europe/Athens');
	$curWorkingDate = new DateTime();
	$curWorkingHour = intval($curWorkingDate->format('H'));
	// save resources - only run cron at hours 08:00 - 18:00
	if ($curWorkingHour < App::WORKING_HOUR_START || $curWorkingHour > App::WORKING_HOUR_END) exit();

	$filePath = ROOT_PATH . 'storage/backups/database_backup_' . date('m-d-Y H:i') . '.sql';


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
	$dump->start($filePath);

	$accessToken = DropboxFetcher::retrieveAccessToken(DropboxCon::SERVICE_APP_DATABASE_BACKUP);
	$dbxClient = new dbx\Client($accessToken, "PHP-Example/1.0");
	$adminAccountInfo = $dbxClient->getAccountInfo();
	$f = fopen($filePath . $fileName, "rb");
	$result = $dbxClient->uploadFile("/backups/$fileName", dbx\WriteMode::add(), $f);
	fclose($f);

//	Mailer::sendDevelopers("Backup created: " . $filename, __FILE__);
} catch (\Exception $e) {
	Mailer::sendDevelopers('mysqldump-php error: ' . $e->getMessage(), __FILE__);
}