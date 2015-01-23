<?php
require __DIR__ . '/app/init.php';
$general->loggedOutProtect();
// redirect if user elevation is not that of admin
if (!$user->isAdmin()) {
	header('Location: ' . BASE_URL . "error-403");
	exit();
}

include_once(ROOT_PATH . '/plugins/mysqldump-php-1.4.1/src/Ifsnop/Mysqldump/Mysqldump.php');
$appInfoFile = ROOT_PATH . "config/dropbox.app";
# Include the Dropbox SDK libraries
require_once ROOT_PATH . "plugins/dropbox-sdk/lib/Dropbox/autoload.php";
use Dropbox as dbx;

try {

	$terms = TermFetcher::retrieveAll();

	$accessTokenDatabase = DropboxFetcher::retrieveAccessToken(DropboxCon::SERVICE_APP_DATABASE_BACKUP)[DropboxFetcher::DB_COLUMN_ACCESS_TOKEN];
	$accessTokenExcel = DropboxFetcher::retrieveAccessToken(DropboxCon::SERVICE_APP_EXCEL_BACKUP)[DropboxFetcher::DB_COLUMN_ACCESS_TOKEN];

//	var_dump($accessTokenDatabase);
	$appInfo = dbx\AppInfo::loadFromJsonFile($appInfoFile);
	$clientIdentifier = "sass-app/1.0";
	$webAuth = new dbx\WebAuthNoRedirect($appInfo, $clientIdentifier, "en");

	if ($accessTokenDatabase !== NULL) {
		try {
			$dbxClientDB = new dbx\Client($accessTokenDatabase, "PHP-Example/1.0");
			$accountInfoDatabase = $dbxClientDB->getAccountInfo();
		} catch (Exception $e) {
			$errors[] = "Could not access Dropbox account database. Maybe user has revoked access?";
		}
	}
	if ($accessTokenExcel !== NULL) {
		try {
			$dbxClientExcel = new dbx\Client($accessTokenExcel, "PHP-Example/1.0");
			$accountInfoExcel = $dbxClientExcel->getAccountInfo();
		} catch (Exception $e) {
			$errors[] = "Could not access Dropbox account excel. Maybe user has revoked access?";
		}
	}


	if (isBtnRqstDropboxConnectionPrsd()) {
		$authCode = $_POST['dropbox-key-token-db'];
		if (empty($authCode)) throw new Exception("Key token is required.");
		list($accessTokenDatabase, $userId) = $webAuth->finish($authCode);
		DropboxCon::insertAccessToken($accessTokenDatabase, $user->getId(), DropboxCon::SERVICE_APP_DATABASE_BACKUP);

		header('Location: ' . BASE_URL . "cloud/success");
		exit();
	} else if (isBtnRqstDropboxConnectExcelKeyPrsd()) {
		$authCode = $_POST['dropbox-key-token-excel'];
		if (empty($authCode)) throw new Exception("Key token is required.");
		list($accessTokenExcel, $userId) = $webAuth->finish($authCode);
		DropboxCon::insertAccessToken($accessTokenExcel, $user->getId(), DropboxCon::SERVICE_APP_EXCEL_BACKUP);
		header('Location: ' . BASE_URL . "cloud/success");
		exit();
	} else if (isBtnRqstTokenDatabaseDropboxKeyPrsd() || isBtnRqstTokenExcelDropboxKeyPrsd()) {
		$authorizeUrl = $webAuth->start();
		header("Location: $authorizeUrl");
		exit();
	} else if (isset($_POST['disconnect-dropbox-database-btn'])) {
		DropboxFetcher::disconnectServiceType(DropboxCon::SERVICE_APP_DATABASE_BACKUP);
		header('Location: ' . BASE_URL . "cloud/success");
		exit();
	} else if (isset($_POST['disconnect-dropbox-excel-btn'])) {
		DropboxFetcher::disconnectServiceType(DropboxCon::SERVICE_APP_EXCEL_BACKUP);
		header('Location: ' . BASE_URL . "cloud/success");
		exit();
	} else if (isBtnRqstDnldDBKeyPrsd()) {

		date_default_timezone_set('Europe/Athens');
		$curWorkingDate = new DateTime();
		$curWorkingHour = intval($curWorkingDate->format('H'));

		$filePath = ROOT_PATH . 'storage/backups/';
		$fileName = 'sass_app_db_' . date('m_d_Y_Hi') . '.sql';
		$zippedFileName = $fileName . '.gz';
		$fullPathName = $filePath . $fileName;

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

		$dump = new Ifsnop\Mysqldump\Mysqldump(App::$dsn[App::DB_NAME],
			App::$dsn[DatabaseManager::DB_USERNAME],
			App::$dsn[DatabaseManager::DB_PASSWORD],
			App::$dsn[DatabaseManager::DB_HOST], 'mysql', $dumpSettings);
		$dump->start($fullPathName);

		// all credits: http://stackoverflow.com/q/22046020/2790481
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=\"" . basename($zippedFileName) . "\";");
		header("Content-Type: application/octet-stream");
		header("Content-Encoding: binary");
		header("Content-Length: " . filesize($filePath . $zippedFileName));
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private");
		header("Pragma: public");
		ob_clean();
		readfile($filePath . $zippedFileName);
	} else if (isBtnRqstDownloadExcelKeyPrsd()) {
		Excel::downloadAppointments($_POST['termId']);
		exit();
	}

} catch
(Exception $e) {
	$errors[] = $e->getMessage();
}

