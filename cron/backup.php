<?php
/**
 *  * Created by PhpStorm.
 *  * User: rdok
 *  * Date: 9/18/2014
 *  * Time: 2:35 PM
 *
 */
require __DIR__ . "/../public_html/app/config/app.php";

$appInfoFile = ROOT_PATH . "config/dropbox.app";
# Include the Dropbox SDK libraries
require_once ROOT_PATH . "plugins/dropbox-sdk/lib/Dropbox/autoload.php";
include_once(ROOT_PATH . '/plugins/mysqldump-php-1.4.1/src/Ifsnop/Mysqldump/Mysqldump.php');

use Dropbox as dbx;


try
{
	date_default_timezone_set(App::getTimeZone());

	// run script only during working hours
	if (!App::isWorkingDateTimeOn())
	{
		exit();
	}

	$filePath = ROOT_PATH . 'storage/backups/';
	$fileName = 'sass app db ' . date('m-d-Y Hi') . '.sql';
	$zippedFileName = $fileName . '.gz';
	$zippedFullFileName = $filePath . $zippedFileName;
	$curWorkingDateYear = App::getCurWorkingDate()->format('Y');

	$dumpSettings = [
		'compress'                   => Ifsnop\Mysqldump\Mysqldump::GZIP,
		'no-data'                    => false,
		'add-drop-table'             => true,
		'single-transaction'         => false,
		'lock-tables'                => true,
		'add-locks'                  => true,
		'extended-insert'            => true,
		'disable-foreign-keys-check' => true,
		'skip-triggers'              => false,
		'add-drop-trigger'           => true,
		'databases'                  => false,
		'add-drop-database'          => false,
		'hex-blob'                   => true
	];

	$dump = new Ifsnop\Mysqldump\Mysqldump(App::getDbName(),
		App::getDbUsername(),
		App::getDbPassword(),
		App::getDbHost(), 'mysql', $dumpSettings);
	$dump->start($filePath . $fileName);


	$accessToken = DropboxFetcher::retrieveAccessToken(DropboxCon::SERVICE_APP_DATABASE_BACKUP)[DropboxFetcher::DB_COLUMN_ACCESS_TOKEN];

	$dbxClient = new dbx\Client($accessToken, App::getVersion());
	$adminAccountInfo = $dbxClient->getAccountInfo();
	$f = fopen($zippedFullFileName, "rb");
	$result = $dbxClient->uploadFile("/storage/backups/$curWorkingDateYear/$zippedFileName", dbx\WriteMode::add(), $f);
	fclose($f);


} catch (Exception $e)
{
	App::storeError($e->getMessage());
	exit();
}