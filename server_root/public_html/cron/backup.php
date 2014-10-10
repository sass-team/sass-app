<?php
/**
 * // * Created by PhpStorm.
 * // * User: rdok
 * // * Date: 9/18/2014
 * // * Time: 2:35 PM
 * // */
//
//require __DIR__ . '/init.php';
//
//
//$filename = __DIR__ . '/' . 'database_backup_' . date('G_a_m_d_y') . '.sql';
//$command = 'mysqldump --user=' . DB_USER . ' --host=' . DB_HOST . ' ' . DB_NAME . ' >' . $filename;
//exec($command, $output, $message);
//
//switch ($message) {
//	case 0:
//		$message = 'Database <b>' . DB_NAME . '</b> successfully exported to <b>~/' . $filename . '</b>';
//		Mailer::sendDevelopers($message, __FILE__);
//		break;
//	case 1:
//		$message = 'There was a warning during the export of <b>' . DB_NAME . '</b> to <b>' . $filename . '</b>';
//		Mailer::sendDevelopers($message, __FILE__);
//		break;
//	case 2:
//		$message = 'There was an error during export. Please check your values:<br/><br/><table><tr><td>MySQL Database
//			Name:</td><td><b>' . DB_NAME . '</b></td></tr><tr><td>MySQL User Name:</td><td><b>' . DB_USER .
//			'</b></td></tr><tr><td>MySQL Password:</td><td><b>NOTSHOWN</b></td></tr><tr><td>MySQL Host Name:</td><td><b>'
//			. DB_HOST . '</b></td></tr></table>';
//		Mailer::sendDevelopers($message, __FILE__);
//		break;
//	default:
//		Mailer::sendDevelopers(var_dump($message), __FILE__);
//}


try {
	require __DIR__ . '/init.php';

	include_once(ROOT_PATH . '/plugins/mysqldump-php-1.4.1/src/Ifsnop/Mysqldump/Mysqldump.php');
	$filename = ROOT_PATH . 'storage/backups/database_backup_' . date('G_a_F_d_Y') . '.sql';


	$dumpSettings = array(
		'compress' => Ifsnop\Mysqldump\Mysqldump::GZIP,
		'no-data' => false,
		'add-drop-table' => true,
		'single-transaction' => true,
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

	$dump = new Ifsnop\Mysqldump\Mysqldump(DB_NAME, DB_USER, DB_PASS, DB_HOST, 'mysql', $dumpSettings);
	Mailer::sendDevelopers("Backup created: " . $filename, __FILE__);

	$dump->start($filename);
} catch (\Exception $e) {
	Mailer::sendDevelopers('mysqldump-php error: ' . $e->getMessage(), __FILE__);

}