/**
 * @return bool
 */
function isBtnRqstDropboxConnectionPrsd() {
	return isset($_POST['request-dropbox-database-connection-btn']);
}

/**
 * http://stackoverflow.com/a/3403863
 *
 * @param $f_location
 * @param $f_name
 */
function download($f_location, $f_name) {
	header('Content-Description: File Transfer');
	header('Content-Type: application/x-zip');
	header('Content-Length: ' . filesize($f_location));
	header('Content-Disposition: attachment; filename=' . basename($f_name));
	header('Content-Encoding: gz');
	readfile($f_location);
}


function isModificationSuccess() {
	return isset($_GET['success']) && strcmp($_GET['success'], 'y1!q' === 0);
}

function isBtnRqstTokenDatabaseDropboxKeyPrsd() {
	return isset($_POST['request-dropbox-key-token-db-database-btn']) && empty($_POST['request-dropbox-key-token-db-database-btn']);
}

function isBtnRqstDropboxConnectExcelKeyPrsd() {
	return isset($_POST['request-dropbox-key-connection-excel-btn']) && empty($_POST['request-dropbox-key-connection-excel-btn']);
}


function isBtnRqstDownloadExcelKeyPrsd() {
	return isset($_POST['download-appointments-excel-btn']) && empty($_POST['download-appointments-excel-btn']);
}

function isBtnRqstDnldDBKeyPrsd() {
	return isset($_POST['download-sass-backup-database-btn']) && empty($_POST['download-sass-backup-database-btn']);
}

function isBtnRqstTokenExcelDropboxKeyPrsd() {
	return isset($_POST['request-dropbox-key-token-db-excel-btn']) && empty($_POST['request-dropbox-key-token-db-excel-btn']);
}


// viewers
$pageTitle = "Cloud - SASS App";
$section = "cloud";


?>


<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<?php require ROOT_PATH . 'views/head.php'; ?>
<body>
<div id="wrapper">
<?php
require ROOT_PATH . 'views/header.php';
require ROOT_PATH . 'views/sidebar.php';
?>


<div id="content">

<div id="content-header">
	<h1>Cloud</h1>
</div>
<!-- #content-header -->

<div id="content-container">


<h3 class="heading">Cloud Services</h3>
<?php
if (empty($errors) === false) {
	?>
	<div class="alert alert-danger">
		<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
		<strong>Oh
			snap!</strong><?php echo '<p>' . implode('</p><p>', $errors) . '</p>';
		?>
	</div>
<?php
} else if (isModificationSuccess()) {
	?>
	<div class="alert alert-success">
		<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
		<strong>Data successfully modified!</strong> <br/>
	</div>
<?php
}?>


<div class="row">

<div class="col-md-12">

<div class="table-responsive">

<form method="post" action="<?php echo BASE_URL . 'cloud/'; ?>" id="request-full-form" class="form">
<table class="table table-striped table-bordered media-table">
<thead>

<tr>
	<th style="width: 150px">Services</th>
	<th>Description</th>
	<th class="text-center">Action</th>
	<th class="text-center">Data Required</th>

