<?php
/**
 *  * Created by PhpStorm.
 *  * User: rdok
 *  * Date: 9/18/2014
 *  * Time: 2:35 PM
 *
 */
require "../public_html/app/config/app.php";

$appInfoFile = ROOT_PATH . "config/dropbox.app";
# Include the Dropbox SDK libraries
require_once ROOT_PATH . "plugins/dropbox-php-sdk-1.1.3/lib/Dropbox/autoload.php";
include_once(ROOT_PATH . '/plugins/mysqldump-php-1.4.1/src/Ifsnop/Mysqldump/Mysqldump.php');

use Dropbox as dbx;


try {
	date_default_timezone_set('Europe/Athens');
	$curWorkingDate = new DateTime();
	$curWorkingHour = intval($curWorkingDate->format('H'));
	// save resources - only run cron at hours 08:00 - 18:00
	if ($curWorkingHour < App::WORKING_HOUR_START || $curWorkingHour > App::WORKING_HOUR_END) exit();

	$filePath = ROOT_PATH . 'storage/excel/';
	$curTerms = TermFetcher::retrieveCurrTerm();

	foreach ($curTerms as $curTerm) {
		$curTermId = $curTerm[TermFetcher::DB_COLUMN_ID];
		$curTermName = $curTerm[TermFetcher::DB_COLUMN_NAME];
		$curTermStartDateTime = new DateTime($curTerm[TermFetcher::DB_COLUMN_START_DATE]);
		$curTermYear = $curTermStartDateTime->format('Y');

		$fullPathFile = Excel::saveAppointments($curTermId);
		$fileName = pathinfo($fullPathFile)['filename'] . "." . pathinfo($fullPathFile)['extension'];
		$accessToken = DropboxFetcher::retrieveAccessToken(DropboxCon::SERVICE_APP_EXCEL_BACKUP)[DropboxFetcher::DB_COLUMN_ACCESS_TOKEN];

		$dbxClient = new dbx\Client($accessToken, App::VERSION);
		$adminAccountInfo = $dbxClient->getAccountInfo();
		$f = fopen($fullPathFile, "rb");
		$result = $dbxClient->uploadFile("/storage/excel/$curTermYear/$fileName", dbx\WriteMode::force(), $f);
		fclose($f);
	}
	exit();
} catch (\Exception $e) {
	Mailer::sendDevelopers('mysqldump-php error: ' . $e->getMessage(), __FILE__);
}