</tr>
</thead>
<tbody>
<tr>
	<td>
		<div class="thumbnail">
			<img
				src="<?php echo BASE_URL; ?>assets/img/logos/logo-sass-excel.png"
				width="125"
				alt="logo sass-excel"/>
		</div>
		<!-- /.thumbnail -->
	</td>
	<td>
		<p>Manually create <strong>Visit Log as excel file and download.</p>
		<a href="http://en.wikipedia.org/wiki/Microsoft_Excel#Excel_2007_.28v12.0.29" title="Excel 2007"
		   target="_blank">Excel 2007</a>
	</td>
	<td class="text-center">
		<div class="btn-group">
			<button type="button" class="btn btn-default" id="download-appointments-excel-btn">
				<i class="fa fa-download fa-fw"></i>
				Download
			</button>
		</div>
	</td>
	<td>
		<select id="termId" name="termId" class="form-control" required>
			<?php
			foreach ($terms as $term) {
				include(ROOT_PATH . "views/partials/term/select-options-view.html.php");
			}
			?>
		</select>
	</td>
</tr>
<?php

if (!empty($accessTokenExcel) && !empty($accountInfoExcel)) {
	?>
	<tr>
		<td>
			<div class="thumbnail">
				<img
					src="<?php echo BASE_URL; ?>assets/img/logos/logo-sass-dropbox-excel.png"
					width="125"
					alt="logo sass-dropbox-excel"/>
			</div>
			<!-- /.thumbnail -->
		</td>
		<td>
			<p>Visit Log are currently generated hourly on the Dropbox Account Connected.</p>

			<p><strong>Account
					connected:</strong> <?php echo $accountInfoExcel['display_name']
					. ", " . $accountInfoExcel['email']; ?>
			</p>
			<a href="https://www.dropbox.com/" title="Dropbox"
			   target="_blank">Dropbox</a> &#124;
			<a href="http://en.wikipedia.org/wiki/Microsoft_Excel#Excel_2007_.28v12.0.29" title="Excel 2007"
			   target="_blank">Excel 2007</a>
		</td>
		<td class="text-center">
			<div class="btn-group">
				<button type="button" class="btn btn-default"
				        id="disconnect-dropbox-excel-btn">
					<i class="fa fa-dropbox fa-fw"></i>
					Disconnect
				</button>
			</div>
		</td>
		<td>
		</td>
	</tr>
<?php
} else {
	?>

	<tr>
		<td>
			<div class="thumbnail">
				<img src="<?php echo BASE_URL; ?>assets/img/logos/logo-sass-dropbox-excel.png"
				     width="125"
				     alt="logo sass-dropbox-database"/>
			</div>
			<!-- /.thumbnail -->
		</td>
		<td>
			<a href="https://www.dropbox.com/" title="Dropbox" target="_blank">Dropbox</a>

			<p>Connect a Dropbox account with SASS App <strong>Visit Log Excel</strong> files.</p>

		</td>
		<td class="text-center">
			<div class="btn-group">
				<button type="button" class="btn btn-default dropdown-toggle"
				        data-toggle="dropdown"><i class="fa fa-dropbox fa-fw"></i>
					Request
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" role="menu">
					<li><a id="request-dropbox-key-token-db-excel-btn">Key Token</a></li>
					<li><a id="request-dropbox-key-connection-excel-btn">Connection</a></li>
				</ul>
			</div>
		</td>
		<td>
			<input type="text" class="form-control" name="dropbox-key-token-excel"
			       placeholder="Key Token" required>
		</td>
	</tr>
<?php
}
?>
<?php

if (!empty($accessTokenDatabase) && !empty($accountInfoDatabase)) {
	?>
	<tr>
		<td>
			<div class="thumbnail">
				<img
					src="<?php echo BASE_URL; ?>assets/img/logos/dropbox-database-sass.png"
					width="125"
					alt="logo sass-dropbox-database"/>
			</div>
			<!-- /.thumbnail -->
		</td>
		<td>
			<p>Database backups are currently generated hourly on the Dropbox Account Connected.</p>

			<p><strong>Account
					connected:</strong> <?php echo $accountInfoDatabase['display_name']
					. ", " . $accountInfoDatabase['email']; ?>
			</p>
			<a href="https://www.dropbox.com/" title="Dropbox"
			   target="_blank">Dropbox</a> &#124;
			<a href="http://www.7-zip.org/download.html" title="7zip"
			   target="_blank">7-Zip</a> &#124;
			<a href="http://en.wikipedia.org/wiki/SQL" title="SQL"
			   target="_blank">SQL</a>
		</td>
		<td class="text-center">
			<div class="btn-group">
				<button type="button" class="btn btn-default"
				        id="disconnect-dropbox-database-btn">
					<i class="fa fa-dropbox fa-fw"></i>
					Disconnect
				</button>
			</div>
		</td>
		<td>
		</td>
	</tr>
<?php
} else {
	?>

	<tr>
		<td>
			<div class="thumbnail">
				<img src="<?php echo BASE_URL; ?>assets/img/logos/dropbox-database-sass.png"
				     width="125"
				     alt="logo sass-dropbox-database"/>
			</div>
			<!-- /.thumbnail -->
		</td>
		<td><a href="https://www.dropbox.com/" title="Dropbox" target="_blank">Dropbox</a>

			<p>Connect a Dropbox account with SASS App <strong>database backup files.</strong> <a
					href="http://en.wikipedia.org/wiki/SQL" title="SQL"
					target="_blank">SQL</a><br/>
			</p>

		</td>
		<td class="text-center">
			<div class="btn-group">
				<button type="button" class="btn btn-default dropdown-toggle"
				        data-toggle="dropdown"><i class="fa fa-dropbox fa-fw"></i>
					Request
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" role="menu">
					<li><a id="request-dropbox-key-token-db-database-btn">Key Token</a></li>
					<li><a id="request-dropbox-database-connection-btn">Connection</a></li>
				</ul>
			</div>
		</td>
		<td>
			<input type="text" class="form-control" name="dropbox-key-token-db" id="dropbox-key-token-db"
			       placeholder="Key Token" required>
		</td>
	</tr>
<?php
}
?>

<tr>
	<td>
		<div class="thumbnail">
			<img
				src="<?php echo BASE_URL; ?>assets/img/logos/sass-db-export.png"
				width="125"
				alt="logo sass-export-database"/>
		</div>
		<!-- /.thumbnail -->
	</td>
	<td>
		<p>Manually create a full SASS App <strong>database backup and download.</strong></p>
		<a href="http://www.7-zip.org/download.html" title="7zip"
		   target="_blank">7-Zip</a> &#124;
		<a href="http://en.wikipedia.org/wiki/SQL" title="SQL"
		   target="_blank">SQL</a>
	</td>
	<td class="text-center">
		<div class="btn-group">
			<button type="button" class="btn btn-default"
			        id="download-sass-backup-database-btn">
				<i class="fa fa-download fa-fw"></i>
				Download
			</button>
		</div>
	</td>
	<td>
	</td>
</tr>
</tbody>
</table>
</form>
</div>
<!-- /.table-responsive -->


</div>
<!-- /.col -->

</div>
<!-- /.row -->


</div>
<!-- /#content-container -->


</div>
<!-- #content -->


<?php include ROOT_PATH . "views/footer.php"; ?>
</div>
<!-- #wrapper<!-- #content -->

<?php include ROOT_PATH . "views/assets/footer_common.php"; ?>
<script src="<?php echo BASE_URL; ?>assets/js/plugins/select2/select2.js"></script>

<script type="text/javascript">
	$(function () {
		var $form = $('#request-full-form');
		var $termId = $('#termId');
		$termId.select2();

		$('#download-appointments-excel-btn, #download-sass-backup-database-btn, #request-dropbox-key-connection-excel-btn, #disconnect-dropbox-excel-btn, #dropbox-disconnect-database-backup, #request-dropbox-database-connection-btn, #disconnect-dropbox-database-btn').on('click', function () {
			$form.attr('target', '_self');

			var input = $("<input>", {type: "hidden", name: $(this).attr("id")});
			$form.append($(input));
			$form.submit();
		});

		$('#request-dropbox-key-token-db-database-btn, #request-dropbox-key-token-db-excel-btn').on('click', function () {
			$form.attr('target', '_blank');

			var input = $("<input>", {type: "hidden", name: $(this).attr("id")});
			$form.append($(input));
			$form.submit();
		});


	});
</script>
</body>
</html>
